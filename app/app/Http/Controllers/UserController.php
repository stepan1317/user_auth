<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * @var int
     */
    private $user_key;

    /**
     * @var login
     */
    private $login;

    /**
     * @var pass
     */
    private $pass;


    /**
     * UserController constructor.
     *
     * @param $login
     * @param $pass
     */
    function __construct($login, $pass)
    {
        $this->login = trim($login);
        $this->pass = md5($pass);
        $this->user_key = 853459834;
    }

    /**
     * @return bool|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function login()
    {
        if (self::accessCheck()) {
            if (FloodAuthController::checkAuthFlood($this->login)) {
                if (self::checkLoginAccess()) {
                    if (session('key') == $this->user_key) {
                        session()->flash('flood');
                        return session('id');
                    }
                }
            }
        } else {
            if (FloodAuthController::checkAuthFlood($this->login)) {
                $path = Storage::disk('local')
                  ->path(FloodAuthController::$floodFile);
                $fp = fopen($path, 'a+');
                $fields = [$this->login, $this->pass, time()];
                $string = implode(",", $fields);
                fputs($fp, PHP_EOL.$string);
                fclose($fp);
                return false;
            }
        }
    }

    /**
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function checkLoginAccess()
    {
        $users = FloodAuthController::loadDataFromFile('users.csv');
        foreach ($users as $user) {
            if ($user[1] == $this->login && $user[2] == $this->pass) {
                session(['key' => $this->user_key]);
                session(['id' => $user[0]]);
                session(['name' => $user['1']]);
                return true;
            }
        }
    }

    /**
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function accessCheck()
    {
        $users = FloodAuthController::loadDataFromFile('users.csv');
        $flag = false;
        foreach ($users as $user) {
            if ($user[1] == $this->login && $user[2] == $this->pass) {
                $flag = true;
            }
        }
        return $flag;
    }
}
