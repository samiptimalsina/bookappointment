<?php

namespace App\Modules\Appointment\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Appointment\Repositories\AppointmentMasterRepository;
use App\Modules\Appointment\Repositories\AppointmentSetupRepository;


class DeleteUnwantedAppointmentCommand extends Command
{

    /**
     * The AppointmentMasterRepository implementation.
     *
     * @var AppointmentMasterRepository
     */
    protected $master;

    /**
     * The AppointmentSetupRepository implementation.
     *
     * @var AppointmentSetupRepository
     */
    protected $setup;


    /**
     * Create a new command instance.
     *
     * @param AppointmentMasterRepository $master
     * @param AppointmentSetupRepository $setup
     */
    public function __construct(AppointmentMasterRepository $master, AppointmentSetupRepository $setup)
    {
        parent::__construct();
        $this->master = $master;
        $this->setup = $setup;
    }


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unwanted-appointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to delete unwanted appointment Schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Started.');
//        $setups = $this->setup->model()->where('appointment_type_id', 5)->get();
//        foreach ($setups as $setup) {
//            $masters = $this->master->model()->where('appointment_type_id', 5)->where('appointment_setup_id', $setup->id)->get();
//            foreach ($masters as $master) {
//                $master->forceDelete();
//            }
//            $setup->forceDelete();
//        }
    }
}
