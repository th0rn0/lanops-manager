<?php

namespace App;

use DB;
use Auth;
use App\PollOption;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;


class Poll extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'polls';
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    protected static function boot()
    {
        parent::boot();

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if(!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPreview', function (Builder $builder) {
                $builder->where('status', '!=', 'PREVIEW');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }
    }

    /*
     * Relationships
     */    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function options()
    {
        return $this->hasMany('App\PollOption', 'poll_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
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
     * Add Poll Option to the Database.
     *
     * @return string
     */
    public function addOption($option)
    {
        if (!empty(trim($option))) {
            $poll_option = new PollOption();
            $poll_option->name = trim($option);
            $poll_option->user_id = Auth::id();
            $poll_option->poll_id = $this->id;
            if (!$poll_option->save()) {
                return false;
            }
        }
        return true;
    }
    
    public function getTotalVotes()
    {
        $total = 0;
        foreach ($this->options as $option) {
            $total += $option->getTotalVotes();
        }
        return $total;
    }
}
