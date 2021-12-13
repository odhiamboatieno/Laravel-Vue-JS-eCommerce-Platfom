<!DOCTYPE html>
<html>
<head>
    <title>Email From {{ $shop_info->shop_name }}</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <style type="text/css">
      .clearfix:after{content:"";display:table;clear:both}a{color:#5d6975;text-decoration:underline}body{position:relative;width:20cm;min-height:20cm;margin:0 auto;color:#001028;background:#fff;font-family:Arial,sans-serif;font-size:15px;font-family:Arial}header{padding:10px 0;margin-bottom:10px;line-height: 1.3;}#logo{text-align:center;margin-bottom:10px;background: {{ $shop_info->theme_color }} !important;}#logo img{/*width:90px*/}#project{float:left}#project span{color:#5d6975;text-align:right;width:64px;margin-right:10px;display:inline-block;font-size:.8em}#company{float:right;}#company div,#project div{white-space:nowrap}h1{border-top:1px solid #5d6975;border-bottom:1px solid #5d6975;color:#5d6975;font-size:1.8em;line-height:1.4em;font-weight:400;text-align:center;margin:0 0 20px 0;}table{width:100%;border-collapse:collapse;border-spacing:0;margin-bottom:20px}table tr:nth-child(2n-1) td{background:#f5f5f5;padding:4px 0px;}table td,table th{text-align:center; line-height: 2em;}table th{padding:5px 20px;color:#5d6975;border-bottom:1px solid #c1ced9;white-space:nowrap;font-size: 16px;}table td.total{font-size:1.2em; line-height: 1.4em;}table td.grand{border-top:1px solid #5d6975;}table td.unit{text-align: right;}footer{color:#5d6975;width:100%;height:30px;position:absolute;bottom:0;border-top:1px solid #c1ced9;padding:8px 0;text-align:center}.discount-price {text-decoration: line-through;color: red;}
    </style>
</head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <a href="{{ url('/') }}">
          <img src="{{asset('images/logo/'.$shop_info->logo_header )}}">
        </a>
      </div>
      <h1>Invoice<strong>#{{ $data->id }}</strong></h1>
    </header>
    <main>
    <div style="margin: 0px 20px;"><p> {!! $sms !!}</p></div>
      <table>
        <thead>
          <tr>
            <th>PID </th>
            <th>Name </th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
        @foreach($orderdetails as $value)
          <tr>
            <td>{{ $value->product_id }}</td>
            <td>{{ $value->product->product_name }}</td>
            <td>{{ $value->quantity }}</td>
            <td>{{ $value->selling_price }} @if($value->unit_discount > 0)<span class="discount-price">{{ $value->selling_price + $value->unit_discount }}</span>@endif</td>
            <td>{{ $value->total_selling_price }}</td>
          </tr>
        @endforeach
          <tr>
            <td colspan="4" class="unit">SUBTOTAL</td>
            <td class="total">{{ $data->total_amount }}</td>
          </tr>
          <tr>
            <td colspan="4" class="unit">SHIPPING</td>
            <td class="total">{{ $data->shipping_amount }}</td>
          </tr>
          <tr>
            <td colspan="4" class="unit">DISCOUNT</td>
            <td class="total">{{ $data->discount }}</td>
          </tr>
          <tr>
            <td colspan="4" class="grand total unit">GRAND TOTAL</td>
            <td class="grand total">{{ getCurrentCurrency()->code }} {{ ($data->total_amount + $data->shipping_amount) - $data->discount }}</td>
          </tr>
        </tbody>
      </table>
    </main>
    <footer>
      please visit <a href="{{ url('/') }}">{{ url('/') }}</a> and save your time by shopping online 
    </footer>
  </body>
</html>