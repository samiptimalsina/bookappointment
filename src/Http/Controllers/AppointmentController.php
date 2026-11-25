<?php

namespace App\Modules\Appointment\Http\Controllers;

use App\Http\Controllers\Controller;
use SEO;
use Illuminate\Http\Request;
use App\Repositories\SeoRepository;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\PageBannerRepository;
use App\Modules\Appointment\Repositories\AppointmentTypeRepository;
use App\Modules\Appointment\Repositories\AppointmentSetupRepository;
use App\Modules\Appointment\Repositories\AppointmentMasterRepository;
use App\Repositories\ZipCodeRepository;
use App\Modules\Appointment\Http\Requests\AppointmentRequest;
use App\Modules\Appointment\Repositories\AppointmentRepository;
use App\Modules\Appointment\Events\AddEventInGoogleCalendarEvent;
use App\Modules\Appointment\Events\AppointmentBookedSmsClientNotificationEvent;
use App\Modules\Appointment\Events\AppointmentBookedSmsAdminNotificationEvent;
use App\Modules\Appointment\Events\DeleteGoogleCalendarScheduleEvent;
use App\Modules\Appointment\Mail\AppointmentConfirmUserMailer;
use App\Modules\Appointment\Mail\AppointmentConfirmAdminMailer;
use Illuminate\Support\Facades\Mail;
use App\Modules\Appointment\Mail\AppointmentCancelNotifier;
use App\Modules\Appointment\Mail\AppointmentCancelAdminNotifier;


class AppointmentController extends Controller
{
    /**
     * The SeoRepository implementation.
     *
     * @var SeoRepository
     */
    protected $seo;
    /**
     * The PageBannerRepository implementation.
     *
     * @var PageBannerRepository
     */
    protected $pageBanner;

    /**
     * The AppointmentTypeRepository implementation.
     *
     * @var AppointmentTypeRepository
     */
    protected $type;

    /**
     * The AppointmentSetupRepository implementation.
     *
     * @var AppointmentSetupRepository
     */
    protected $setup;

    /**
     * The AppointmentMasterRepository implementation.
     *
     * @var AppointmentMasterRepository
     */
    protected $master;

    /**
     * The AppointmentRepository implementation.
     *
     * @var AppointmentRepository
     */
    protected $appointment;

    /**
     * The Zip Code repository implementation.
     *
     * @var ZipCodeRepository
     */
    protected $zipCode;

    /**
     * Create a new controller instance.
     *
     * @param SeoRepository $seo
     * @param PageBannerRepository $pageBanner
     * @param AppointmentTypeRepository $type
     * @param AppointmentSetupRepository $setup
     * @param AppointmentMasterRepository $master
     * @param AppointmentRepository $appointment
     * @param ZipCodeRepository $zipCode
     */
    public function __construct(SeoRepository $seo, PageBannerRepository $pageBanner, AppointmentTypeRepository $type, AppointmentSetupRepository $setup, AppointmentMasterRepository $master, AppointmentRepository $appointment, ZipCodeRepository $zipCode)
    {
        $this->seo = $seo;
        $this->pageBanner = $pageBanner;
        $this->type = $type;
        $this->setup = $setup;
        $this->master = $master;
        $this->appointment = $appointment;
        $this->zipCode = $zipCode;
    }

    public function index()
    {
        $seo = $this->seo->where('slug', '=', 'appointment')->first();
        if (!$seo) {
            $seo = $this->seo->where('slug', '=', 'generic')->first();
        }
        $pageBanner = $this->pageBanner->where('slug', '=', 'appointment')->first();
        if (isset($pageBanner) && !$pageBanner->getMedia('pageBanner')->first()) {
            $pageBanner = $this->pageBanner->where('slug', '=', 'generic')->first();
        }
        $appointmentTypes = $this->type->allActive();
        SEO::setTitle($seo->meta_title);
        SEO::setDescription($seo->meta_description);
        return view('appointment::appointment.index', compact('pageBanner', 'appointmentTypes'));
    }

    public function getAvailableDays(Request $request)
    {
        $from = date('Y-m-d');
        $to = date('Y-m-d', strtotime($from . ' +210 days'));
        $showFromDate = date('Y-m-d');
        if (date("H") > 16) {
            $showFromDate = date('Y-m-d', strtotime("+1 day"));
        }
        $appointmentTypeId = $request->get('appointmentId');
        $availableDays = $this->master->model()->select(['id', 'appointment_date'])->where('is_active', 1)->where('appointment_type_id', $appointmentTypeId)
            ->whereBetween('appointment_date', [$from, $to])
            ->where('appointment_date', '>=', $showFromDate)
            ->groupBy('appointment_date')
            ->get();
        $data = $availableDays->map(function ($availableDay) {
            $availableDay['date'] = $availableDay->appointment_date;
            $availableDay['badge'] = true;

            return $availableDay;
        });
        return response()->json(['status' => 'ok', 'data' => $data], 200);
    }

