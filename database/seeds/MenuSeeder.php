<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `menus` (`id`, `parent_id`, `name`, `icon`, `menu_url`, `president`, `status`, `created_at`, `updated_at`) VALUES
        (1, 0, 'Administration', 'fa-sitemap', NULL, 1, 0, NULL, NULL),
        (2, 0, 'Product', 'fa-diamond', NULL, 2, 0, NULL, NULL),
        (3, 0, 'Offers & Coupon', 'fa-fire', NULL, 3, 0, NULL, NULL),
        (4, 0, 'Order', 'fa-first-order', NULL, 4, 0, NULL, NULL),
        (5, 0, 'Customer', 'fa-group', 'customer.index', 5, 0, NULL, NULL),
        (6, 0, 'General Setting', 'fa-cog', NULL, 6, 0, NULL, NULL),
        (7, 0, 'Emailing User', 'fa-envelope', 'email.index', 7, 0, NULL, NULL),
        (8, 1, 'Role', NULL, 'role.index', 1, 0, NULL, NULL),
        (9, 1, 'Manage Admin', NULL, 'admin.index', 2, 0, NULL, NULL),
        (10, 2, 'Category', NULL, 'category.index', 1, 0, NULL, NULL),
        (11, 2, 'Brand', NULL, 'brand.index', 3, 0, NULL, NULL),
        (12, 2, 'Sub Category', NULL, 'sub-category.index', 2, 0, NULL, NULL),
        (13, 2, 'Product', NULL, 'product.index', 5, 0, NULL, NULL),
        (14, 3, 'Campaign', NULL, 'offer.index', 1, 0, NULL, NULL),
        (15, 6, 'Currency Setting', NULL, 'currency.index', 1, 0, NULL, NULL),
        (16, 6, 'Pyament Gateway Setting', NULL, 'gateway.index', 2, 0, NULL, NULL),
        (17, 6, 'Social Login Setting', NULL, 'social.index', 3, 0, NULL, NULL),
        (18, 6, 'Shop Setting', NULL, 'shop.index', 4, 0, NULL, NULL),
        (19, 6, 'Page Seting', NULL, 'pages.index', 5, 0, NULL, NULL),
        (20, 6, 'Seo Setting', NULL, 'seo.index', 6, 0, NULL, NULL),
        (21, 6, 'Messenger & Google Analytics', NULL, 'message.analytics', 7, 0, NULL, NULL),
        (22, 6, 'Mail Setting', NULL, 'email-setting.index', 8, 0, NULL, NULL),
        (23, 6, 'Shipping Cost Setting', NULL, 'shipping.index', 2, 0, NULL, NULL),
        (24, 0, 'Report', 'fa-fire', NULL, 9, 0, NULL, NULL),
        (25, 24, 'Stock Report', NULL, 'stock.report', 1, 0, NULL, NULL),
        (26, 24, 'Sales Report', NULL, 'sales.report', 2, 0, NULL, NULL),
        (27, 24, 'Invoice Report', NULL, 'invoice.report', 3, 0, NULL, NULL),
        (28, 6, 'Order Area', NULL, 'order-area.index', 9, 0, NULL, NULL),
        (29, 4, 'All Order', NULL, 'order.index', 1, 0, NULL, NULL),
        (30, 4, 'My Area\'s Order', NULL, 'areawiseorder.index', 2, 0, NULL, NULL),
        (31, 24, 'Account Report', NULL, 'transection.report', 4, 0, NULL, NULL),
        (32, 2, 'Sub Sub Category', NULL, 'sub-sub-category.index', 4, 1, NULL, NULL),
        (33, 6, 'Slider', NULL, 'slider.index', 10, 1, '2020-07-10 09:35:22', '2020-07-10 09:35:23'),
        (34, 2, 'Color', NULL, 'product-color.index', 7, 0, NULL, NULL),
        (35, 2, 'Size', NULL, 'product-size.index', 6, 0, NULL, NULL),
        (36, 3, 'Coupon', NULL, 'coupon.index', 3, 0, NULL, NULL),
        (37, 3, 'Send Coupon', NULL, 'send-coupon.index', 4, 0, NULL, NULL),
        (38, 6, 'Trial', NULL, 'trial.index', 11, 1, NULL, NULL),
        (39, 6, 'Date Slot', NULL, 'slot.index', 12, 1, NULL, NULL)
        ");
    }
}
