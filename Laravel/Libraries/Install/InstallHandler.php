<?php

namespace App\Libraries\Install;

use App\Libraries\Settings\Env\EnvHandler;

class InstallHandler
{
    public const FILE_NAME = 'InstallationCompleted';
    public const ARRAY_INSTALL_ROUTES = [
        0 => '/install/welcome',
        1 => '/install/first',
        2 => '/install/second',
        3 => '/install/third',
        4 => '/install/fourth',
        5 => '/install/fifth',
        6 => '/install/finish',
    ];

    /**
     * Returns the path to the installation trigger file
     *
     * @return string
     * */
    public static function returnPath()
    {
        return base_path() . '/storage/' . self::FILE_NAME . '.json';
    }

    /**
     * Returns data from an installation trigger file
     *
     * @return array|null
     * */
    public static function returnDataInstallations()
    {
        $path = static::returnPath();

        if (! file_exists($path)) {
            return null;
        }

        return json_decode(
            file_get_contents($path),
            true
        );
    }

    /**
     * Checks if the installation has been completed
     *
     * @return bool
     * */
    public static function checkInstallation()
    {
        $data = static::returnDataInstallations();

        if (isset($data['setup']) && $data['setup'] == 'done') {
            return true;
        }

        return false;
    }

    /**
     * Creates an JSON file indicating that the installation has been completed
     *
     * @param array $sections_info
     * @param int|null $step
     *
     * @return int.
     * */
    public static function addFileTregger($sections_info, $step = null)
    {
        $path = static::returnPath();

        $existed_env_content = EnvHandler::getEnvFileContent();

        foreach ($sections_info as $section_info) {
            if ($section_info['desc'] == 'Application info') {
                $applicationInfo = $section_info;
            }

            if ($section_info['desc'] == 'Database settings') {
                $databaseSettings = $section_info;
            }

            if ($section_info['desc'] === 'Drivers settings') {
                $driversSettings = $section_info;
            }

            if ($section_info['desc'] == 'Redis settings') {
                $redisSettings = $section_info;
            }

            if ($section_info['desc'] == 'Drivers mailer settings') {
                $driversMailSettings = $section_info;
            }
        }

        $data = [
            'setup' => 'done',
            'step' => $step,
            'env' => [
                'Application info' => $applicationInfo,
                'Database settings' => $databaseSettings,
                'Drivers settings' => $driversSettings,
                'Redis settings' => $redisSettings,
                'Drivers Mail settings' => $driversMailSettings,
            ]
        ];

        $data['values'] = [];

        if (isset($step)) {
            foreach ($data['env'] as $items_env) {
                foreach ($items_env['content'] as $item_env) {
                    $data['values'][$item_env['variable']] = null;
                }
            }

            $arr_key_value_in_data_old = $data['values'];

            foreach ($arr_key_value_in_data_old as $key => $value) {
                $app_key_pair = EnvHandler::returnKeyAndValueFromDotEnvContent($existed_env_content, $key);
                $arr_key_value_in_data_old[$app_key_pair['key']] = $app_key_pair['value'];
            }

            $data['values'] = cleanFromEmptyArray($arr_key_value_in_data_old);
        }

        file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );

        return 0;
    }
}