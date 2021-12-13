<table>
	<thead>
	  <tr>
	    <th>Customer</th>
	    <th>Phone</th>
	    <th>Email </th>
	    <th>Address </th>
	    <th>Point </th>
	    <th>Register At</th>
	  </tr>
	</thead>
	<tbody>
	@foreach($customers as $value)
	  <tr>
	    <td>{{ $value->name }}</td>
	    <td>{{ $value->phone }}</td>
	    <td>{{ $value->email }}</td>
	    <td>{{ $value->address }}</td>
	    <td>{{ $value->points }}</td>
	    <td>{{ date('M j, Y',strtotime($value->created_at)) }}</td>
	  </tr>
	@endforeach
	</tbody>
</table>