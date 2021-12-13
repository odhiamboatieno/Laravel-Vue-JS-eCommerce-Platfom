<!DOCTYPE html>
<html>
<head>
  <title>Coupon</title>
  <style>
    *{ margin: auto; padding:0; }
    table{margin: 20px auto; text-align: center;height: auto; width: 850px;border-style: hidden;border-radius: 4px;font-family:serif; font-size: 18px;line-height: 1.5em;background: #fdf7f7;}
  </style>
</head>
<body>
@php 
    $currency = getCurrentCurrency();
@endphp 
<table>
  <tr style="background-color: {{ $shop_info->theme_color }};">
    <td style="padding: 8px 0px;">
      <a href="{{ url('/') }}">
            <img src="{{asset('images/logo/'.$shop_info->logo_header )}}"  alt="{{ $shop_info->shop_name }}">
          </a></td>
  </tr>
  <tr style="height:auto;">
    <td>
      <p style="width:240px;padding: 10px 20px;border-bottom: 2px solid #888888;margin-top: 20px;font-size: 1.3em;">Welcome</p>
            <p style="margin: 20px 25px;font-size: 1.1em;">Apply <span class="theme-color">{{ $coupon->coupon_code }}</span> during checkout to Get 
                {{ $coupon->amount }} 
                @if($coupon->amount_type == 1)
                <span>{{ $currency->symbol }}</span>
                @else
                <span>% Up To {{ $coupon->max_amount_limit }}{{ $currency->symbol }}</span>
                @endif
                Discount</p>

          <a href="{{ url('/') }}" style="padding: 12px 30px;text-decoration:none;background-color: #dff0d8;border:1px solid #d4cccc;border-radius: 5px;font-size: 1.1em;">Click For Shopping</a>

          <p style="margin: 20px 25px;font-size: 1.1em;">Your Coupon Expired Date : {{ $exp_date }}</p>
 
    </td>
  </tr>
  <tr style="background-color: {{ $shop_info->theme_color }}; height: 50px;margin-top: 20px;">
    <td><a href="{{ url('/') }}" style="text-decoration: none;color: #fff;">Shop Now : {{ $shop_info->shop_name }}</a></td>
  </tr>
</table>
</body>
</html>