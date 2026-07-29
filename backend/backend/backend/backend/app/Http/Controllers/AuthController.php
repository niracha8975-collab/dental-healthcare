<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Models\Patient;

use App\Models\AuditLog;



class AuthController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */


    public function login(

        Request $request

    )

    {


        $request->validate([


            'login'=>'required',


            'password'=>'required'


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

        )

        {


            return response()->json([


                'message'=>'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง'


            ],401);


        }





        if(

            $user->status !== 'active'

        )

        {


            return response()->json([


                'message'=>'บัญชีถูกระงับ'


            ],403);


        }





        $token = $user->createToken(

            'DentalHealthcare'

        )->plainTextToken;





        AuditLog::record(

            'login',

            'User',

            $user->id

        );





        return response()->json([


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


            'cid'=>'required|digits:13',


            'birth_date'=>'required|date'


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


                'message'=>'ไม่พบข้อมูลประชาชน'


            ],404);


        }





        $user = $patient->user;





        if(!$user)

        {


            $user = User::create([


                'name'=>

                    $patient->first_name.' '.

                    $patient->last_name,


                'phone'=>$patient->phone,


                'password'=>

                    Hash::make(

                        $request->cid

                    ),


                'status'=>'active'


            ]);





            $user->assignRole(

                'citizen'

            );





            $patient->update([


                'user_id'=>$user->id


            ]);


        }





        $token = $user->createToken(

            'CitizenApp'

        )->plainTextToken;





        return response()->json([


            'token'=>$token,


            'user'=>$user,


            'patient'=>$patient


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Register Staff
    |--------------------------------------------------------------------------
    */


    public function register(

        Request $request

    )

    {


        $request->validate([


            'name'=>'required',


            'email'=>'required|email|unique:users',


            'password'=>'required|min:8'


        ]);





        $user = User::create([


            'name'=>$request->name,


            'email'=>$request->email,


            'phone'=>$request->phone,


            'password'=>Hash::make(

                $request->password

            ),


            'status'=>'active'


        ]);





        $user->assignRole(

            $request->role ?? 'staff'

        );





        return response()->json([


            'message'=>'สร้างผู้ใช้งานสำเร็จ',


            'data'=>$user


        ],201);


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


            'message'=>'ออกจากระบบสำเร็จ'


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


            'data'=>$request

                ->user()

                ->load([

                    'roles',

                    'patient'

                ])


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


            'phone'=>'required',


            'password'=>'required|min:8'


        ]);





        $user = User::where(

            'phone',

            $request->phone

        )->firstOrFail();





        $user->update([


            'password'=>Hash::make(

                $request->password

            )


        ]);





        return response()->json([


            'message'=>'เปลี่ยนรหัสผ่านสำเร็จ'


        ]);


    }


}