<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Models\AuditLog;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class AdminController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Users List
    |--------------------------------------------------------------------------
    */

    public function users(Request $request)
    {

        $users = User::with('roles');


        if($request->role)
        {

            $users->role(

                $request->role

            );

        }


        return response()->json([

            'success'=>true,

            'data'=>

                $users

                ->latest()

                ->paginate(20)

        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Create Staff
    |--------------------------------------------------------------------------
    */

    public function createStaff(Request $request)
    {

        $validated = $request->validate([


            'name'=>

                'required|string',


            'phone'=>

                'required|unique:users',


            'password'=>

                'required|min:8',


            'role'=>

                'required|string'


        ]);



        $user = User::create([


            'name'=>

                $validated['name'],


            'phone'=>

                $validated['phone'],


            'password'=>

                Hash::make(

                    $validated['password']

                ),


            'status'=>

                'active'


        ]);



        $user->assignRole(

            $validated['role']

        );



        AuditLog::createLog(

            'CREATE',

            'ADMIN_USER',

            'User',

            $user->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'สร้างผู้ใช้งานสำเร็จ',


            'data'=>$user


        ],201);

    }





    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    */

    public function updateUser(

        Request $request,

        User $user

    )
    {


        $old = $user->toArray();



        $user->update(

            $request->except(

                'password'

            )

        );



        if($request->password)
        {

            $user->update([


                'password'=>

                    Hash::make(

                        $request->password

                    )


            ]);

        }



        AuditLog::createLog(

            'UPDATE',

            'ADMIN_USER',

            'User',

            $user->id,

            $old,

            $user->fresh()->toArray()

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'แก้ไขข้อมูลผู้ใช้สำเร็จ',


            'data'=>$user


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


            'role'=>

                'required|string'


        ]);



        $user->syncRoles([


            $request->role


        ]);



        AuditLog::createLog(

            'UPDATE_ROLE',

            'ADMIN_USER',

            'User',

            $user->id

        );



        return response()->json([


            'success'=>true,


            'message'=>

                'กำหนดสิทธิ์สำเร็จ'


        ]);

    }





    /*
    |--------------------------------------------------------------------------
    | Toggle Status
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


            'success'=>true,


            'message'=>

                'เปลี่ยนสถานะผู้ใช้งานแล้ว',


            'data'=>$user


        ]);

    }





}