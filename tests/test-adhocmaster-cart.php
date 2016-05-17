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

    	$cart->address = 'My address';

    	$cart->payer_id = 1;

    	$cart->gateway = 'offline';


    	$raw_clone = clone $cart;

    	// print_r($cart);

    	// echo $cart->debug();

    	echo $cart->save();

    	// echo "after save";

    	// print_r($cart);



    	// echo $cart->ID;

    	// echo "after accessing ID";

    	// print_r($cart);

    	// echo "after doing a dummy update";

    	$new_cart = new Adhocmaster_Cart($cart->ID);

    	// print_r($new_cart);

    	// $new_cart->save();

    	// print_r($new_cart);


    	echo "Adhocmaster_Cart_Test test_save()";

    	print_r($raw_clone);
    	print_r($new_cart);


    	$this->assertEquals($new_cart->ID, $cart->ID);
    	$this->assertEquals($new_cart->message, $raw_clone->message);
    	$this->assertEquals($new_cart->currency_code, $raw_clone->currency_code);
    	$this->assertEquals($new_cart->amount, $raw_clone->amount);
    	$this->assertEquals($new_cart->order_id, $raw_clone->order_id);
    	$this->assertEquals($new_cart->address, $raw_clone->address);
    	$this->assertEquals($new_cart->payer_id, $raw_clone->payer_id);
    	$this->assertEquals($new_cart->gateway, $raw_clone->gateway);

    }
}