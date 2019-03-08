<?php

namespace App;

use App\NewsComment;
use App\NewsTag;

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

    public function tags()
    {
      return $this->hasMany('App\NewsTag', 'news_feed_id');
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

    /**
     * Store Comment
     * @param  String $text
     * @param  String $user_id
     * @return Boolean
     */
    public function storeComment($text, $user_id)
    {
        $news_comment = new NewsComment();
        $news_comment->comment = $text;
        $news_comment->news_feed_id = $this->id;
        $news_comment->user_id = $user_id;
        if (!$news_comment->save()) {
            return false;
        }
        return true;
    }

    /**
     * Store Tags
     * @param  Array $tags
     * @return Boolean
     */
    public function storeTags($tags)
    {
        $this->tags()->delete();
        $added_tags = array();
        foreach ($tags as $tag) {
            if (!in_array(trim($tag), $added_tags)) {
                $news_tag = new NewsTag();
                $news_tag->tag = trim($tag);
                $news_tag->news_feed_id = $this->id;
                if (!$news_tag->save()) {
                    return false;
                }
                array_push($added_tags, trim($tag));
            }
        }
        return true;
    }

    /**
     * Get Tags
     * @param  String $separator
     * @return Array
     */
    public function getTags($separator = ', ')
    {
        return implode($separator, $this->tags->pluck('tag')->toArray());
    }
}
