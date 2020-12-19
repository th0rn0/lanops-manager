<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpCategoryEntryAttachment extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'help_category_entry_attachments';

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
    public function entry()
    {
        return $this->belongsTo('App\HelpCategoryEntry');
    }

}
