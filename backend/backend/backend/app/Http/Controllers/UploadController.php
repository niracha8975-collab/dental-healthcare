<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\Models\Media;

use App\Models\AuditLog;



class UploadController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Upload File
    |--------------------------------------------------------------------------
    */


    public function store(

        Request $request

    )

    {


        $request->validate([


            'file'=>'required|file|max:10240',


            'type'=>

                'required|in:
                profile,
               xray,
               oral_photo,
               document',


        ]);





        $file = $request->file('file');





        $path = $file->store(

            'dental',

            'public'

        );





        $media = Media::create([


            'user_id'=>$request->user()->id,


            'patient_id'=>$request->patient_id,


            'dental_record_id'=>

                $request->dental_record_id,


            'type'=>$request->type,


            'file_name'=>

                $file->getClientOriginalName(),


            'file_path'=>$path,


            'mime_type'=>

                $file->getMimeType(),


            'size'=>

                $file->getSize()


        ]);





        AuditLog::record(

            'upload',

            'Media',

            $media->id,

            [],

            $media->toArray()

        );





        return response()->json([


            'message'=>'อัปโหลดไฟล์สำเร็จ',


            'data'=>$media


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Files
    |--------------------------------------------------------------------------
    */


    public function patientFiles(

        Request $request

    )

    {


        $files = Media::where(

            'patient_id',

            $request->patient_id

        )

        ->latest()

        ->get();





        return response()->json([


            'data'=>$files


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Download File
    |--------------------------------------------------------------------------
    */


    public function download(

        Media $media

    )

    {


        if(

            !Storage::disk('public')

                ->exists(

                    $media->file_path

                )

        )

        {


            return response()->json([


                'message'=>'ไม่พบไฟล์'


            ],404);


        }





        return Storage::disk('public')

            ->download(

                $media->file_path,

                $media->file_name

            );


    }





    /*
    |--------------------------------------------------------------------------
    | Delete File
    |--------------------------------------------------------------------------
    */


    public function destroy(

        Media $media

    )

    {


        Storage::disk('public')

            ->delete(

                $media->file_path

            );





        AuditLog::record(

            'delete',

            'Media',

            $media->id

        );





        $media->delete();





        return response()->json([


            'message'=>'ลบไฟล์สำเร็จ'


        ]);


    }


}