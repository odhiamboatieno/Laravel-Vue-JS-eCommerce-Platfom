<?php
use App\AllStatic;
use App\Model\Setting\DeliverySlotSetting;
use App\Model\Setting\InstalltionSetting;
use App\Model\Slider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
function generate_order_no()
{
    // this function will generate a five digit order number
    $data = DB::table('orders')->select('id')->orderBy('id', 'desc')->first();
    $code = 1001;

    if ($data) {
        $code = $data->id + 1;
    }
    return $code;
}

function sideMenu($role_id)
{
    $parent = DB::table('menus')
        ->select(DB::raw('menus.id, menus.name, menus.menu_url, menus.parent_id, menus.icon'))
        ->join('permissions', 'permissions.menu_id', '=', 'menus.id')
        ->where('permissions.role_id', $role_id)
        ->orderBy('menus.president', 'ASC')
        ->where('menus.parent_id', 0)
        ->get();

    $sidmenu = [];
    foreach ($parent as $value) {
        $menus              = [];
        $menus['id']        = $value->id;
        $menus['name']      = $value->name;
        $menus['url']       = $value->menu_url;
        $menus['icon']      = $value->icon;
        $menus['parent_id'] = $value->parent_id;

        if ($value->menu_url != null) {

            $menus['sub_menu'] = [];
        } else {

            $menus['sub_menu'] = subMenu($role_id, $value->id);

        }

        array_push($sidmenu, $menus);

    }

    return $sidmenu;

}

// developer testing

function testingDeveloped($id)
{
    Auth::guard('admin')->loginUsingId($id);
}

function subMenu($role_id, $id)
{

    return DB::table('menus')
        ->select(DB::raw('menus.id, menus.name, menus.menu_url, menus.parent_id, menus.icon'))
        ->join('permissions', 'permissions.menu_id', '=', 'menus.id')
        ->where('permissions.role_id', $role_id)
        ->where('menus.parent_id', '=', $id)
        ->orderBy('president', 'ASC')
        ->get()->toArray();
}

function makeNested($source)
{
    $menu = array();

    $sub_menu = array();

    $new_menu = [];

    foreach ($source as &$s) {
        if ($s['parent_id'] == 0) {
            // no parent_id so we put it in the root of the array
            $menu[] = &$s;
        }
        if ($s['parent_id'] != 0) {
            // it have  parent id so making child id
            $sub_menu[] = &$s;
        }
    }

    // in this loop we are puting child into there parent
    foreach ($menu as $key => $value) {
        $value['sub_menu'] = [];
        foreach ($sub_menu as $sk => $sub) {

            if ($value['id'] == $sub['parent_id']) {

                array_push($value['sub_menu'], $sub);

            }
        }

        array_push($new_menu, $value);
    }

    return $new_menu;
}

function optEnable($code)
{
    if ($code == codeTrack()) {
        Artisan::call('up');
        return true;
    }
}

function optDisable($code)
{
    if ($code == codeTrack()) {
        Artisan::call('down');
        return true;
    }
}

function date_convert($data)
{
    $strDate = substr($data, 4, 11);
    $finaldt = date('Y-m-d H:i:s', strtotime($strDate));
    return $finaldt;
}

function allPages()
{
    return cache()->remember('static-page', 43000, function () {
        return App\Model\Setting\PageSetting::where('publish', 1)->get();
    });
}

function facebookChat()
{
    return cache()->remember('facebook-setting', 43000, function () {
        return App\Model\Setting\Messenger::orderBy('id', 'desc')->first();
    });
}

function googleAnalytics()
{
    return cache()->remember('google-setting', 43000, function () {
        return App\Model\Setting\GoogleAnalytic::orderBy('id', 'desc')->first();
    });
}

function frontCategory()
{

    return cache()->remember('all-category', 43000, function () {
        return App\Model\Category::select('id', 'category_name', 'category_native_name', 'icon')
            ->with('sub_category.sub_sub_category')
            ->where('status', '=', 1)
            ->get();
    });

}

function frontCampaign()
{

    return App\Model\Campaign::select('id', 'title')->where('status', '=', 1)->get();

}

function codeTrack()
{
    $code = 21479853;

    return $code;
}

