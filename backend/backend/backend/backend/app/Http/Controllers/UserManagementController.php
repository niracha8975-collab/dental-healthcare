<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Models\Role;

use App\Models\Permission;

use App\Models\LoginHistory;

use App\Models\AuditLog;



class UserManagementController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | User List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $users = User::with([


            'roles',

            'permissions'


        ])

        ->when(

            $request->search,

            function($query) use($request){


                $query->where(

                    'name',

                    'like',

                    '%'.$request->search.'%'

                );


            }

        )

        ->paginate(20);





        return response()->json([


            'data'=>$users


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


            'name'=>

                'required',


            'email'=>

                'required|email|unique:users',


            'password'=>

                'required|min:8',


            'role_id'=>

                'required'


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


            'status'=>'active'


        ]);





        $role = Role::find(

            $request->role_id

        );





        if($role)

        {


            $user->roles()->attach(

                $role->id

            );


        }





        AuditLog::record(

            'create_user',

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


        $user->update([


            'name'=>

                $request->name,


            'email'=>

                $request->email



        ]);





        return response()->json([


            'message'=>'แก้ไขข้อมูลผู้ใช้สำเร็จ'


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


            'role_id'=>

                'required'


        ]);





        $user->roles()->sync([


            $request->role_id


        ]);





        AuditLog::record(

            'assign_role',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'กำหนด Role สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Assign Permission
    |--------------------------------------------------------------------------
    */


    public function assignPermission(

        Request $request,

        User $user

    )

    {


        $permissions = Permission::whereIn(

            'id',

            $request->permissions

        )

        ->pluck(

            'id'

        );





        $user->permissions()->sync(

            $permissions

        );





        return response()->json([


            'message'=>'กำหนด Permission สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Enable Disable Account
    |--------------------------------------------------------------------------
    */


    public function status(

        Request $request,

        User $user

    )

    {


        $user->update([


            'status'=>

                $request->status


        ]);





        AuditLog::record(

            'change_user_status',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'เปลี่ยนสถานะบัญชีสำเร็จ'


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


            'password'=>

                'required|min:8'


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
    | Login History
    |--------------------------------------------------------------------------
    */


    public function loginHistory(

        User $user

    )

    {


        return response()->json([


            'data'=>

                LoginHistory::where(

                    'user_id',

                    $user->id

                )

                ->latest()

                ->get()


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


        $user->update([


            'status'=>'inactive'


        ]);





        AuditLog::record(

            'delete_user',

            'User',

            $user->id

        );





        return response()->json([


            'message'=>'ปิดการใช้งานผู้ใช้สำเร็จ'


        ]);


    }


}