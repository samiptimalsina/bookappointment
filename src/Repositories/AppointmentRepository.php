<?php

/*
 * @project Pratt Pest Control
 * @author Kokil Thapa <thapa.kokil@gmail.com>
 * Pratt Pest Control
 */

namespace App\Modules\Appointment\Repositories;

use App\Modules\Appointment\Models\Appointment;
use App\Repositories\Repository;

class AppointmentRepository extends Repository
{
    public function __construct(Appointment $model)
    {
        $this->model = $model;
    }
}
