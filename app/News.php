<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
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
        'updated_at'
    );

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
}
