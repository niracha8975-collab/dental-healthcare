<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

use App\Models\Setting;



class SettingSeeder extends Seeder
{


    public function run(): void
    {


        $settings = [


            /*
            |--------------------------------------------------------------------------
            | General System
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'system_name',

                'value'=>'Dental Healthcare',

                'type'=>'string',

                'group'=>'general',

                'description'=>'ชื่อระบบหลัก',

                'is_public'=>true
            ],


            [
                'key'=>'system_version',

                'value'=>'1.0.0',

                'type'=>'string',

                'group'=>'general',

                'description'=>'เวอร์ชันระบบ',

                'is_public'=>true
            ],


            [
                'key'=>'welcome_message',

                'value'=>'บริการสุขภาพใกล้บ้าน',

                'type'=>'string',

                'group'=>'general',

                'description'=>'ข้อความต้อนรับ',

                'is_public'=>true
            ],





            /*
            |--------------------------------------------------------------------------
            | Hospital Profile
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'hospital_name',

                'value'=>'โรงพยาบาลส่งเสริมสุขภาพตำบลไร่หลักทอง',

                'type'=>'string',

                'group'=>'hospital',

                'description'=>'ชื่อหน่วยบริการ',

                'is_public'=>true
            ],


            [
                'key'=>'hospital_logo',

                'value'=>'assets/logo.png',

                'type'=>'image',

                'group'=>'hospital',

                'description'=>'โลโก้หน่วยบริการ',

                'is_public'=>true
            ],


            [
                'key'=>'hospital_phone',

                'value'=>'038-000000',

                'type'=>'string',

                'group'=>'hospital',

                'description'=>'เบอร์ติดต่อ',

                'is_public'=>true
            ],





            /*
            |--------------------------------------------------------------------------
            | Theme
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'primary_color',

                'value'=>'#2E7D32',

                'type'=>'string',

                'group'=>'theme',

                'description'=>'สีหลักของระบบ',

                'is_public'=>true
            ],


            [
                'key'=>'secondary_color',

                'value'=>'#FFFFFF',

                'type'=>'string',

                'group'=>'theme',

                'description'=>'สีรอง',

                'is_public'=>true
            ],


            [
                'key'=>'button_color',

                'value'=>'#81C784',

                'type'=>'string',

                'group'=>'theme',

                'description'=>'สีปุ่ม',

                'is_public'=>true
            ],





            /*
            |--------------------------------------------------------------------------
            | Appointment
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'booking_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'appointment',

                'description'=>'เปิดระบบจองคิว',

                'is_public'=>true
            ],


            [
                'key'=>'advance_booking_days',

                'value'=>'30',

                'type'=>'number',

                'group'=>'appointment',

                'description'=>'จำนวนวันที่ให้จองล่วงหน้า',

                'is_public'=>true
            ],


            [
                'key'=>'default_queue_limit',

                'value'=>'20',

                'type'=>'number',

                'group'=>'appointment',

                'description'=>'จำนวนคิวเริ่มต้น',

                'is_public'=>false
            ],





            /*
            |--------------------------------------------------------------------------
            | Notification
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'notification_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'notification',

                'description'=>'เปิดระบบแจ้งเตือน',

                'is_public'=>false
            ],


            [
                'key'=>'firebase_enabled',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'notification',

                'description'=>'ใช้งาน Firebase Push',

                'is_public'=>false
            ],





            /*
            |--------------------------------------------------------------------------
            | My PCU Integration
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'mypcu_enabled',

                'value'=>'false',

                'type'=>'boolean',

                'group'=>'integration',

                'description'=>'เปิดใช้งาน My PCU',

                'is_public'=>false
            ],


            [
                'key'=>'mypcu_sync_patient',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'integration',

                'description'=>'Sync ข้อมูลผู้ป่วย',

                'is_public'=>false
            ],


            [
                'key'=>'mypcu_sync_appointment',

                'value'=>'true',

                'type'=>'boolean',

                'group'=>'integration',

                'description'=>'Sync ข้อมูลนัดหมาย',

                'is_public'=>false
            ],





            /*
            |--------------------------------------------------------------------------
            | Security
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'max_login_attempt',

                'value'=>'5',

                'type'=>'number',

                'group'=>'security',

                'description'=>'จำนวน Login ผิดสูงสุด',

                'is_public'=>false
            ],


            [
                'key'=>'session_timeout',

                'value'=>'120',

                'type'=>'number',

                'group'=>'security',

                'description'=>'เวลาหมดอายุ Session นาที',

                'is_public'=>false
            ],





            /*
            |--------------------------------------------------------------------------
            | Report
            |--------------------------------------------------------------------------
            */


            [
                'key'=>'report_export',

                'value'=>'excel,pdf',

                'type'=>'string',

                'group'=>'report',

                'description'=>'รูปแบบรายงาน',

                'is_public'=>false
            ],


        ];





        foreach($settings as $setting)
        {


            Setting::updateOrCreate(

                [

                    'key'=>$setting['key']

                ],

                $setting

            );


        }


    }


}