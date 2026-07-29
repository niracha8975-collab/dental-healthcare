<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Queue;

use App\Services\MyPCUService;

use App\Services\HOSxPService;



class HealthCheckController extends Controller
{


    protected MyPCUService $myPCU;

    protected HOSxPService $hosxp;



    public function __construct(

        MyPCUService $myPCU,

        HOSxPService $hosxp

    )

    {

        $this->myPCU = $myPCU;

        $this->hosxp = $hosxp;

    }





    /*
    |--------------------------------------------------------------------------
    | Full System Health
    |--------------------------------------------------------------------------
    */


    public function index()

    {


        $start = microtime(true);





        $health = [


            'application'=>

                [

                    'status'=>'online',

                    'time'=>now()

                ],



            'database'=>

                $this->database(),



            'storage'=>

                $this->storage(),



            'cache'=>

                $this->cache(),



            'queue'=>

                $this->queue(),



            'mypcu'=>

                $this->mypcu(),



            'hosxp'=>

                $this->hosxp(),



            'response_time'=>

                round(

                    (microtime(true)-$start)*1000,

                    2

                )

                .' ms'


        ];





        return response()->json([


            'status'=>

                'healthy',


            'services'=>$health


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Database Check
    |--------------------------------------------------------------------------
    */


    private function database()

    {


        try {


            DB::connection()->getPdo();



            return [

                'status'=>'online',

                'message'=>'Database connected'

            ];



        }

        catch(\Exception $e)

        {


            return [

                'status'=>'error',

                'message'=>$e->getMessage()

            ];


        }


    }





    /*
    |--------------------------------------------------------------------------
    | Storage Check
    |--------------------------------------------------------------------------
    */


    private function storage()

    {


        try {


            Storage::disk(

                'private'

            )->put(

                'health-check.txt',

                now()

            );





            Storage::disk(

                'private'

            )->delete(

                'health-check.txt'

            );





            return [

                'status'=>'online'

            ];



        }

        catch(\Exception $e)

        {


            return [

                'status'=>'error'

            ];


        }


    }





    /*
    |--------------------------------------------------------------------------
    | Cache Check
    |--------------------------------------------------------------------------
    */


    private function cache()

    {


        try {


            Cache::put(

                'health_check',

                true,

                10

            );





            return [

                'status'=>'online'

            ];



        }

        catch(\Exception $e)

        {


            return [

                'status'=>'error'

            ];


        }


    }





    /*
    |--------------------------------------------------------------------------
    | Queue Check
    |--------------------------------------------------------------------------
    */


    private function queue()

    {


        return [

            'status'=>'online',

            'driver'=>

                config(

                    'queue.default'

                )

        ];


    }





    /*
    |--------------------------------------------------------------------------
    | My PCU Check
    |--------------------------------------------------------------------------
    */


    private function mypcu()

    {


        try {


            return [

                'status'=>

                    $this->myPCU->ping()

                    ?

                    'online'

                    :

                    'offline'

            ];



        }

        catch(\Exception $e)

        {


            return [

                'status'=>'offline'

            ];


        }


    }





    /*
    |--------------------------------------------------------------------------
    | HOSxP Check
    |--------------------------------------------------------------------------
    */


    private function hosxp()

    {


        try {


            return [

                'status'=>

                    $this->hosxp->ping()

                    ?

                    'online'

                    :

                    'offline'

            ];



        }

        catch(\Exception $e)

        {


            return [

                'status'=>'offline'

            ];


        }


    }





    /*
    |--------------------------------------------------------------------------
    | Simple Ping
    |--------------------------------------------------------------------------
    */


    public function ping()

    {


        return response()->json([


            'status'=>'ok',


            'timestamp'=>now()


        ]);


    }





}