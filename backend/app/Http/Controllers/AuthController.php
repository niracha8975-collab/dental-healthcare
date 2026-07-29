<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;

use App\Models\User;

use App\Models\Patient;

use App\Models\AuditLog;

use App\Services\OtpService;



class AuthController extends Controller
{


    protected OtpService $otp;


    public function __construct(

        OtpService $otp

    )

    {

        $this->otp = $otp;

    }





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

        )

        ->first();





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

            !$user->active

        )

        {


            return response()->json([


                'message'=>'บัญชีถูกระงับ'


            ],403);


        }





        $token = $user->createToken(

            'dental-healthcare'

        )->plainTextToken;





        AuditLog::record(

            'login',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'เข้าสู่ระบบสำเร็จ',


            'token'=>$token,


            'user'=>$user->load('roles')


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





        $patient = Patient::where(

            'cid',

            $request->cid

        )

        ->where(

            'birth_date',

            $request->birth_date

        )

        ->first();





        if(!$patient)

        {


            return response()->json([


                'message'=>'ไม่พบข้อมูลผู้รับบริการ'


            ],404);


        }





        $token = $patient->createToken(

            'citizen-app'

        )->plainTextToken;





        return response()->json([


            'message'=>'เข้าสู่ระบบสำเร็จ',


            'token'=>$token,


            'patient'=>$patient


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | OTP Request
    |--------------------------------------------------------------------------
    */


    public function requestOTP(

        Request $request

    )

    {


        $request->validate([


            'phone'=>

                'required'


        ]);





        $otp = $this->otp->send(

            $request->phone

        );





        return response()->json([


            'message'=>'ส่ง OTP สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | OTP Verify
    |--------------------------------------------------------------------------
    */


    public function verifyOTP(

        Request $request

    )

    {


        $request->validate([


            'phone'=>

                'required',


            'otp'=>

                'required'


        ]);





        if(

            !$this->otp->verify(

                $request->phone,

                $request->otp

            )

        )

        {


            return response()->json([


                'message'=>'OTP ไม่ถูกต้อง'


            ],401);


        }





        return response()->json([


            'message'=>'ยืนยัน OTP สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */


    public function me(

        Request $request

    )

    {


        return response()->json([


            'data'=>

                $request->user()


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


        $request

            ->user()

            ->currentAccessToken()

            ->delete();





        return response()->json([


            'message'=>'ออกจากระบบสำเร็จ'


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
    | Refresh Token
    |--------------------------------------------------------------------------
    */


    public function refresh(

        Request $request

    )

    {


        $token = $request

            ->user()

            ->createToken(

                'refresh'

            )

            ->plainTextToken;





        return response()->json([


            'token'=>$token


        ]);


    }


}