<table>
  <thead>
    <tr>
      <th>P-Code </th>
      <th>Product </th>
      <th>Brand </th>
      <th>Category</th>
      <th>Sub Category</th>
      <th>Sub Sub Category</th>
      <th>Opening Stock</th>
      <th>Current Stock</th>
    </tr>
  </thead>
  <tbody>
  @foreach($product as $value)
    <tr>
      <td>{{ $value->id }}</td>
      <td>{{ $value->product_name }}</td>
      <td>{{ $value->brand->brand_name }}</td>
      <td>{{ $value->category->category_name }}</td>
      <td>{{ $value->sub_category->sub_category_name }}</td>
      <td>{{ $value->sub_sub_category->sub_sub_category_name }}</td>
      <td>{{ $value->stock_quantity }}</td>
      <td>{{ $value->current_quantity }}</td>
    </tr>
  @endforeach
  </tbody>
</table>