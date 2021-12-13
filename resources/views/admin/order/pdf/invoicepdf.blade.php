<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $order->id }}</title>
   <style>
      .clearfix:after{content:"";display:table;clear:both}a{color:#5d6975;text-decoration:underline}body{position:relative;width:auto;height:auto;margin:0 auto;color:#001028;background:#fff;font-family:Arial,sans-serif;font-size:12px;word-wrap: nowrap; font-family:Arial}header{padding:10px 0;margin-bottom:10px;line-height: 1.3;}#logo{text-align:center;margin-bottom:10px;background: {{ $shop_info->theme_color }} !important;}#logo img{/*width:90px*/}#project{float:left}#project span{color:#5d6975;text-align:right;width:64px;margin-right:10px;display:inline-block;font-size:.8em}#company{float:right;}#company div,#project div{white-space:nowrap}h1{border-top:1px solid #5d6975;border-bottom:1px solid #5d6975;color:#5d6975;font-size:1.8em;line-height:1.4em;font-weight:400;text-align:center;margin:0 0 20px 0;}table{width:100%;border-collapse:collapse;border-spacing:0;margin-bottom:20px}table tr:nth-child(2n-1) td{background:#f5f5f5;padding:4px 0px;}table td,table th{text-align:center; line-height: 2em;width: 10%;}table th{padding:5px 20px;color:#5d6975;border-bottom:1px solid #c1ced9;white-space:wrap;font-size: 12px;}footer{color:#5d6975;width:100%;height:30px;bottom:0;border-top:1px solid #c1ced9;padding:8px 0;text-align:center}.discount-price {text-decoration: line-through;color: red;}.text-right{text-align: right;}
    </style>
  </head>
  <body>
  @php 
  $currency = getCurrentCurrency();
  @endphp
    <header class="clearfix">
      <div id="logo">
        <img src="{{ url('images/logo/'.$shop_info->logo_header) }}">
      </div>
      <h1>INVOICE #{{ $order->id }}</h1>
      <div id="company" class="clearfix">
        <div>{{ $shop_info->shop_name }}</div>
        <div>{{ $shop_info->address }}</div>
        <div>{{ $shop_info->phone }}</div>
        <div>{{ $shop_info->email }}</div>
      </div>
      <div id="project">
        <div><span>ORDER NO.</span> {{ $order->id }}</div>
        <div><span>CLIENT</span> {{ $order->customer_name }}</div>
        <div><span>ADDRESS</span> {{ $order->address }}</div>
        <div><span>EMAIL</span>{{ $order->user->email }}</div>
        <div><span>PHONE</span> {{ $order->phone }}</div>
        <div><span>Order DATE</span> {{ $order->order_date }}</div>
        @if($order->customer_delivery_date)
        <div><span style="text-transform: uppercase;">Expected Delivery Time</span> <br> {{ $order->customer_delivery_date }} {{ $order->customer_delivery_time }}</div>
        @endif
      </div>
    </header>
    <main>
      <table >
        <thead>
          <tr >
            <th>IMAGE</th>
            <th>ITEM</th>
            <th>QTY</th>
            <th>UNIT PRICE</th>
            <th>TOTAL PRICE</th>
          </tr>
        </thead>
        <tbody>
        @foreach($order->order_details as $detail)
          <tr>
            <td>
              <img src="{{ url('images/product/feature/'.$detail->product->product_image)}}" alt="Product Image" height="40" width="50" />
            </td>
            <td>{{ $detail->product->product_name }} <small>@if($detail->quantity_unit)  ( {{  $detail->quantity_unit }} ) @endif</small>
              {{ $detail->size ? 'size:'.$detail->size->name : '' }}
              {{ $detail->color ? 'color:'.$detail->color->name : '' }}
            </td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ $detail->selling_price }}@if($detail->unit_discount > 0)<span class="discount-price">{{ $detail->selling_price + $detail->unit_discount }}</span>@endif</td>
            <td>{{ $detail->quantity * $detail->selling_price }}</td>
          </tr>
        @endforeach

        @if($order->status != 3 && count($order->trial_product) > 0) 

        <tr>
          <td colspan="5" class="text-center">Trial Items</td>
        </tr>
        @foreach($order->trial_product as $trial)
        @if($trial->invoiced == 0)
          <tr>
            <td>
              <img src="{{ url('images/product/feature/'.$trial->product->product_image)}}" alt="Product Image" height="40" width="50" />
            </td>
            <td>{{ $trial->product->product_name }} <small>@if($trial->quantity_unit)  ( {{  $trial->quantity_unit }} ) @endif</small>
              {{ $trial->size ? 'size:'.$trial->size->name : '' }}
              {{ $trial->color ? 'color:'.$trial->color->name : '' }}
            </td>
            <td>{{ $trial->quantity }}</td>
            <td>{{ $trial->selling_price }}@if($trial->unit_discount > 0)<span class="discount-price">{{ $trial->selling_price + $trial->unit_discount }}</span>@endif</td>
            <td>Mark as invoice <input type="checkbox"></td>
          </tr>
        @endif
        @endforeach
        @endif
        </tbody>

        <tfoot style="font-weight: bolder;border-top:2px solid #999">
            <tr>
            <td colspan="4" class="text-right">SUBTOTAL</td>
            <td>{{ $order->total_amount }}</td>
          </tr>
          <tr>
            <td colspan="4" class="text-right">SHIPPING</td>
            <td>{{ $order->shipping_amount }}</td>
          </tr>
          @if($order->coupon_discount > 0 )
              <tr>
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="total"> {{ $order->total_amount + $order->shipping_amount }}</td>
              </tr>

              <tr>
                <td colspan="4" class="text-right">(-) DISCOUNT ({{ $order->cupon }})</td>
                <td class="total"> {{ $order->coupon_discount }}</td>
              </tr>

          @endif
          <tr>
            <td colspan="4"  class="text-right" >GRAND TOTAL</td>
            <td>{{ $currency->code }} {{ $order->total_amount + $order->shipping_amount }}</td>
          </tr>
        </tfoot>
      </table>

    </main>
  </body>
</html>