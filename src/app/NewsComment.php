<?php

namespace App;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NewsComment extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'news_feed_comments';

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
            static::addGlobalScope('approvedTrue', function (Builder $builder) {
                $builder->where('approved', true);
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
    public function newsArticle()
    {
      return $this->belongsTo('App\NewsArticle', 'news_feed_id');
    }

}
