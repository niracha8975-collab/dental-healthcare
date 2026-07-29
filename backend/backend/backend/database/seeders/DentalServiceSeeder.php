<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use App\Models\DentalService;



class DentalServiceSeeder extends Seeder
{


    public function run(): void
    {


        $services = [



            /*
            |--------------------------------------------------------------------------
            | Examination
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'EXAM001',

                'name'=>'ตรวจสุขภาพช่องปาก',

                'description'=>'ตรวจประเมินสภาพช่องปากและวางแผนการรักษา',

                'category'=>'examination',

                'duration_minutes'=>30,

                'price'=>0,

                'default_queue_limit'=>30,

                'sort_order'=>1

            ],



            [

                'code'=>'CONSULT001',

                'name'=>'ให้คำปรึกษาทางทันตกรรม',

                'description'=>'ให้คำแนะนำเกี่ยวกับปัญหาสุขภาพช่องปาก',

                'category'=>'examination',

                'duration_minutes'=>20,

                'price'=>0,

                'default_queue_limit'=>40,

                'sort_order'=>2

            ],





            /*
            |--------------------------------------------------------------------------
            | Preventive Dentistry
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'SCAL001',

                'name'=>'ขูดหินปูน',

                'description'=>'กำจัดคราบหินปูนและทำความสะอาดฟัน',

                'category'=>'preventive',

                'duration_minutes'=>45,

                'price'=>500,

                'default_queue_limit'=>15,

                'sort_order'=>3

            ],



            [

                'code'=>'FLU001',

                'name'=>'เคลือบฟลูออไรด์',

                'description'=>'ป้องกันฟันผุด้วยฟลูออไรด์',

                'category'=>'preventive',

                'duration_minutes'=>30,

                'price'=>100,

                'default_queue_limit'=>30,

                'sort_order'=>4

            ],



            [

                'code'=>'SEAL001',

                'name'=>'เคลือบหลุมร่องฟัน',

                'description'=>'ป้องกันฟันกรามผุในเด็ก',

                'category'=>'pediatric',

                'duration_minutes'=>30,

                'price'=>100,

                'default_queue_limit'=>20,

                'sort_order'=>5

            ],





            /*
            |--------------------------------------------------------------------------
            | Restorative Dentistry
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'FILL001',

                'name'=>'อุดฟัน',

                'description'=>'รักษาฟันผุด้วยวัสดุบูรณะฟัน',

                'category'=>'restorative',

                'duration_minutes'=>60,

                'price'=>500,

                'default_queue_limit'=>10,

                'sort_order'=>6

            ],



            [

                'code'=>'TEMP001',

                'name'=>'อุดฟันชั่วคราว',

                'description'=>'บูรณะฟันชั่วคราวก่อนรักษาต่อ',

                'category'=>'restorative',

                'duration_minutes'=>30,

                'price'=>200,

                'default_queue_limit'=>20,

                'sort_order'=>7

            ],





            /*
            |--------------------------------------------------------------------------
            | Oral Surgery
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'EXT001',

                'name'=>'ถอนฟัน',

                'description'=>'ถอนฟันที่ไม่สามารถรักษาได้',

                'category'=>'surgery',

                'duration_minutes'=>45,

                'price'=>300,

                'default_queue_limit'=>15,

                'sort_order'=>8

            ],



            [

                'code'=>'SURG001',

                'name'=>'ผ่าฟันคุด',

                'description'=>'ศัลยกรรมถอนฟันคุด',

                'category'=>'surgery',

                'duration_minutes'=>90,

                'price'=>1500,

                'default_queue_limit'=>5,

                'sort_order'=>9

            ],





            /*
            |--------------------------------------------------------------------------
            | Periodontal
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'PERIO001',

                'name'=>'รักษาโรคปริทันต์',

                'description'=>'ประเมินและรักษาโรคเหงือก',

                'category'=>'periodontal',

                'duration_minutes'=>60,

                'price'=>800,

                'default_queue_limit'=>10,

                'sort_order'=>10

            ],





            /*
            |--------------------------------------------------------------------------
            | Pediatric Dentistry
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'PED001',

                'name'=>'ทันตกรรมเด็ก',

                'description'=>'บริการรักษาและส่งเสริมสุขภาพช่องปากเด็ก',

                'category'=>'pediatric',

                'duration_minutes'=>45,

                'price'=>300,

                'default_queue_limit'=>15,

                'sort_order'=>11

            ],





            /*
            |--------------------------------------------------------------------------
            | Oral Health Promotion
            |--------------------------------------------------------------------------
            */


            [

                'code'=>'PROMO001',

                'name'=>'กิจกรรมส่งเสริมสุขภาพช่องปาก',

                'description'=>'กิจกรรมให้ความรู้และป้องกันโรคในชุมชน',

                'category'=>'other',

                'duration_minutes'=>60,

                'price'=>0,

                'default_queue_limit'=>50,

                'sort_order'=>12

            ]


        ];





        foreach($services as $service)
        {


            DentalService::updateOrCreate(


                [

                    'code'=>$service['code']

                ],


                $service


            );


        }


    }


}