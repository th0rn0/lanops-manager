<?php

namespace App;

use App\NewsComment;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class NewsArticle extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'news_feed';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
    );

    /*
     * Relationships
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function comments()
    {
      return $this->hasMany('App\NewsComment', 'news_feed_id');
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
                'source' => 'title'
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

    public function postComment($text, $user_id)
    {
        $comment = new NewsComment();
        $comment->comment = $text;
        $comment->news_feed_id = $this->id;
        $comment->user_id = $user_id;
        if (!$comment->save()) {
            return false;
        }
        return true;
    }
}
