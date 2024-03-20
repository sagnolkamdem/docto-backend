<?php

if (! function_exists('is_module_enabled')) {
    function is_module_enabled($module)
    {
        return array_key_exists($module, app('modules')->allEnabled());
    }
}

if (! function_exists('setEnvironmentValue')) {
    /**
     * Function to set or update .env variable.
     *
     * @param array $values
     * @return bool
     */
    function setEnvironmentValue(array $values): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            $str .= "\n"; // In case the searched variable is in the last line without \n
            foreach ($values as $envKey => $envValue) {
                if ($envValue === true) {
                    $value = 'true';
                } elseif ($envValue === false) {
                    $value = 'false';
                } else {
                    $value = $envValue;
                }

                $envKey = strtoupper($envKey);
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $space = strpos($value, ' ');
                $envValue = ($space === false) ? $value : '"' . $value . '"';

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }

                env($envKey, $envValue);
            }
        }

        $str = substr($str, 0, -1);

        if (! file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}
