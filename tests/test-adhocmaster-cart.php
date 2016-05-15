<?php
 
/**
 * An example test case.
 */
class Adhocmaster_Cart_Test extends WP_UnitTestCase {
 
    /**
     * An example test.
     *
     * We just want to make sure that false is still false.
     */
    
    function test_save() {

    	$cart = new Adhocmaster_Cart();

    	$cart->message = "First cart";

    	$cart->currency_code = '$';

    	$cart->amount = 55*100;

    	$cart->order_id = 1;

    	$cart->address = '';


    	print_r($cart);

    	// echo $cart->debug();

    	echo $cart->save();

    	echo "after save";

    	print_r($cart);



    	echo $cart->ID;

    	echo "after accessing ID";

    	print_r($cart);

    }
}