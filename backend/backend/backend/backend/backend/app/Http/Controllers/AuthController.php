<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;

use App\Models\User;

use App\Models\Patient;

use App\Models\LoginHistory;

use App\Models\AuditLog;



class AuthController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Admin / Staff Login
    |--------------------------------------------------------------------------
    */


    public function login(

        Request $request

    )

    {


        $request->validate([


            'email'=>

                'required|email',


            'password'=>

                'required'


        ]);





        $user = User::where(

            'email',

            $request->email

        )->first();





        if(

            !$user ||

            !Hash::check(

                $request->password,

                $user->password

            )

        )

        {


            return response()->json([


                'message'=>'Email หรือ Password ไม่ถูกต้อง'


            ],401);


        }





        if(

            $user->status != 'active'

        )

        {


            return response()->json([


                'message'=>'บัญชีถูกปิดใช้งาน'


            ],403);


        }





        $token = $user->createToken(

            'DentalHealthcare'

        )->plainTextToken;





        LoginHistory::create([


            'user_id'=>

                $user->id,


            'ip_address'=>

                $request->ip(),


            'login_at'=>

                now()


        ]);





        AuditLog::record(

            'login',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'เข้าสู่ระบบสำเร็จ',


            'token'=>$token,


            'user'=>$user->load(

                'roles'

            )


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Citizen Login
    |--------------------------------------------------------------------------
    */


    public function citizenLogin(

        Request $request

    )

    {


        $request->validate([


            'cid'=>

                'required|digits:13',


            'birth_date'=>

                'required|date'


        ]);





        $patient = Patient::where([


            'cid'=>

                $request->cid,


            'birth_date'=>

                $request->birth_date


        ])

        ->first();





        if(!$patient)

        {


            return response()->json([


                'message'=>

                    'ไม่พบข้อมูลผู้รับบริการ'


            ],401);


        }





        $token = $patient->createToken(

            'CitizenApp'

        )->plainTextToken;





        return response()->json([


            'message'=>

                'เข้าสู่ระบบสำเร็จ',


            'token'=>$token,


            'patient'=>$patient


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Refresh Token
    |--------------------------------------------------------------------------
    */


    public function refresh()

    {


        $user = auth()->user();





        $token = $user->createToken(

            'DentalHealthcare'

        )->plainTextToken;





        return response()->json([


            'token'=>$token


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */


    public function logout(

        Request $request

    )

    {


        $request->user()

            ->currentAccessToken()

            ->delete();





        return response()->json([


            'message'=>

                'ออกจากระบบสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */


    public function profile()

    {


        return response()->json([


            'data'=>

                auth()->user()


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */


    public function forgotPassword(

        Request $request

    )

    {


        $request->validate([


            'email'=>

                'required|email'


        ]);





        Password::sendResetLink(

            $request->only(

                'email'

            )

        );





        return response()->json([


            'message'=>

                'ส่งลิงก์ Reset Password แล้ว'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
    */


    public function resetPassword(

        Request $request

    )

    {


        $request->validate([


            'token'=>

                'required',


            'email'=>

                'required|email',


            'password'=>

                'required|min:8'


        ]);





        return response()->json([


            'message'=>

                'เปลี่ยน Password สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Check Permission
    |--------------------------------------------------------------------------
    */


    public function permission()

    {


        return response()->json([


            'roles'=>

                auth()

                ->user()

                ->roles,


            'permissions'=>

                auth()

                ->user()

                ->permissions


        ]);


    }


}