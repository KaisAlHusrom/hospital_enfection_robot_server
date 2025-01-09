<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Settings\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SettingController extends ApiController
{

    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'test',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function getThemeSettings()
    {
        return Setting::getThemeSettings();
    }


    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'value' => 'required',
            'locale' => 'string'
        ]);

        $locale = $validated['locale'] ?? Setting::$defaultSettingsLocale;
        $values = $validated['value'];

        // Process values
        if (!is_string($values)) {
            $values = json_encode($values);
        }

        // Create or update setting
        $setting = Setting::updateOrCreate(
            ['name' => $validated['name']]
        );

        // Handle translation using translateOrNew
        $translation = $setting->translateOrNew(mb_strtolower($locale));
        $translation->value = $values;
        $setting->save();

        // Clear relevant caches
        cache()->forget('settings.' . $validated['name']);
        if ($validated['name'] == 'general') {
            cache()->forget('settings.getDefaultLocale');
        }

        return $this->createdResponse([
            'setting' => $setting,
            'translation' => $translation,
            'locale' => $locale
        ]);
    }
}
