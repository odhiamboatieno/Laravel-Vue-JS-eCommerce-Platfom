<?php
namespace App;

/**
 * this class will have all the status code of database,
 * as a variable, so that any developer can understand what's happening
 * behind the status code 0,1 like that ..
 */
class AllStatic
{

    public static $active   = 1;
    public static $inactive = 0;

    // payment status
    public static $paid     = 1;
    public static $not_paid = 0;

    // delivery status
    public static $pending     = 0;
    public static $processing  = 1;
    public static $on_delivery = 2;
    public static $delivered   = 3;

    // payment method  status

    public static $cash        = 1;
    public static $paypal      = 2;
    public static $stripe      = 3;
    public static $ssl         = 4;
    public static $razor       = 5;
    public static $flutterwave = 6;

}
