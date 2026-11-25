<?php

namespace App\Modules\Appointment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Http\Requests\Admin\AppointmentSetupRequest;
use App\Modules\Appointment\Repositories\AppointmentMasterRepository;
use App\Modules\Appointment\Repositories\AppointmentSetupRepository;
use App\Modules\Appointment\Repositories\AppointmentTypeRepository;
use Illuminate\Http\Request;

class AppointmentMasterController extends Controller
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
     * Create a new controller instance.
     *
     * @param AppointmentTypeRepository $appointmentType
     * @param AppointmentSetupRepository $appointmentSetup
     * @param AppointmentMasterRepository $appointmentMaster
     */
    public function __construct(
        AppointmentTypeRepository   $appointmentType,
        AppointmentSetupRepository  $appointmentSetup,
        AppointmentMasterRepository $appointmentMaster
    )
    {
        $this->type = $appointmentType;
        $this->setup = $appointmentSetup;
        $this->master = $appointmentMaster;
        $this->middleware('permission:appointment-view,admin', ['only' => ['index']]);
        $this->middleware('permission:appointment-create,admin', ['only' => ['create', 'store']]);
        $this->middleware('permission:appointment-edit,admin', ['only' => ['edit', 'update']]);
        $this->middleware('permission:appointment-delete,admin', ['only' => ['destroy']]);
    }

    public function specificDay(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $appointmentTypeId = $request->get('appointment_type_id') ?? 1;
        if (isset($startDate))
            $startDate = date("Y-m-d", strtotime($startDate));
        else
            $startDate = date("Y-m-d");
        if (isset($endDate))
            $endDate = date("Y-m-d", strtotime($endDate));
        else
            $endDate = date("Y-m-d");
        $appointmentTypes = $this->type->lists();
        $availableAppointments = $this->master->model()->with(['setup', 'type'])
            ->whereBetween('appointment_date', [$startDate, $endDate])->where('appointment_type_id', $appointmentTypeId)
            ->orderBy('appointment_date')->orderBy('start_time', 'asc')->get();
        return view('appointment::admin.appointment.master.specificDay', compact('startDate', 'endDate', 'appointmentTypeId', 'availableAppointments', 'appointmentTypes'));
    }

    public function specificDayChangeStatus(Request $request)
    {
        $record = $this->master->find($request->get('id'));
        if ($record->is_active == 0) {
            $status = 1;
            $message = 'Appointment at ' . date("h:i a", strtotime($record->start_time)) . ' is published.';
        } else {
            $status = 0;
            $message = 'Appointment at ' . date("h:i a", strtotime($record->start_time)) . ' is unpublished.';
        }
        $this->master->update($record->id, array('is_active' => $status));
        $updated = $this->master->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function specificDayBulkChangeStatus(Request $request)
    {
        $inputs = $request->all();
        $appointmentIds = isset($inputs['appointment_id']) ? $inputs['appointment_id'] : null;
        $message = "Please select at least one appointment slot.";
        $errorType = 'flash_error';
        if (sizeof($appointmentIds) > 0) {
            if ($inputs['status'] == 'publish') {
                $this->master->model()->whereIn('id', $appointmentIds)->update(['is_active' => 1]);
                $message = "Selected record status updated to published.";
                $errorType = 'flash_success';
            } else {
                $this->master->model()->whereIn('id', $appointmentIds)->update(['is_active' => 0]);
                $message = "Selected record status updated to unpublished.";
                $errorType = 'flash_success';
            }
        }
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $appointmentTypeId = $request->get('appointment_type_id') ?? 1;
        if (isset($startDate))
            $startDate = date("Y-m-d", strtotime($startDate));
        else
            $startDate = date("Y-m-d");
        if (isset($endDate))
            $endDate = date("Y-m-d", strtotime($endDate));
        else
            $endDate = date("Y-m-d");
        return redirect()->route('admin.appointment.master-setup.specific-day', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'appointment_type_id' => $appointmentTypeId,
        ])->with($errorType, $message)->with('status', $errorType);
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Schedule ";
        $masterSetup = $this->master->find($id);
        return view('appointment::admin.appointment.master.edit', compact('title', 'masterSetup'));
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $masterSetup = $this->master->find($id);
        $this->master->update($masterSetup->id, $inputs);
        return redirect()->route('admin.appointment.master-setup.specific-day', array('date' => \App\Helpers\Misc::usFormatDate($masterSetup->appointment_date)))->with('flash_success', 'Record updated successfully.');
    }
}
