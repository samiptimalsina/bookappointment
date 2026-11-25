<?php

namespace App\Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Notifications\Notifiable;

class Appointment extends Model
{
    use HasFactory;

    use SoftDeletes;

    use RevisionableTrait;

    use Notifiable;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public $sortable = [
        'order_column_name' => 'id',
        'sort_when_creating' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_master_id', 'appointment_setup_id', 'appointment_date', 'start_time', 'end_time', 'first_name', 'last_name', 'email', 'phone', 'city', 'state', 'zip', 'address', 'message', 'is_active', 'assign_to', 'is_confirmed', 'is_completed', 'unique_id', 'is_notified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function appointmentMaster()
    {
        return $this->belongsTo(AppointmentMaster::class, 'appointment_master_id', 'id');
    }

    public function appointmentSetup()
    {
        return $this->belongsTo(AppointmentSetup::class, 'appointment_setup_id', 'id');
    }

    public function smsable()
    {
        return $this->morphMany('App\Models\SmsLog', 'smsable');
    }

    public function agentable()
    {
        return $this->morphOne('App\Models\Agent', 'agentable');
    }

    /**
     * Get the zipcode that owns the user.
     */
    public function zipCode()
    {
        return $this->belongsTo('App\Models\ZipCode', 'zip');
    }
}
