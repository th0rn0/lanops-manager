<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpCategoryEntry extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'help_category_entrys';

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
    public function album()
    {
        return $this->belongsTo('App\HelpCategory');
    }
}
