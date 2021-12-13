<table>
  <thead>
    <tr>
      <th>P-Code </th>
      <th>Product </th>
      <th>Category</th>
      <th>Sub Category</th>
      <th>Sub Sub Category</th>
      <th>Brand </th>
      <th>Total Sale Qty</th>
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
    <tr>
      <td colspan="6">Total =</td>
      <td>{{ $total_sold_qty }}</td>
      <td>{{ $total_buying_amount }} {{ getCurrentCurrency()->code }}</td>
      <td>{{ $total_sales_amount }} {{ getCurrentCurrency()->code }}</td>
      <td>{{ $total_sales_amount - $total_buying_amount }} {{ getCurrentCurrency()->code }}</td>
    </tr>
  </tbody>
</table>