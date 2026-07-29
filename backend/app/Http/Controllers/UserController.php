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


        $users = User::with(

            'roles'

        )

        ->when(

            $request->role,

            function($query) use($request){


                $query->whereHas(

                    'roles',

                    function($q) use($request){


                        $q->where(

                            'name',

                            $request->role

                        );


                    }

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
    | User Profile
    |--------------------------------------------------------------------------
    */


    public function show(

        User $user

    )

    {


        return response()->json([


            'data'=>$user->load(

                'roles'

            )


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Create User
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'name'=>'required',


            'email'=>'required|email|unique:users',


            'password'=>'required|min:8',


            'role'=>'required'


        ]);





        $user = User::create([


            'name'=>

                $request->name,


            'email'=>

                $request->email,


            'password'=>

                Hash::make(

                    $request->password

                ),


            'phone'=>

                $request->phone,


            'active'=>true



        ]);





        $user->assignRole(

            $request->role

        );





        AuditLog::record(

            'create',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'สร้างผู้ใช้งานสำเร็จ',


            'data'=>$user


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Update User
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

                'active'


            ])

        );





        if(

            $request->role

        )

        {


            $user->syncRoles([


                $request->role


            ]);


        }





        AuditLog::record(

            'update',

            'User',

            $user->id,

            $old,

            $user->toArray()

        );





        return response()->json([


            'message'=>'แก้ไขผู้ใช้งานสำเร็จ',


            'data'=>$user


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
    */


    public function resetPassword(

        Request $request,

        User $user

    )

    {


        $request->validate([


            'password'=>'required|min:8'


        ]);





        $user->update([


            'password'=>

                Hash::make(

                    $request->password

                )


        ]);





        AuditLog::record(

            'reset_password',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'Reset Password สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Account
    |--------------------------------------------------------------------------
    */


    public function toggle(

        User $user

    )

    {


        $user->update([


            'active'=>

                !$user->active


        ]);





        return response()->json([


            'message'=>'เปลี่ยนสถานะบัญชีสำเร็จ',


            'active'=>$user->active


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Assign Role
    |--------------------------------------------------------------------------
    */


    public function role(

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

            'change_role',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'กำหนดสิทธิ์สำเร็จ',


            'role'=>$request->role


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    */


    public function destroy(

        User $user

    )

    {


        AuditLog::record(

            'delete',

            'User',

            $user->id

        );





        $user->delete();





        return response()->json([


            'message'=>'ลบผู้ใช้งานสำเร็จ'


        ]);


    }


}