<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginController extends Controller
{

    public function login($employee_id)
    {

        try {         
            
            if(request()->header('Cache-Control') === 'no-store, private, no-cache, must-revalidate') {
                // User has logged out, redirect to login or another page
                return redirect('/');
            }
            
            $decrypted = Crypt::decryptString($employee_id);
            $employee = Employees::where('id', $decrypted)->first();

            if ($employee) {
                // Encrypt the employee ID again
                $encryptedEmployeeId = Crypt::encryptString($employee->id);

                /* Auth::loginUsingId(1, $remember = true); */

                return redirect()->route('dashboard', $encryptedEmployeeId);
            } else {
                return redirect()->route('index')->with('error', 'Invalid Employee ID');
            }
        } catch (DecryptException $decrypted) {
            return redirect()->route('index')->with('error', 'Unknown QR Code');
        }
    }



    public function logout(Request $request)
    {
        /* Auth::logout(); */

        $request->session()->flush();

        return redirect('/')->header('Cache-Control', 'no-store, private, no-cache, must-revalidate');
    }

    

}
