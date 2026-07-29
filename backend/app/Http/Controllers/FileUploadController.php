<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

use App\Models\FileAttachment;

use App\Models\DentalRecord;

use App\Models\Patient;

use App\Models\AuditLog;



class FileUploadController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | File List
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)

    {


        $files = FileAttachment::with([


            'patient',

            'record'


        ])

        ->when(

            $request->patient_id,

            function($query) use($request){


                $query->where(

                    'patient_id',

                    $request->patient_id

                );


            }

        )

        ->latest()

        ->get();





        return response()->json([


            'data'=>$files


        ]);


    }





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


            'file'=>

                'required|file|max:10240',


            'type'=>

                'required|in:xray,oral_photo,consent,document',


            'patient_id'=>

                'required|exists:patients,id'


        ]);





        $file = $request->file(

            'file'

        );





        $filename = Str::uuid()

            ->toString()

            .'.'

            .$file->extension();





        $path = $file->storeAs(

            'medical_files',

            $filename,

            'private'

        );





        $attachment = FileAttachment::create([


            'patient_id'=>

                $request->patient_id,


            'dental_record_id'=>

                $request->dental_record_id,


            'type'=>

                $request->type,


            'file_name'=>

                $file->getClientOriginalName(),


            'file_path'=>

                $path,


            'mime_type'=>

                $file->getMimeType(),


            'uploaded_by'=>

                auth()->id()


        ]);





        AuditLog::record(

            'upload_file',

            'FileAttachment',

            $attachment->id

        );





        return response()->json([


            'message'=>'Upload สำเร็จ',


            'data'=>$attachment


        ],201);


    }





    /*
    |--------------------------------------------------------------------------
    | Upload X-ray Shortcut
    |--------------------------------------------------------------------------
    */


    public function xray(

        Request $request

    )

    {


        $request->merge([


            'type'=>'xray'


        ]);





        return $this->store(

            $request

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Upload Oral Photo
    |--------------------------------------------------------------------------
    */


    public function oralPhoto(

        Request $request

    )

    {


        $request->merge([


            'type'=>'oral_photo'


        ]);





        return $this->store(

            $request

        );


    }





    /*
    |--------------------------------------------------------------------------
    | Download File
    |--------------------------------------------------------------------------
    */


    public function download(

        FileAttachment $file

    )

    {


        if(

            !Storage::disk('private')

                ->exists(

                    $file->file_path

                )

        )

        {


            return response()->json([


                'message'=>'ไม่พบไฟล์'


            ],404);


        }





        AuditLog::record(

            'download_file',

            'FileAttachment',

            $file->id

        );





        return Storage::disk('private')

            ->download(

                $file->file_path,

                $file->file_name

            );


    }





    /*
    |--------------------------------------------------------------------------
    | Delete File
    |--------------------------------------------------------------------------
    */


    public function destroy(

        FileAttachment $file

    )

    {


        Storage::disk('private')

            ->delete(

                $file->file_path

            );





        AuditLog::record(

            'delete_file',

            'FileAttachment',

            $file->id

        );





        $file->delete();





        return response()->json([


            'message'=>'ลบไฟล์สำเร็จ'


        ]);


    }





    /*
    |--------------------------------------------------------------------------
    | Patient Files
    |--------------------------------------------------------------------------
    */


    public function patientFiles(

        Patient $patient

    )

    {


        return response()->json([


            'data'=>

                FileAttachment::where(

                    'patient_id',

                    $patient->id

                )

                ->get()


        ]);


    }


}