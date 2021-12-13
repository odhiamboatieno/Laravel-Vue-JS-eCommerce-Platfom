<?php
 namespace App\Cart;

 use Session;
/**
 * 
 */
class Cart 
{
	
	public static function add($id,$product,$qty,$stock_qty,$qty_unit,$price,$picture)
	{
    
     $cart = Session::get('shopping_cart');
     // checking if shopping cart is fresh 
     if(empty($cart))
     {

     	return "shakhawat";
        $cart = [
        	      'id' => $id,
        	      'product_name' => $product,
        	      'qty'          => $qty,
        	      'price'        => $price,
        	      'qty_unit'     => $qty_unit,
        	      'picture'      => $picture,
              ];

         Session::put('shopping_cart',$cart);

         return "added";      
      }

         $new_item =  [
        	      'id'           => $id,
        	      'product_name' => $product,
        	      'qty'          => $qty,
        	      'price'        => $price,
        	      'qty_unit'     => $qty_unit,
        	      'picture'      => $picture,
              ];
         // array_merge(array1)
         $merged_array = array_merge($cart, $new_item);

         Session::put('shopping_cart',$merged_array);

         // or similer 

         // Session::push('shopping_cart',$new_item);


              return "added";

      

	}

	public static function content()
	{
      
      return Session::get('shopping_cart');

	}

	public static function update()
	{

	}




}