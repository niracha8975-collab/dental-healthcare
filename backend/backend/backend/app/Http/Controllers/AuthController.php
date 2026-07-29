<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;

use App\Models\User;

use App\Models\Patient;



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


            'email'=>'required|email',


            'password'=>'required'


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


            throw ValidationException::withMessages([


                'email'=>[

                    'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง'

                ]


            ]);


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

            'DentalHealthcareApp'

        )->plainTextToken;





        return response()->json([


            'success'=>true,


            'message'=>'เข้าสู่ระบบสำเร็จ',


            'token'=>$token,


            'user'=>$this->userResponse($user)


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Citizen Register
    |--------------------------------------------------------------------------
    */


    public function registerCitizen(Request $request)
    {


        $request->validate([


            'name'=>'required',


            'email'=>'required|email|unique:users',


            'password'=>'required|min:8',


            'phone'=>'required'


        ]);





        $user = User::create([


            'name'=>$request->name,


            'email'=>$request->email,


            'phone'=>$request->phone,


            'password'=>Hash::make(

                $request->password

            ),


            'user_type'=>'citizen',


            'status'=>'active'


        ]);





        $user->assignRole(

            'Citizen'

        );





        Patient::create([


            'user_id'=>$user->id,


            'first_name'=>$request->name,


            'phone'=>$request->phone


        ]);





        return response()->json([


            'message'=>'สมัครสมาชิกสำเร็จ'


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


            'user'=>$this->userResponse(

                $request->user()

            )


        ]);


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





        return response()->json([


            'message'=>'ออกจากระบบสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | User Response
    |--------------------------------------------------------------------------
    */


    private function userResponse(

        User $user

    )

    {


        return [


            'id'=>$user->id,


            'name'=>$user->name,


            'email'=>$user->email,


            'phone'=>$user->phone,


            'roles'=>$user->getRoleNames(),


            'permissions'=>$user->getAllPermissions()

                ->pluck('name')


        ];


    }


}