    public function getAvailableSlot(Request $request)
    {
        $selectedDate = $request->get('selectedDate');
        $appointmentTypeId = $request->get('appointmentTypeId');
        $from = date('Y-m-d');
        $to = date('Y-m-d', strtotime($from . ' +210 days'));

        if (!empty($appointmentTypeId))
            $isAppointmentTypeAvailable = true;
        else
            $isAppointmentTypeAvailable = false;

        if ($selectedDate == date('Y-m-d') && date("H") > 16) {
            $availableSlots = null;

        } elseif ($selectedDate == date('Y-m-d')) {
            $availableSlots = $this->master->model()->where('is_active', 1)->where('appointment_type_id', $appointmentTypeId)
                ->where('appointment_date', $selectedDate)
                ->whereBetween('appointment_date', [$from, $to])
                ->where('available_slot', '>', 0)
                ->where('is_active', 1)
                ->where('start_time', '>', (date('H:i', strtotime('+4 hours'))))
                ->orderBy('start_time', 'asc')
                ->get();

        } else {
            $availableSlots = $this->master->model()->where('is_active', 1)->where('appointment_type_id', $appointmentTypeId)
                ->where('appointment_date', $selectedDate)
                ->whereBetween('appointment_date', [$from, $to])
                ->where('available_slot', '>', 0)
                ->where('is_active', 1)
                ->orderBy('start_time', 'asc')
                ->get();
        }

        if (count($availableSlots) > 0) {
            $availableSlotHtml = '';
            foreach ($availableSlots as $availableSlot) {
                $availableSlotHtml .= '<div class="col-sm-4 col-lg-2">
                                                <label class="btn btn-default button-grid">
                                                    <a href="' . route('appointment.book', Crypt::encrypt($availableSlot->id)) . '">
                                                        <span class="button-grid-word">' . date('g:i a', strtotime($availableSlot->start_time)) . ' - ' . date('g:i a', strtotime($availableSlot->end_time)) . ' </span>
                                                        <p style="color: #fff;font-size: 12px;">' . $availableSlot->available_slot . ' Appointment Available</p>
                                                    </a>
                                                </label>
                                            </div>';
            }
        } else {
            $availableSlotHtml = '<div class="col-12"><p>No Appointment Available for ' . $selectedDate . '</p></div>';
        }
        return response()->json(['status' => 'ok', 'data' => $availableSlotHtml, 'isAppointmentTypeAvailable' => $isAppointmentTypeAvailable], 200);
    }

    public function create(Request $request, $id)
    {
        $appointmentId = Crypt::decrypt($id);
        $appointmentMaster = $this->master->findWith($appointmentId, ['setup']);

        if (!$appointmentMaster)
            abort(404);
        SEO::setTitle("Book An Appointment");
        SEO::setDescription("Book An Appointment");

        $pageBanner = $this->pageBanner->where('slug', '=', 'appointment')->first();
        if (isset($pageBanner) && !$pageBanner->getMedia('pageBanner')->first()) {
            $pageBanner = $this->pageBanner->where('slug', '=', 'generic')->first();
        }
        return view('appointment::appointment.create', compact('appointmentMaster', 'pageBanner'));
    }

    public function store(AppointmentRequest $request, $id)
    {
        $appointmentId = Crypt::decrypt($id);
        $appointmentMaster = $this->master->findWith($appointmentId, ['setup']);
        $inputs = $request->validated();
        $zipCode = $this->zipCode->find($inputs['zip']);
        $inputs['state'] = $zipCode->state_id;
        $inputs['city'] = $zipCode->city_id;
        $inputs['appointment_master_id'] = $appointmentMaster->id;
        $inputs['appointment_setup_id'] = $appointmentMaster->appointment_setup_id;
        $inputs['appointment_date'] = $appointmentMaster->appointment_date;
        $inputs['start_time'] = $appointmentMaster->start_time;
        $inputs['end_time'] = $appointmentMaster->end_time;
        $inputs['is_confirmed'] = 1;
        $inputs['unique_id'] = sha1(time());
        $appointment = $this->appointment->create($inputs);
        $appointmentMaster->increment('used_slot');
        $appointmentMaster->decrement('available_slot');
        event(new AddEventInGoogleCalendarEvent($appointment, $appointmentMaster));
        Mail::send(new AppointmentConfirmUserMailer($appointment, $appointmentMaster));
        Mail::send(new AppointmentConfirmAdminMailer($appointment, $appointmentMaster));
        //event(new AppointmentBookedSmsClientNotificationEvent($appointment));
        //event(new AppointmentBookedSmsAdminNotificationEvent($appointment));
        return redirect()->route('appointment.success', Crypt::encrypt($appointment->id))->with('flash_notice', 'Your appointment has been confirmed.');
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointmentId = \Crypt::decrypt($id);
        $appointment = $this->appointment->findWith($appointmentId);
        if (!$appointment)
            return redirect()->route('appointment.cancelled');
        if ($appointment->is_completed == 1)
            abort(404);

        event(new DeleteGoogleCalendarScheduleEvent($appointment));
        Mail::send(new AppointmentCancelNotifier($appointment));
        Mail::send(new AppointmentCancelAdminNotifier($appointment));
        $appointmentMaster = $this->master->find($appointment->appointment_master_id);
        $appointmentMaster->increment('available_slot');
        $appointmentMaster->decrement('used_slot');
        $this->appointment->destroy($appointment->id);
        return redirect()->route('appointment.cancelled');
    }

    public function cancelSuccess()
    {
        SEO::setTitle("Appointment Success");
        SEO::setDescription("Appointment Success");
        $pageBanner = $this->pageBanner->where('slug', '=', 'appointment')->first();
        if (isset($pageBanner) && !$pageBanner->getMedia('pageBanner')->first()) {
            $pageBanner = $this->pageBanner->where('slug', '=', 'generic')->first();
        }
        return view('appointment::appointment.cancel', compact('pageBanner'));
    }

    public function success(Request $request, $id)
    {
        $appointment = $this->appointment->findWith(Crypt::decrypt($id), ['appointmentMaster', 'appointmentSetup']);
        SEO::setTitle("Appointment Success");
        SEO::setDescription("Appointment Success");
        $pageBanner = $this->pageBanner->where('slug', '=', 'appointment')->first();
        if (isset($pageBanner) && !$pageBanner->getMedia('pageBanner')->first()) {
            $pageBanner = $this->pageBanner->where('slug', '=', 'generic')->first();
        }
        return view('appointment::appointment.success', compact('appointment', 'pageBanner'));
    }
}
