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
            $headers = [];
            for ($r = 1; $r < $model->rows; $r++) {
                // $column = '';
                // while ($num > 0) {
                //     $num--; // Adjust for 0-based index
                //     $column = chr($num % 26 + 65) . $column;
                //     $num = floor($num / 26);
                // }
                $headers[] = $model->numberToExcelColumn($r);
            }
            $model->headers = $headers;
        });
        self::created(function ($model) {
            // $alphabet = range('A', 'Z');
            // for ($i = 0; $i < $request->columns; $i++) {
            //     $seatingHeaders[] = $alphabet[$i];
            // }
            // $seatingPlan->headers  = implode(',', $seatingHeaders);

            for ($r = 1; $r < $model->rows; $r++) {
                for ($c = 1; $c < $model->columns; $c++) {
                    $seat = new EventSeating();
                    $seat->event_seating_plan_id = $model->id;
                    $seat->seat = $model->numberToExcelColumn($r) . $c;
                    $seat->save();
                }
            }
        });
        self::updating(function ($model) {
            // if (array_key_exists('rows', $model->getDirty()) || 
            //     array_key_exists('columns', $model->getDirty())) {
            //         dd('asdasd');
            // }


        });
    }

    public function numberToExcelColumn($num) {
        $column = '';
        while ($num > 0) {
            $num--; // Adjust for 0-based index
            $column = chr($num % 26 + 65) . $column;
            $num = floor($num / 26);
        }
        return $column;
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
        return $this->seats()->where('seat', 'LIKE', $row)->get();
    }
}
