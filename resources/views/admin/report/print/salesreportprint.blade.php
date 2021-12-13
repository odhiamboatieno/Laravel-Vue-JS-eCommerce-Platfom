<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
      .clearfix:after{content:"";display:table;clear:both}a{color:#5d6975;text-decoration:underline}body{position:relative;width:auto;height:auto;margin:0 auto;color:#001028;background:#fff;font-family:Arial,sans-serif;font-size:12px;font-family:Arial}header{padding:10px 0;margin-bottom:10px;line-height: 1.3;}#logo{text-align:center;margin-bottom:10px;background: {{ $shop_info->theme_color }} !important;}#logo img{/*width:90px*/}#project{float:left}#project span{color:#5d6975;text-align:right;width:64px;margin-right:10px;display:inline-block;font-size:.8em}#company{float:right;}#company div,#project div{white-space:nowrap}h1{border-top:1px solid #5d6975;border-bottom:1px solid #5d6975;color:#5d6975;font-size:1.8em;line-height:1.4em;font-weight:400;text-align:center;margin:0 0 20px 0;}table{width:100%;border-collapse:collapse;border-spacing:0;margin-bottom:20px}table tr:nth-child(2n-1) td{background:#f5f5f5;padding:4px 0px;}table td,table th{text-align:center; line-height: 2em;width: 10%;}table th{padding:5px 20px;color:#5d6975;border-bottom:1px solid #c1ced9;white-space:wrap;font-size: 12px;}footer{color:#5d6975;width:100%;height:30px;bottom:0;border-top:1px solid #c1ced9;padding:8px 0;text-align:center}
    </style>
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js')}}"></script>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{asset('images/logo/'.$shop_info->logo_header )}}">
      </div>
      <h1>Sales Report</h1>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th>P-Code </th>
            <th>Product </th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>SubSub Category</th>
            <th>Brand </th>
            <th>Sale Qty</th>
            <th>Total Buying Amount</th>
            <th>Total Sales Amount</th>
            <th>Profit</th>
          </tr>
        </thead>
        <tbody>
          @php
              $total_sold_qty = 0;
              $total_buying_amount = 0;
              $total_sales_amount = 0;
          @endphp
        @foreach($product as $value)
          <tr>
            <td>{{ $value->product->id }}</td>
            <td>{{ $value->product->product_name }}</td>
            <td>{{ $value->category->category_name }}</td>
            <td>{{ $value->sub_category->sub_category_name }}</td>
            <td>{{ $value->sub_sub_category->sub_sub_category_name }}</td>
            <td>{{ $value->brand->brand_name }}</td>
            <td>{{ $value->total_sold_qty }}</td>
            <td>{{ $value->total_buying_amount }}</td>
            <td>{{ $value->total_sales_amount }}</td>
            <td>{{ $value->total_sales_amount - $value->total_buying_amount }}</td>
          </tr>
          @php
              $total_sold_qty += $value->total_sold_qty;
              $total_buying_amount += $value->total_buying_amount;
              $total_sales_amount += $value->total_sales_amount;
          @endphp
        @endforeach
          <tr style="font-style: bold; border-top: 1px solid #000;">
            <td colspan="6" style="text-align: right;"><strong>Total =</strong></td>
            <td><strong>{{ $total_sold_qty }}</strong></td>
            <td><strong>{{ $total_buying_amount }} {{ getCurrentCurrency()->code }}</strong></td>
            <td><strong>{{ $total_sales_amount }} {{ getCurrentCurrency()->code }}</strong></td>
            <td><strong>{{ $total_sales_amount - $total_buying_amount }} {{ getCurrentCurrency()->code }}</td>
          </tr>
        </tbody>
      </table>
    </main>
<script>

    window.print();
 
</script>
  </body>
</html>