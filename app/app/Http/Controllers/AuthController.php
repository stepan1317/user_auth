<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        if (session()->exists('id') && session()->exists('name')) {
            return redirect()->route('userPage', ['id' => session('id')]);
        } else {
            return view('auth.index');
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function userPage(Request $request)
    {
        if (session()->exists('id') && session()->exists('name') && $request->segment(2) == session('id')) {
            return view('auth.user')->with(['name' => session('name')]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function authForm(Request $request)
    {
        $user = new UserController($request->post('login'),
          $request->post('pass'));
        if ($user->login()) {
            return redirect()->route('userPage', ['id' => session('id')]);
        } else {
            if (\session()->exists('flood')) {
                if (session('flood') < 300) {

                    return redirect()
                      ->back()
                      ->with('message',
                        'You are blocked, please try again after '.(300 - session('flood')).' second');
                }
            } else {
                return redirect()->back()->with('message', 'Wrong data!');
            }
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('show');
    }
}
