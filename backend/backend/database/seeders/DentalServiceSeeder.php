<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\DentalService;



class DentalServiceSeeder extends Seeder
{


    /**
     * Create default dental services
     */
    public function run(): void
    {


        $services = [


            [
                'code'=>'D001',

                'name'=>'ตรวจสุขภาพช่องปาก',

                'description'=>'ตรวจประเมินสุขภาพช่องปากและวางแผนการรักษา',

                'category'=>'examination',

                'duration_minutes'=>20,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D002',

                'name'=>'ขูดหินปูน',

                'description'=>'กำจัดคราบหินปูนและคราบจุลินทรีย์',

                'category'=>'periodontal',

                'duration_minutes'=>45,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D003',

                'name'=>'อุดฟัน',

                'description'=>'รักษาฟันผุด้วยวัสดุบูรณะฟัน',

                'category'=>'restorative',

                'duration_minutes'=>60,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D004',

                'name'=>'ถอนฟัน',

                'description'=>'ถอนฟันที่ไม่สามารถรักษาได้',

                'category'=>'surgery',

                'duration_minutes'=>30,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D005',

                'name'=>'เคลือบฟลูออไรด์',

                'description'=>'บริการป้องกันฟันผุด้วยฟลูออไรด์',

                'category'=>'preventive',

                'duration_minutes'=>20,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D006',

                'name'=>'ทาฟลูออไรด์วานิช',

                'description'=>'ส่งเสริมป้องกันฟันผุในเด็ก',

                'category'=>'preventive',

                'duration_minutes'=>15,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D007',

                'name'=>'เคลือบหลุมร่องฟัน',

                'description'=>'ป้องกันฟันผุในร่องฟัน',

                'category'=>'preventive',

                'duration_minutes'=>30,

                'price'=>0,

                'requires_appointment'=>true,

                'status'=>'active'
            ],



            [
                'code'=>'D008',

                'name'=>'ให้คำแนะนำสุขภาพช่องปาก',

                'description'=>'ให้ความรู้และคำแนะนำด้านทันตสุขศึกษา',

                'category'=>'preventive',

                'duration_minutes'=>20,

                'price'=>0,

                'requires_appointment'=>false,

                'status'=>'active'
            ],


        ];



        foreach($services as $service)
        {

            DentalService::create(
                $service
            );

        }


    }

}