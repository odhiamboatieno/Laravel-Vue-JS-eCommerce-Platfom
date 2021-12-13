<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>User Order Details</title>
    <style>
      .clearfix:after{content:"";display:table;clear:both}a{color:#5d6975;text-decoration:underline}body{position:relative;width:24cm;min-height:20cm;margin:0 auto;color:#001028;background:#fff;font-family:Arial,sans-serif;font-size:15px;font-family:Arial}header{padding:10px 0;margin-bottom:10px;}#logo{text-align:center;margin-bottom:10px;background: {{ $shop_info->theme_color }} !important;}#logo img{/*width:90px*/}h1{border-top:1px solid #5d6975;border-bottom:1px solid #5d6975;color:#5d6975;font-size:1.8em;line-height:1.4em;font-weight:400;text-align:center;margin:0 0 20px 0;}table{width:100%;border-collapse:collapse;border-spacing:0;margin-bottom:20px}table tr:nth-child(2n-1) td{background:#f5f5f5;padding:4px 0px;}table td,table th{text-align:center; line-height: 2em;}table th{padding:5px 20px;color:#5d6975;border-bottom:1px solid #c1ced9;white-space:nowrap;font-size: 16px;}footer{color:#5d6975;width:100%;height:30px;position:absolute;bottom:0;border-top:1px solid #c1ced9;padding:8px 0;text-align:center}
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{asset('images/logo/'.$shop_info->logo_header )}}">
      </div>
      <h1>Invoice-<strong>#{{ $order_no }}</strong></h1>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th>SL no</th>
            <th>Name</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody><?php $i = 1; ?>
        @foreach($orderdetails as $value)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $value->product->product_name }}</td>
            <td><img src="{{asset('images/product/feature/'.$value->product->product_image)}}" height="40" width="50"></td>
            <td>{{ $value->quantity }}</td>
            <td>{{ $current_currency->symbol }} {{ $value->total_selling_price }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
<script>
  $(document).ready(function(){
    window.print();
    window.close();
  })
</script>
  </body>
</html>