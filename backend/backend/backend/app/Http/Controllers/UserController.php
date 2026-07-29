<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Models\AuditLog;



class UserController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | User List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {


        $users = User::with('roles')

            ->when(

                $request->keyword,

                function($query) use($request){


                    $query->where(

                        'name',

                        'like',

                        "%{$request->keyword}%"

                    )

                    ->orWhere(

                        'email',

                        'like',

                        "%{$request->keyword}%"

                    );


                }

            )

            ->latest()

            ->paginate(20);





        return response()->json([


            'data'=>$users


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Show User
    |--------------------------------------------------------------------------
    */


    public function show(

        User $user

    )

    {


        return response()->json([


            'data'=>$user->load([


                'roles',

                'patient'


            ])


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */


    public function update(

        Request $request,

        User $user

    )

    {


        $old = $user->toArray();





        $user->update(

            $request->only([


                'name',

                'phone',

                'email'


            ])

        );





        AuditLog::record(

            'update',

            'User',

            $user->id,

            $old,

            $user->toArray()

        );





        return response()->json([


            'message'=>'แก้ไขข้อมูลผู้ใช้งานสำเร็จ',


            'data'=>$user


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */


    public function changePassword(

        Request $request,

        User $user

    )

    {


        $request->validate([


            'password'=>'required|min:8|confirmed'


        ]);





        $user->update([


            'password'=>Hash::make(

                $request->password

            )


        ]);





        AuditLog::record(

            'password_change',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'เปลี่ยนรหัสผ่านสำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Assign Role
    |--------------------------------------------------------------------------
    */


    public function assignRole(

        Request $request,

        User $user

    )

    {


        $request->validate([


            'role'=>'required'


        ]);





        $user->syncRoles([


            $request->role


        ]);





        AuditLog::record(

            'assign_role',

            'User',

            $user->id,

            [],

            [

                'role'=>$request->role

            ]

        );





        return response()->json([


            'message'=>'กำหนดสิทธิ์สำเร็จ',


            'role'=>$request->role


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Account Status
    |--------------------------------------------------------------------------
    */


    public function toggleStatus(

        User $user

    )

    {


        $user->update([


            'status'=>

                $user->status === 'active'

                ? 'inactive'

                : 'active'


        ]);





        return response()->json([


            'message'=>'เปลี่ยนสถานะบัญชีสำเร็จ',


            'status'=>$user->status


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Current User Profile
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


}