<?php

namespace App\Modules\Appointment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Repositories\AppointmentMasterRepository;
use App\Modules\Appointment\Repositories\AppointmentSetupRepository;
use App\Modules\Appointment\Repositories\AppointmentTypeRepository;
use App\Modules\Appointment\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use App\Helpers\Misc;
use DB;
use Yajra\DataTables\DataTables;
use App\Modules\Appointment\Mail\AppointmentCancelNotifier;
use Illuminate\Support\Facades\Mail;
use App\Modules\Appointment\Events\DeleteGoogleCalendarScheduleEvent;


class AppointmentController extends Controller
{
    /**
     * The AppointmentTypeRepository implementation.
     *
     * @var AppointmentTypeRepository
     */
    protected $appointmentType;

    /**
     * The AppointmentSetupRepository implementation.
     *
     * @var AppointmentSetupRepository
     */
    protected $appointmentSetup;

    /**
     * The AppointmentMasterRepository implementation.
     *
     * @var AppointmentMasterRepository
     */
    protected $appointmentMaster;

    /**
     * The AppointmentRepository implementation.
     *
     * @var AppointmentRepository
     */
    protected $appointment;

    /**
     * Create a new controller instance.
     *
     * @param AppointmentTypeRepository $appointmentType
     * @param AppointmentSetupRepository $appointmentSetup
     * @param AppointmentMasterRepository $appointmentMaster
     * @param AppointmentRepository $appointment
     */
    public function __construct(
        AppointmentTypeRepository   $appointmentType,
        AppointmentSetupRepository  $appointmentSetup,
        AppointmentMasterRepository $appointmentMaster,
        AppointmentRepository       $appointment
    )
    {
        $this->type = $appointmentType;
        $this->setup = $appointmentSetup;
        $this->master = $appointmentMaster;
        $this->appointment = $appointment;
        $this->middleware('permission:appointment-view,admin', ['only' => ['index']]);
        $this->middleware('permission:appointment-create,admin', ['only' => ['create', 'store']]);
        $this->middleware('permission:appointment-edit,admin', ['only' => ['edit', 'update']]);
        $this->middleware('permission:appointment-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('appointment::admin.appointment.appointment.index');
    }

    public function getAppointments(Request $request)
    {
        $appointments = DB::table("appointments")
            ->select(
                'appointments.id',
                'appointments.appointment_date as appointment_date',
                'appointments.start_time',
                'appointments.end_time',
                'appointments.first_name',
                'appointments.last_name',
                'appointments.phone',
                'appointments.email',
                'appointments.address',
                'appointments.is_completed',
                'appointment_types.id as appointment_type_id',
                'appointment_types.name as appointment_type_name'
            )
            ->join('appointment_setups', 'appointment_setups.id', '=', 'appointments.appointment_setup_id')
            ->join('appointment_types', 'appointment_types.id', '=', 'appointment_setups.appointment_type_id')
            ->whereNull('appointments.deleted_at')
            ->orderBy('appointments.appointment_date', 'desc')
            ->orderBy('appointments.start_time', 'asc');

        return Datatables::of($appointments)
            ->addColumn('appointment_date', function ($r) {
                return Misc::usFormatDate($r->appointment_date);
            })
            ->addColumn('full_name', function ($r) {
                return $r->first_name . ' ' . $r->last_name;
            })
            ->addColumn('appointment_time', function ($r) {
                return date("h:i a", strtotime($r->start_time)) . ' - ' . date("h:i a", strtotime($r->end_time));
            })
            ->addColumn('is_completed', function ($r) {
                if ($r->is_completed) {
                    return '<span class="badge badge-success">Yes</span>';
                } else {
                    return '<span class="badge badge-primary">No</span>';
                }
            })
            ->addColumn('actions', function ($r) {
                return '<a href="' . route('admin.appointment.appointment.show', $r->id) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View Detail" data-toggle="m-tooltip" data-original-title="View Detail"><i class="far fa-eye"></i></a>';
            })
            ->rawColumns(['actions', 'is_completed'])
            ->filter(function ($query) use ($request) {
                if (@$request->search['value']) {
                    $query->where('appointments.first_name', 'like', "%{$request->search['value']}%")
                        ->orWhere('appointments.last_name', 'like', "%{$request->search['value']}%")
                        ->orWhere('appointments.phone', 'like', "%{$request->search['value']}%")
                        ->orWhere('appointments.email', 'like', "%{$request->search['value']}%")
                        ->orWhere('users.name', 'like', "%{$request->search['value']}%")
                        ->orWhere('appointments.address', 'like', "%{$request->search['value']}%")
                        ->orWhere('appointment_types.name', 'like', "%{$request->search['value']}%");
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function show(Request $request, $id)
    {
        $title = 'Appointment Detail';
        $appointment = $this->appointment->findWith($id, ['appointmentMaster', 'appointmentMaster.type']);
        if (!$appointment)
            abort(404);
        return view('appointment::admin.appointment.appointment.show', compact('title', 'appointment'));
    }


    public function markCompleted(Request $request)
    {
        $inputs = $request->all();
        $appointment = $this->appointment->find($inputs['id']);
        $appointment = $this->appointment->update($appointment->id, ['is_completed' => 1]);
        $message = 'Appointment has been mark as completed';
        return response()->json(['status' => 'ok', 'message' => $message, 'appointment' => $appointment], 200);
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointmentId = \Crypt::decrypt($id);
        $appointment = $this->appointment->find($appointmentId);
        if (!$appointment)
            abort(404);
        if ($appointment->is_completed == 1)
            abort(404);
        Mail::send(new AppointmentCancelNotifier($appointment));
        event(new DeleteGoogleCalendarScheduleEvent($appointment));
        $appointmentMaster = $this->master->find($appointment->appointment_master_id);
        $appointmentMaster->increment('available_slot');
        $appointmentMaster->decrement('used_slot');
        $this->appointment->destroy($appointment->id);
        return redirect()->route('admin.appointment.appointment.index')->with('flash_success', 'Appointment has cancelled successfully.');
    }
}
