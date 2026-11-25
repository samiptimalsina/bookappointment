<?php

/*
 * @project Pratt Pest Control
 * @author Kokil Thapa <thapa.kokil@gmail.com>
 * Heartland Computer LLC
 */

namespace App\Modules\Appointment\Repositories;

use DB;
use Exception;
use Carbon\Carbon;
use App\Helpers\Misc;
use App\Modules\Appointment\Models\AppointmentSetup;
use App\Modules\Appointment\Models\AppointmentMaster;
use App\Repositories\Repository;

class AppointmentSetupRepository extends Repository
{
    /**
     * The AppointmentMasterRepository implementation.
     *
     * @var AppointmentMasterRepository
     */
    protected $appointmentMaster;

    public function __construct(AppointmentSetup $model, AppointmentMasterRepository $appointmentMaster)
    {
        $this->model = $model;
        $this->appointmentMaster = $appointmentMaster;
    }

    public function createAppointments($inputs)
    {
        try {
            foreach ($inputs['applicable_days'] as $key => $applicable_day) {
                $inputs['day'] = $applicable_day;
                $inputs['effective_from'] = date("Y-m-d", strtotime($inputs['effective_from']));
                $inputs['effective_to'] = date("Y-m-d", strtotime($inputs['effective_to']));
                $inputs['created_by'] = \Auth::id();
                $inputs['updated_by'] = \Auth::id();
                $appointmentSetup = $this->model->create($inputs);
                $arrayOfTime = Misc::getTimeArray($appointmentSetup->start_time, $appointmentSetup->end_time, $appointmentSetup->time_slot);
                $arrayOfDate = Misc::getDateArray($appointmentSetup->effective_from, $appointmentSetup->effective_to, $appointmentSetup->day);
                $appointmentMasterData = [];
                if (count($arrayOfDate) > 0 && count($arrayOfTime) > 0) {
                    foreach ($arrayOfDate as $date) {
                        foreach ($arrayOfTime as $time) {
                            $appointmentMasterData[] = [
                                'appointment_setup_id' => $appointmentSetup->id,
                                'appointment_type_id' => $appointmentSetup->appointment_type_id,
                                'appointment_date' => $date,
                                'start_time' => $time['start_time'],
                                'end_time' => $time['end_time'],
                                'available_slot' => $appointmentSetup->available_slot,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                    $this->appointmentMaster->insert($appointmentMasterData);
                }
                $appointmentSetupData[] = $appointmentSetup;
            }
            DB::commit();
            return $appointmentSetupData;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function update($id, $inputs)
    {
        DB::beginTransaction();
        try {
            $appointmentSetup = AppointmentSetup::findOrFail($id);
            $appointmentSetup->fill($inputs)->save();
            $date = new Carbon($inputs['effective_from']);
            $date1 = new Carbon($inputs['effective_to']);
            $from = $date->format('Y-m-d') . " 00:00:00";
            $to = $date1->format('Y-m-d') . " 23:59:59";
            AppointmentMaster::where('appointment_setup_id', $appointmentSetup->id)
                ->whereBetween('appointment_date', [$from, $to])
                ->delete();

            $arrayOfTime = Misc::getTimeArray($appointmentSetup->start_time, $appointmentSetup->end_time, $appointmentSetup->time_slot);
            $arrayOfDate = Misc::getDateArray($appointmentSetup->effective_from, $appointmentSetup->effective_to, $appointmentSetup->day);
            $appointmentMasterData = [];
            if (count($arrayOfDate) > 0 && count($arrayOfTime) > 0) {
                foreach ($arrayOfDate as $date) {
                    foreach ($arrayOfTime as $time) {
                        $appointmentMasterData[] = [
                            'appointment_setup_id' => $appointmentSetup->id,
                            'appointment_type_id' => $appointmentSetup->appointment_type_id,
                            'appointment_date' => $date,
                            'start_time' => $time['start_time'],
                            'end_time' => $time['end_time'],
                            'available_slot' => $appointmentSetup->available_slot
                        ];
                    }
                }
                $this->appointmentMaster->insert($appointmentMasterData);
            }
            DB::commit();
            return $appointmentSetup;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }

    }
}
