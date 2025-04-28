<?php

/**
 * Get a setting value by category and key
 *
 * @param string $category The setting category
 * @param string $key The setting key
 * @param mixed $default Default value if setting not found
 * @return mixed
 */
function get_setting($category, $key = null, $default = null)
{
    $settingModel = new \App\Models\SettingModel();
    $setting = $settingModel->getByCategoryAndKey($category, $key);
    if (!$setting) {
        return $default;
    }

    
    // Convert value according to its type
    switch ($setting['value_type']) {
        case 'number':
            return is_numeric($setting['value']) ? (float) $setting['value'] : $default;
        case 'boolean':
            return filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
        case 'json':
            $json = json_decode($setting['value'], true);
            return ($json === null && json_last_error() !== JSON_ERROR_NONE) ? $default : $json;
        default:
            return $setting['value'];
    }
}

/**
 * Get all settings by category
 *
 * @param string $category The setting category
 * @return array
 */
function get_settings_by_category($category)
{
    $settingModel = new \App\Models\SettingModel();
    return $settingModel->getAllByCategory($category);
}

/**
 * Get all public settings
 *
 * @return array
 */
function get_public_settings()
{
    $settingModel = new \App\Models\SettingModel();
    return $settingModel->getAllPublic();
}

/**
 * Set a setting value
 *
 * @param string $category The setting category
 * @param string $key The setting key
 * @param mixed $value The setting value
 * @param int|null $userId The user ID updating the setting
 * @return bool
 */
function set_setting($category, $key, $value, $userId = null)
{
    $settingModel = new \App\Models\SettingModel();
    return $settingModel->setSetting($category, $key, $value, $userId);
} 