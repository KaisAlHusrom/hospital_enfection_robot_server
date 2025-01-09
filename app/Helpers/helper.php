<?php

// Manually include all helper files
foreach (glob(__DIR__ . '/*.php') as $filename) {
    if ($filename !== __FILE__) {
        require_once $filename;
    }
}

function getThemeSettings()
{
    return \App\Models\Settings\Setting::getThemeSettings();
}

/**
 * Returns the id type of all schemas
 */
function getIdType()
{
    return \App\Models\BaseModel::getIdType();
}
