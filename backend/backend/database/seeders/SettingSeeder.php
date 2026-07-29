<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Setting;



class SettingSeeder extends Seeder
{


    public function run(): void
    {


        $settings = [


            [
                'key'=>'facility_name',

                'value'=>'โรงพยาบาลส่งเสริมสุขภาพตำบลไร่หลักทอง',

                'type'=>'string',

                'group'=>'facility',

                'description'=>'ชื่อหน่วยบริการ',

                'is_public'=>true
            ],



            [
                'key'=>'app_name',

                'value'=>'Dental Healthcare',

                'type'=>'string',

                'group'=>'application',

                'description'=>'ชื่อระบบ',

                'is_public'=>true
            ],



            [
                'key'=>'welcome_message',

                'value'=>'บริการสุขภาพใกล้บ้าน',

                'type'=>'string',

                'group'=>'application',

                'description'=>'ข้อความหน้าแรก',

                'is_public'=>true
            ],



            [
                'key'=>'primary_color',

                'value'=>'#2E7D32',

                'type'=>'string',

                'group'=>'theme',

                'description'=>'สีหลักของระบบ',

                'is_public'=>true
            ],



            [
                'key'=>'booking_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'appointment',

                'description'=>'เปิดใช้งานระบบจองคิว',

                'is_public'=>true
            ],



            [
                'key'=>'max_booking_days',

                'value'=>'30',

                'type'=>'number',

                'group'=>'appointment',

                'description'=>'จำนวนวันที่สามารถจองล่วงหน้า',

                'is_public'=>true
            ],



            [
                'key'=>'daily_queue_limit',

                'value'=>'50',

                'type'=>'number',

                'group'=>'appointment',

                'description'=>'จำนวนคิวสูงสุดต่อวัน',

                'is_public'=>false
            ],



            [
                'key'=>'notification_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'notification',

                'description'=>'เปิดระบบแจ้งเตือน',

                'is_public'=>false
            ],



            [
                'key'=>'mypcu_sync_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'integration',

                'description'=>'เปิดระบบเชื่อม MyPCU',

                'is_public'=>false
            ],


        ];



        foreach($settings as $setting)
        {

            Setting::create(
                $setting
            );

        }


    }

}