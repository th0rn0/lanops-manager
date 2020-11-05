<?php

namespace App;

use Cache;

use Illuminate\Database\Eloquent\Model;

use ScssPhp\ScssPhp\Compiler;
use Illuminate\Support\Facades\Storage;

class Appearance extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'appearance';

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
     * Recompile CSS
     * @return Boolean
     */
    public static function cssRecompile()
    {
        @Cache::forget("css_version");
        $scss = new Compiler();
        $scss->setImportPaths('/web/html/resources/assets/sass/');
        // required for node_moudles imports
        $scss->addImportPath('/web/html/');
        $scss->setSourceMap(Compiler::SOURCE_MAP_FILE);
        $cssTemplates = ['app', 'admin'];
        foreach ($cssTemplates as $cssTemplate) {
            $scss->setSourceMapOptions(array(
                'sourceMapWriteTo'  => config('filesystems.disks.compiled-css.root') .
                    '/' .
                    str_replace("/", "_", $cssTemplate) .
                    ".css.map"
                ,
                'sourceMapFilename' => $cssTemplate . '.css',
                'sourceMapBasepath' => config('filesystems.disks.compiled-css.root'),
                'sourceRoot'        => '/',
            ));
            @Storage::disk('compiled-css')->delete($cssTemplate . '.css');
            @Storage::disk('compiled-css')->delete($cssTemplate . '.css.map');
            if (!Storage::disk('compiled-css')
                ->put($cssTemplate . '.css', $scss->compile('@import "' . $cssTemplate . '.scss";'))
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get CSS Overrides
     * @return Boolean|Storage
     */
    public static function getCssOverride()
    {
        if (!Storage::disk('assets')->exists('sass/stylesheets/app/components/_user-override.scss')) {
            return false;
        }
        return Storage::disk('assets')->get('sass/stylesheets/app/components/_user-override.scss');
    }

    /**
     * Save CSS Overrides
     * @param String $css
     * @return Boolean
     */
    public static function saveCssOverride($css)
    {
        @Cache::forget("css_version");
        if (!Storage::disk('assets')->put('sass/stylesheets/app/components/_user-override.scss', $css)) {
            return false;
        }
        return true;
    }

    /**
     * Get CSS Variable
     * @return Var
     */
    public static function getCssVariables()
    {
        return self::where('type', 'CSS_VAR')->get();
    }

    /**
     * Get CSS Variable
     * @return Var
     */
    public static function getCssVersion()
    {
        return Cache::get("css_version", function () {
            $int = random_int(1, 999);
            Cache::forever("css_version", $int);
            return $int;
        });
    }

    /**
     * Save CSS Variable
     * @return Var
     */
    public static function saveCssVariables($variables)
    {
        @Cache::forget("css_version");
        @Storage::disk('assets')->delete('sass/stylesheets/app/modules/_user-variables.scss');
        Storage::disk('assets')->put('sass/stylesheets/app/modules/_user-variables.scss', '// User Variable Overrides');
        foreach ($variables as $key => $value) {
            if (!self::saveCssVariableToDatabase($key, $value)) {
                return false;
            }
            if (!self::saveCssVariableToFile($key, $value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Save CSS Variable to Database
     * @return Var
     */
    public static function saveCssVariableToDatabase($key, $value)
    {
        @Cache::forget("css_version");
        $variable = self::where('key', $key)->first();
        $variable->value = $value;
        if (!$variable->save()) {
            return false;
        }
        return true;
    }

    /**
     * Save CSS Variable to File
     * @return Var
     */
    public static function saveCssVariableToFile($key, $value)
    {
        @Cache::forget("css_version");
        if (!Storage::disk('assets')
            ->append('sass/stylesheets/app/modules/_user-variables.scss', '$' . $key . ': ' . $value . ';')
        ) {
            return false;
        }
        return true;
    }
}
