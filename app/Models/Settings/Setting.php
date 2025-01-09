<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Setting extends BaseModel implements TranslatableContract
{
    use Translatable;

    protected $table = 'settings';

    public array $translatedAttributes = ['value'];
    protected $translationForeignKey = 'setting_id';

    static string $defaultSettingsLocale = 'en'; // Because the settings table uses translation and some settings do not need to be translated, so we save them with a default locale


    // The result is stored in these variables
    // If you use each function more than once per page, the database will be requested only once.
    static $themeValue;

    // settings name , Using these keys, values are taken from the settings table
    static string $themeKey = 'theme';

    static function getSetting(&$static, $name, $key = null)
    {
        if (!isset($static)) {
            $static = cache()->remember('settings.' . $name, 24 * 60 * 60, function () use ($name) {
                return self::where('name', $name)->first();
            });
        }

        $value = [];

        if (!empty($static) and !empty($static->value) and isset($static->value)) {
            $value = json_decode($static->value, true);
        }

        if (!empty($value) and !empty($key)) {
            return $value[$key] ?? null;
        }

        if (!empty($key) and (empty($value) or count($value) < 1)) {
            return '';
        }

        return $value;
    }

    static function getThemeSettings()
    {

        return self::getSetting(self::$themeValue, self::$themeKey);
    }
}
