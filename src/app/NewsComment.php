<?php

namespace App;

use Auth;
use App\NewsCommentReport;

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
    public function reports()
    {
      return $this->hasMany('App\NewsCommentReport', 'news_feed_comment_id');
    }


    /**
     * Set Comment as Reported
     * @return Boolean
     */
    public function report()
    {
        $report = [
            'news_feed_comment_id'      => $this->id,
            'user_id'                   => Auth::id(),
        ];
        if (!NewsCommentReport::create($report)) {
            return false;
        }
        return true;
    }

    /** 
     * Set Comment as Reviewed
     * @param  Boolean
     * @return Boolean
     */
    public function review($boolean)
    {
        $this->reviewed = $boolean;
        $this->reviewed_by = Auth::id();
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /** 
     * Set Comment as Approved
     * @param  Boolean
     * @return Boolean
     */
    public function approve($boolean)
    {
        $this->approved = $boolean;
        $this->approved_by = Auth::id();
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /** 
     * Check if any Reports exist
     * @param  Boolean
     * @return Boolean
     */
    public function hasReports()
    {
        if ($this->reports->isEmpty()) {
            return false;
        }
        return true;
    }
}
