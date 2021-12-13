<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `payment_settings` (`id`, `provider`, `client_id`, `client_secret`, `public_key`, `status`, `live_status`, `created_at`, `updated_at`) VALUES
        (1, 'Cash', '', '', 'abc', 1, 1, '2020-04-23 22:45:09', '2020-04-23 22:45:12'),
        (2, 'paypal', 'AXhhMYItOyEdhEUJCF-wFASr9JGyPFd1f38jv32OtDzrxPiKUwBTLV9ewjgJJuZb8C_G7Nh-YXOil-vj', 'EF8JKcIf4YOMKtYTLBPiEx9VPenq6qiv5DNXlpJkDuPlIMUCYi16B08RDuLOY8r1VriMRNG563x_InvS',
        'abc', 1, 0, '2020-04-12 16:20:49', '2020-04-11 09:48:52'),
        (3, 'stripe', 'pk_test_76vAdtoXsSBtYoxrZNCQAFPm007T5F0FL6', 'sk_test_6mdHPx4HzdfgvHl03vQeeKB500lLcs7oAy', 'abc', 1, 0, '2020-04-12 16:20:50', '2020-05-11 03:19:36'),
        (4, 'ssl-commerz', 'limme5e92958f76c27', 'limme5e92958f76c27@ssl', 'abc', 1, 0, '2020-04-12 16:20:45', '2020-05-11 03:19:44'),
        (5, 'Razorpay', 'rzp_test_u9yNniBONQCWfF', '2B5sHo2BbgRt9vnf6vaoSZk2', 'abc', 1, 1, '2020-07-03 18:59:11', '2020-07-03 18:59:12'),
        (6, 'Flutterwave', 'FLWSECK_TEST-e527bac3669759b9dccb8152d72bd638-X', '2B5sHo2BbgRt9vnf6vaoSZk2', 'FLWPUBK_TEST-3796e2f4e0ada89eb3583850b4017924-X', 1, 1, '2020-07-03 18:59:11', '2020-07-03 18:59:12')");
    }
}
