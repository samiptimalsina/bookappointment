<?php

/*
 * @project Pratt Pest Control
 * @author Kokil Thapa <thapa.kokil@gmail.com>
 * Pratt Pest Control
 */

namespace App\Modules\Appointment\Repositories;

use App\Modules\Appointment\Models\AppointmentType;
use App\Repositories\Repository;

class AppointmentTypeRepository extends Repository
{
    public function __construct(AppointmentType $model)
    {
        $this->model = $model;
    }

    public function lists()
    {
        return $this->model->pluck('name', 'id');
    }
}
