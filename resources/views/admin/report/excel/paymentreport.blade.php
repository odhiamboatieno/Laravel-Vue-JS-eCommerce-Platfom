
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Method </th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
  @foreach($payments as $payment)
    <tr>
      <td>{{ $payment->payment_date }}</td>
      <td>{{ $payment->provider->provider }}</td>
      <td>{{ $payment->amount }}</td>
    </tr>
  @endforeach
  </tbody>
</table>