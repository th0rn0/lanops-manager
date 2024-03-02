<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EventInformation extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_information';
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }
    
    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
}