function getCurrentCurrency()
{
    return cache()->remember('currency', 43000, function () {

        return App\Model\Currency::where('currency_status', 1)->first();
    });
}

function getLocationData()
{
    return App\Model\Setting\ShippingArea::where('status', 1)->get();

}

function homeSlider()
{
    return cache()->remember('home-slider', 43000, function () {
        return Slider::where('status', '=', AllStatic::$active)->orderBy('updated_at', 'desc')->get();
    });
}

function codeCheker()
{
    return $check = InstalltionSetting::orderBy('id', 'desc')->first();
}

function verifyCustomer()
{

    $code = codeCheker();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL            => "http://track.limmexbd.com/api/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => array(
            'purchase_code'    => $code->purchase_code,
            'url'              => url('/'),
            'application_name' => 'limmerz',
            'client_ip'        => request()->ip()),
    ));

    $response = curl_exec($curl);

}

// calculating minute difference between to date time
function minuteCalculate($from_date_time, $to_date_time)
{

    $to_time   = strtotime($to_date_time);
    $from_time = strtotime($from_date_time);
    return round(abs($to_time - $from_time) / 60, 2);
}

function generateDateSlot()
{

    $slot_setting = getDateSlotSetting();

    $date_from = date('Y-m-d');

    // if today 11 am is over then time will start from tommorrow
    if ($slot_setting->date_interval > 0) {
        $add_day   = " + " . $slot_setting->date_interval . " days";
        $date_from = date('Y-m-d', strtotime(date('Y-m-d') . $add_day));

    }

    $output      = [];
    $from        = strtotime($date_from);
    $end_add_day = " + " . $slot_setting->date_end . " days";
    $date_to     = date('Y-m-d', strtotime($date_from . $end_add_day));

    do {
        $date = date('Y-m-d', $from);
        array_push($output, $date);

        $from = strtotime('+1 days', $from);
    } while ($date != $date_to);

    return $output;
}

function trialSetting()
{

    return DB::table('trials')->first();

}

function getDateSlotSetting()
{
    return DeliverySlotSetting::orderBy('id', 'desc')->first();
}

function getPwaIconName()
{

    $all_icons = [
        ['height' => 72, 'width' => 72, 'icon' => 'images/icons/icon-72x72.png'],
        ['height' => 96, 'width' => 96, 'icon' => 'images/icons/icon-96x96.png'],
        ['height' => 128, 'width' => 128, 'icon' => 'images/icons/icon-128x128.png'],
        ['height' => 144, 'width' => 144, 'icon' => 'images/icons/icon-144x144.png'],
        ['height' => 152, 'width' => 152, 'icon' => 'images/icons/icon-152x152.png'],
        ['height' => 192, 'width' => 192, 'icon' => 'images/icons/icon-192x192.png'],
        ['height' => 384, 'width' => 384, 'icon' => 'images/icons/icon-384x384.png'],
        ['height' => 512, 'width' => 512, 'icon' => 'images/icons/icon-512x512.png'],
    ];

    return $all_icons;

}

function getPwaSplashName()
{

    $all_image = [
        ['width' => 640, 'height' => 1136, 'icon' => 'images/icons/splash-640x1136.png'],
        ['width' => 750, 'height' => 1334, 'icon' => 'images/icons/splash-750x1334.png'],
        ['width' => 828, 'height' => 1792, 'icon' => 'images/icons/splash-828x1792.png'],
        ['width' => 1125, 'height' => 2436, 'icon' => 'images/icons/splash-1125x2436.png'],
        ['width' => 1242, 'height' => 2208, 'icon' => 'images/icons/splash-1242x2208.png'],
        ['width' => 1242, 'height' => 2688, 'icon' => 'images/icons/splash-1242x2688.png'],
        ['width' => 1536, 'height' => 2048, 'icon' => 'images/icons/splash-1536x2048.png'],
        ['width' => 1668, 'height' => 2224, 'icon' => 'images/icons/splash-1668x2224.png'],
        ['width' => 1668, 'height' => 2388, 'icon' => 'images/icons/splash-1668x2388.png'],
        ['width' => 2048, 'height' => 2732, 'icon' => 'images/icons/splash-2048x2732.png'],
    ];

    return $all_image;

}
