<?php

if (!function_exists('is_module_enabled')) {

    function is_module_enabled($module) {
        return array_key_exists($module, app('modules')->enabled());
    }

}

if (!function_exists('setEnvironmentValue')) {

    function setEnvironmentValue($envKey, $envValue) {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = env("{$envKey}");

        if ($envKey=="MAIL_FROM_NAME") {
            $str = str_replace("{$envKey}='{$oldValue}'", "{$envKey}='{$envValue}'", $str);
        } else {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        }

        file_put_contents($envFile, $str);
    }

}
