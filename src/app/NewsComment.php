<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    );

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
