<!DOCTYPE html>
<html>
<head>
  <title>Email From {{ $shop_info->shop_name }}</title>
  <style>
    *{ margin: auto; padding:0; }
    table{margin: 20px auto; text-align: center;height: auto; width: 850px;border-style: hidden;border-radius: 4px;font-family:serif; font-size: 18px;line-height: 1.5em;background: #fdf7f7;}
  </style>
</head>
<body>
<table>
  <tr style="background-color: {{ $shop_info->theme_color }};">
    <td style="padding: 9px 0px;"><a href="#">
            <img src="{{asset('images/logo/'.$shop_info->logo_header )}}"  alt="{{ $shop_info->shop_name }}">
          </a></td>
  </tr>
  <tr style="height: auto;">
    
    <td>
      <p style="padding: 21px 0px 7px 0px;text-align: left;border-bottom: 2px solid #e6d8d8;margin: 0px 18px;"><strong>Subject : {{ $subject }}</strong></p>
      <p style="margin: 7px 0px 0px 18px;text-align: left;font-weight:500;padding: 0px 10px 0px 1px;">{!! $text_body !!}</p>
    </td>
  </tr>
  <tr style="height: auto;text-align: left;">
    <td style="padding: 20px 5px;">
      <address  style="margin:10px 16px;">
       {{ $shop_info->shop_name }}<br>
              Written by <a href="mailto:{{ $shop_info->email }}">{{ $shop_info->email }}</a>.<br>
              cell: {{ $shop_info->phone }}<br>
              {{ $shop_info->address }}<br>
      </address>
    </td>
  </tr>
  <tr style="background-color: {{ $shop_info->theme_color }};height: 50px;margin-top: 20px;">
    <td><a href="{{ url('/') }}" style="text-decoration: none;color: #fff;">Shop Now : {{ $shop_info->shop_name }}</a></td>
  </tr>
</table>
</body>
</html>