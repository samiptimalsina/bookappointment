<?php

namespace App\Modules\Appointment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Http\Requests\Admin\AppointmentSetupRequest;
use App\Modules\Appointment\Repositories\AppointmentTypeRepository;
use App\Modules\Appointment\Repositories\AppointmentSetupRepository;
use App\Modules\Appointment\Repositories\AppointmentMasterRepository;
use Illuminate\Http\Request;

class AppointmentSetupController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Appointment Setup';
        $setups = $this->setup->model()->whereDate('effective_to', '>=', date('Y-m-d'))->get();
        return view('appointment::admin.appointment.setup.index', compact('title', 'setups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Schedule';
        $appointmentTypes = $this->type->lists();
        return view('appointment::admin.appointment.setup.create', compact('title', 'appointmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentSetupRequest $request)
    {
        $inputs = $request->validated();
        $dateRange = explode(' - ', $inputs['appointment_date']);
        $startDate = explode('/', $dateRange[0]);
        $endDate = explode('/', $dateRange[1]);
        $inputs['effective_from'] = $startDate[2] . '-' . $startDate[0] . '-' . $startDate[1];
        $inputs['effective_to'] = $endDate[2] . '-' . $endDate[0] . '-' . $endDate[1];
        $this->setup->createAppointments($inputs);
        return redirect()->route('admin.appointment.setup.index')->with('flash_success', 'Appointment Schedule created.');
    }
}
