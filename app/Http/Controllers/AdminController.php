<?php

namespace App\Http\Controllers;

use App\Interfaces\AdminInterface;
use App\Http\Requests\AdminLoginRequest;

class AdminController extends Controller
{
    protected $adminInterface;

    public function __construct(AdminInterface $adminInterface)
    {
        $this->adminInterface = $adminInterface;
    }

    /**
     * Check if the login user is authenticated or not
     * @param Http\Requests\AdminLoginRequest
     * @author KaungHtetSan
     * @date 06/21/2023
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(AdminLoginRequest $request)
    {

        $infos = [
            ["id" => 1, "password" => "admin123123"],
            ["id" => 2, "password" => "admin456456"],
        ];

        foreach ($infos as $info) {

            if ($request->id == $info['id'] && $request->password == $info['password']) {

                // We create session table with migration and store the login id in session so we can use that id in anywhere

                $request->session()->put('id', $info['id']);

                return redirect()->route('employees.index')->with("loginMessage", "Welcome Kaung Good to see you again");
            }
        }

        return redirect()->back()->with('login-error', 'Credential errors. Please try again');
    }


    /**
     * Logout the auth user and return to login
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return \Illuminate\Http\RedirectResponse
     */

    public function logout()
    {
        session()->flush();
        return redirect()->route('admins.login');
    }
}
