<table>
  <thead>
    <tr>
      <th>OrderID </th>
      <th>Order Date </th>
      <th>Customer</th>
      <th>Phone</th>
      <th>Payment </th>
      <th>Delivery </th>
      <th>Item Qty </th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
  @foreach($product as $value)
    <tr>
      <td>{{ $value->id }}</td>
      <td>{{ $value->order_date }}</td>
      <td>{{ $value->customer_name }}</td>
      <td>{{ $value->phone }}</td>
      <td>
        @if($value->payment_status == 1)
          <span>Paid</span>@else
          <span>Unpaid</span>@endif
      </td>
      <td>
        @if($value->payment_status == 0)
          <span>Pending</span>
        @elseif($value->payment_status == 1)
          <span>On Process</span>
        @elseif($value->payment_status == 2)
        <span>On Delivery</span>
        @else <span>Delivery</span>@endif
      </td>
      <td>{{ $value->total_item }}</td>
      <td>{{ $value->total_amount }}</td>
    </tr>
  @endforeach
  </tbody>
</table>