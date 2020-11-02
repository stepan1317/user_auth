<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FloodAuthController extends Controller
{

    public static $floodFile = 'flood_users.csv';

    /**
     * @param  \App\Http\Controllers\string  $login
     *
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function checkAuthFlood(string $login)
    {
        $csvData = self::loadDataFromFile(self::$floodFile);

        $last_authorization = [];
        foreach ($csvData as $user) {
            if (array_search($login, $user) !== false) {
                $last_authorization[] = $user;
            }
        }

        $floodCount = count($last_authorization);
        if ($floodCount < 3) {
            return true;
        } else {
            $time = time() - $last_authorization[$floodCount - 1][2];
            if ($time > 300) {
                self::clearFloodItems($login);
                return true;
            } else {
                session(['flood' => $time]);
            }
            return false;
        }
    }

    /**
     * @param  \App\Http\Controllers\string  $path
     *
     * @return array|bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function loadDataFromFile(string $path)
    {
        if (Storage::disk('local')->exists($path)) {
            $csvData = Storage::disk('local')->get($path);
            $lines = explode(PHP_EOL, $csvData);
            $array = [];
            foreach ($lines as $line) {
                $array[] = str_getcsv($line);
            }
            return $array;
        } else {
            Storage::put($path, '');
        }
        return false;
    }

    /**
     * @param  \App\Http\Controllers\string  $login
     */
    public static function clearFloodItems(string $login)
    {
        $path = Storage::disk('local')
          ->path(FloodAuthController::$floodFile);
        $file = file($path);
        $fp = fopen($path, "w");
        for ($i = 0; $i < sizeof($file); $i++) {
            $arr = explode(',', $file[$i]);
            if ($arr[0] == $login) {
                unset($file[$i]);
            }
        }
        fputs($fp, implode("", $file));
        fclose($fp);
    }
}
