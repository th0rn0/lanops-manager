<?php

namespace App\Models;

use App\Models\EventSeating;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

class EventSeatingPlan extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_seating_plans';
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    protected $casts = [
        'disabled_seats' => 'array',
        'headers' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if (!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }
        self::creating(function ($model) {
            $model->headers = $model->formatRowHeaders();
        });
        self::created(function ($model) {
            $model->createSeats();
        });
        self::updating(function ($model) {
            if (
                array_key_exists('rows', $model->getDirty()) || 
                array_key_exists('columns', $model->getDirty())
            ) {
                $occupiedSeats = $model->seats()->where('event_participant_id', '!=', null)->get();
                $model->seats()->delete();
                $model->createSeats($occupiedSeats);
                $model->headers = $model->formatRowHeaders();
            }
        });
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
    public function seats()
    {
        return $this->hasMany('App\Models\EventSeating');
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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the Short Name for Lists.
     *
     * @return string
     */
    public function getShortName()
    {
        $name = $this->name;
        if ($this->name_short !== null) {
            $name = $this->name_short;
        }
        return $name;
    }

    /**
     * Get Seats for specific row.
     *
     * @return string
     */
    public function getSeatsForRow($row) {
        return $this->seats()->where('seat', 'REGEXP', "^[{$row}]\d+$")->get();
    }

    /**
     * Get Seats for specific column.
     *
     * @return App\Models\Seat
     */
    public function getSeatsForColumn($column) {
        return $this->seats()->where('seat', 'REGEXP', "^[A-Za-z]{1,9}{$column}$")->get();
    }
    
    public function createSeats($occupiedSeats = null) {
        for ($r = 0; $r < $this->rows; $r++) {
            for ($c = 0; $c < $this->columns; $c++) {
                $seat = new EventSeating();
                $seat->event_seating_plan_id = $this->id;
                $seat->seat = $this->formatRowHeaderByNumber($r + 1) . $c + 1;
                if ($occupiedSeats != null && $thisSeat = $occupiedSeats->where('seat', $seat->seat)->first()) {
                    $seat->event_participant_id = $thisSeat->event_participant_id;
                }
                $seat->save();
            }
        }
    }

    public function formatRowHeaders() {
        $headers = [];
        for ($r = 0; $r < $this->rows; $r++) {
            $headers[] = $this->formatRowHeaderByNumber($r + 1);
        }
        return $headers;
    }

    public function formatRowHeaderByNumber($num) {
        $column = '';
        while ($num > 0) {
            $num--; // Adjust for 0-based index
            $column = chr($num % 26 + 65) . $column;
            $num = floor($num / 26);
        }
        return $column;
    }

    public function getCapacity() {
        return $this->seats()->where('disabled', false)->get()->count();
    }

    public function getSeatedCount() {
        return $this->seats()->where('disabled', false)->where('event_participant_id', '!=', null)->get()->count();
    }

}
