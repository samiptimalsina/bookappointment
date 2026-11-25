<?php

/*
 * @project Pratt Pest Control
 * @author Kokil Thapa <thapa.kokil@gmail.com>
 * Pratt Pest Control
 */

namespace App\Modules\Appointment\Repositories;

use App\Modules\Appointment\Models\AppointmentMaster;
use App\Repositories\Repository;

class AppointmentMasterRepository extends Repository
{
    public function __construct(AppointmentMaster $model)
    {
        $this->model = $model;
    }
}
