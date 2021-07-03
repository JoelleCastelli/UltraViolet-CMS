<?php

namespace App\Core;

use App\Models\Settings as SettingsModel;

class Settings {

    private static ?array $settingsArray;

     // Prevents the class from being called 'non-statically'
    private function __construct() {}

    // Stores all the settings in array
    public static function init() {
        $settings = new SettingsModel();
        $settings = $settings->findAll();
        foreach ($settings as $setting) {
            self::$settingsArray[$setting->getName()] = $setting;
        }
    }

    /**
     * Return all the settings in the database
     * @return array|null
     */
    public static function getSettings(): ?array
    {
        return self::$settingsArray;
    }

}