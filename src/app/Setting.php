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

    /**
     * Get Organization Name
     * @return String
     */
    public static function getOrgName()
    {
        return self::where('setting', 'org_name')->first()->value;
    }

    /**
     * Set Organization Name
     * @param String $name
     */
    public static function setOrgName($name)
    {
        $setting = self::where('setting', 'org_name')->first();
        $setting->value = $name;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Organization Logo Path
     * @return String
     */
    public static function getOrgLogo()
    {
        return self::where('setting', 'org_logo')->first()->value;
    }

    /**
     * Set Organization Logo
     * @param Image $logo
     */
    public static function setOrgLogo($logo)
    {
        Storage::delete(self::getOrgLogo());
        $path = str_replace(
            'public/',
            '/storage/',
            Storage::putFileAs(
              'public/images/main',
              $logo,
              'logo_main.png'
            )
        );
        $setting = self::where('setting', 'org_logo')->first();
        $setting->value = $path;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Organization Favicon Path
     * @return String
     */
    public static function getOrgFavicon()
    {
        return self::where('setting', 'org_favicon')->first()->value;
    }

    /**
     * Set Organization Favicon
     * @param Image $favicon
     */
    public static function setOrgFavicon($favicon)
    {
        Storage::delete(self::getOrgFavicon());
        $path = str_replace(
            'public/',
            '/storage/',
            Storage::putFileAs(
              'public/images/main',
              $favicon,
              'favicon.ico'
            )
        );
        $setting = self::where('setting', 'org_favicon')->first();
        $setting->value = $path;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Terms And Conditions
     * @return String
     */
    public static function getPurchaseTermsAndConditions()
    {
        return self::where('setting', 'purchase_terms_and_conditions')->first()->value;
    }

    /**
     * Set Terms And Conditions
     * @param String $text
     */
    public static function setPurchaseTermsAndConditions($text)
    {
        $setting = self::where('setting', 'purchase_terms_and_conditions')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Registration Terms And Conditions
     * @return String
     */
    public static function getRegistrationTermsAndConditions()
    {
        return self::where('setting', 'registration_terms_and_conditions')->first()->value;
    }

    /**
     * Set Registration Terms And Conditions
     * @param String $text
     */
    public static function setRegistrationTermsAndConditions($text)
    {
        $setting = self::where('setting', 'registration_terms_and_conditions')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Discord Link
     * @return String
     */
    public static function getDiscordLink()
    {
        return self::where('setting', 'discord')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setDiscordLink($text)
    {
        $setting = self::where('setting', 'discord')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Facebook Link
     * @return String
     */
    public static function getFacebookLink()
    {
        return self::where('setting', 'facebook')->first()->value;
    }
    
    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setFacebookLink($text)
    {
        $setting = self::where('setting', 'facebook')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Steam Link
     * @return String
     */
    public static function getSteamLink()
    {
        return self::where('setting', 'steam')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setSteamLink($text)
    {
        $setting = self::where('setting', 'steam')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Reddit Link
     * @return String
     */
    public static function getRedditLink()
    {
        return self::where('setting', 'reddit')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setRedditLink($text)
    {
        $setting = self::where('setting', 'reddit')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Teamspeak Link
     * @return String
     */
    public static function getTeamspeakLink()
    {
        return self::where('setting', 'teamspeak')->first()->value;
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setTeamspeakLink($text)
    {
        $setting = self::where('setting', 'teamspeak')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Participant Count Offset
     * @return Integer
     */
    public static function getParticipantCountOffset()
    {
        return self::where('setting', 'participant_count_offset')->first()->value;
    }

    /**
     * Set Participant Count Offset
     * @param Integer $number
     */
    public static function setParticipantCountOffset($number)
    {
        $setting = self::where('setting', 'participant_count_offset')->first();
        $setting->value = $number;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Lan Count Offset
     * @return Integer
     */
    public static function getLanCountOffset()
    {
        return self::where('setting', 'lan_count_offset')->first()->value;
    }

    /**
     * Set Lan Count Offset
     * @param Integer $number
     */
    public static function setLanCountOffset($number)
    {
        $setting = self::where('setting', 'lan_count_offset')->first();
        $setting->value = $number;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Currency
     * @return String
     */
    public static function getCurrency()
    {
        return self::where('setting', 'currency')->first()->value;
    }

    /**
     * Set Currency
     * @param String $text
     */
    public static function setCurrency($currency)
    {
        $setting = self::where('setting', 'currency')->first();
        $setting->value = $currency;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Main
     * @return String
     */
    public static function getAboutMain()
    {
        return self::where('setting', 'about_main')->first()->value;
    }

    /**
     * Set About Main
     * @param String $text
     */
    public static function setAboutMain($text)
    {
        $setting = self::where('setting', 'about_main')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Short
     * @return String
     */
    public static function getAboutShort()
    {
        return self::where('setting', 'about_short')->first()->value;
    }

    /**
     * Set About Short
     * @param String $text
     */
    public static function setAboutShort($text)
    {
        $setting = self::where('setting', 'about_short')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Our Aim
     * @return String
     */
    public static function getAboutOurAim()
    {
        return self::where('setting', 'about_our_aim')->first()->value;
    }

    /**
     * Set About Our Aim
     * @param String $text
     */
    public static function setAboutOurAim($text)
    {
        $setting = self::where('setting', 'about_our_aim')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get About Who's Who
     * @return String
     */
    public static function getAboutWho()
    {
        return self::where('setting', 'about_who')->first()->value;
    }

    /**
     * Set About Who's Who
     * @param String $text
     */
    public static function setAboutWho($text)
    {
        $setting = self::where('setting', 'about_who')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }
}