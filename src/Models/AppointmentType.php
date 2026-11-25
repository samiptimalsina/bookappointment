<?php

namespace App\Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Venturecraft\Revisionable\RevisionableTrait;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;

class AppointmentType extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use RevisionableTrait;
    use SortableTrait;
    use InteractsWithMedia;

    public $sortable = [
        'order_column_name' => 'display_order',
        'sort_when_creating' => true,
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('display_order', 'asc')->orderBy('id', 'DESC');
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'display_order', 'description', 'is_active', 'created_by', 'updated_by'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')->sharpen(10)->crop('crop-center', 100, 100)->format('webp')->quality(80);
        $this->addMediaConversion('big')->sharpen(10)->crop('crop-center', 200, 200)->format('webp')->quality(80);

    }
}
