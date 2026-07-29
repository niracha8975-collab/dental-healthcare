<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Models\AuditLog;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {


        $request->validate([


            'login'=>'required|string',


            'password'=>'required|string'


        ]);



        $user = User::where(

            'email',

            $request->login

        )

        ->orWhere(

            'phone',

            $request->login

        )

        ->first();



        if(

            !$user ||

            !Hash::check(

                $request->password,

                $user->password

            )

        ){

            throw ValidationException::withMessages([


                'login'=>[

                    'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง'

                ]


            ]);

        }



        $token = $user->createToken(

            'DentalHealthcare'

        )->plainTextToken;



        AuditLog::createLog(

            'LOGIN',

            'AUTH',

            'User',

            $user->id

        );



        return response()->json([


            'success'=>true,


            'message'=>'เข้าสู่ระบบสำเร็จ',


            'data'=>[


                'user'=>$user,


                'token'=>$token


            ]


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Register Citizen
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {


        $request->validate([


            'name'=>'required|string',


            'phone'=>'required|unique:users',


            'password'=>'required|min:8'


        ]);



        $user = User::create([


            'name'=>$request->name,


            'phone'=>$request->phone,


            'password'=>$request->password,


            'status'=>'active'


        ]);



        $user->assignRole(

            'Citizen'

        );



        return response()->json([


            'success'=>true,


            'message'=>'สมัครสมาชิกสำเร็จ',


            'data'=>$user


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {


        $request->user()

            ->currentAccessToken()

            ->delete();



        AuditLog::createLog(

            'LOGOUT',

            'AUTH'

        );



        return response()->json([


            'success'=>true,


            'message'=>'ออกจากระบบสำเร็จ'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function profile(Request $request)
    {


        return response()->json([


            'success'=>true,


            'data'=>$request->user()


        ]);

    }


}