<?php

namespace App;

use DB;
use Storage;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting', 
        'value',
        'default',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    public static function getOrgName()
    {
        return self::where('setting', 'org_name')->first()->value;
    }

    public static function setOrgName($name)
    {
        $setting = self::where('setting', 'org_name')->first();
        $setting->value = $name;
        $setting->save();
        return $setting;
    }

    public static function getOrgLogo()
    {
        return self::where('setting', 'org_logo')->first()->value;
    }

    public static function setOrgLogo($logo)
    {
        Storage::delete(self::getOrgLogo());
      
        $path = str_replace(
            'public/',
            '/storage/',
            Storage::put(
              'public/images/main',
              $logo
            )
        );

        $setting = self::where('setting', 'org_logo')->first();
        $setting->value = $path;
        $setting->save();
        return $setting;
    }

    public static function getTermsAndConditions()
    {
        return self::where('setting', 'terms_and_conditions')->first()->value;
    }

    public static function setTermsAndConditions($text)
    {
        $setting = self::where('setting', 'terms_and_conditions')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getDiscordLink()
    {
        return self::where('setting', 'discord')->first()->value;
    }

    public static function setDiscordLink($text)
    {
        $setting = self::where('setting', 'discord')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getFacebookLink()
    {
        return self::where('setting', 'facebook')->first()->value;
    }

    public static function setFacebookLink($text)
    {
        $setting = self::where('setting', 'facebook')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getSteamLink()
    {
        return self::where('setting', 'steam')->first()->value;
    }

    public static function setSteamLink($text)
    {
        $setting = self::where('setting', 'steam')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getRedditLink()
    {
        return self::where('setting', 'reddit')->first()->value;
    }

    public static function setRedditLink($text)
    {
        $setting = self::where('setting', 'reddit')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getTeamspeakLink()
    {
        return self::where('setting', 'teamspeak')->first()->value;
    }

    public static function setTeamspeakLink($text)
    {
        $setting = self::where('setting', 'teamspeak')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getParticipantCountOffset()
    {
        return self::where('setting', 'participant_count_offset')->first()->value;
    }

    public static function setParticipantCountOffset($number)
    {
        $setting = self::where('setting', 'participant_count_offset')->first();
        $setting->value = $number;
        $setting->save();
        return $setting;
    }

    public static function getLanCountOffset()
    {
        return self::where('setting', 'lan_count_offset')->first()->value;
    }

    public static function setLanCountOffset($number)
    {
        $setting = self::where('setting', 'lan_count_offset')->first();
        $setting->value = $number;
        $setting->save();
        return $setting;
    }

    public static function getCurrency()
    {
        return self::where('setting', 'currency')->first()->value;
    }

    public static function setCurrency($currency)
    {
        $setting = self::where('setting', 'currency')->first();
        $setting->value = $currency;
        $setting->save();
        return $setting;
    }
    
    public static function getAboutMain()
    {
        return self::where('setting', 'about_main')->first()->value;
    }

    public static function setAboutMain($text)
    {
        $setting = self::where('setting', 'about_main')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getAboutShort()
    {
        return self::where('setting', 'about_short')->first()->value;
    }

    public static function setAboutShort($text)
    {
        $setting = self::where('setting', 'about_short')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getAboutOurAim()
    {
        return self::where('setting', 'about_our_aim')->first()->value;
    }

    public static function setAboutOurAim($text)
    {
        $setting = self::where('setting', 'about_our_aim')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }

    public static function getAboutWho()
    {
        return self::where('setting', 'about_who')->first()->value;
    }

    public static function setAboutWho($text)
    {
        $setting = self::where('setting', 'about_who')->first();
        $setting->value = $text;
        $setting->save();
        return $setting;
    }
}