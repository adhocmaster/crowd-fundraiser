<?php

/**
 * Blank Class
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 * @author     AdhocMaster <adhocmaster@live.com>
 */
class Adhocmaster_Paypal {

    /**
     * Add your plugin or theme text domain name here.
     *
     * @since    1.0.0
     */
    
    const TEXT_DOMAIN = CROWD_FUNDRAISER_TEXT_DOMAIN;

    /**
     * Plugin URL needed for IPN?
     *
     * @since    1.0.0
     */
    
    const PLUGIN_URL = CROWD_FUNDRAISER_URL ;

    /**
     * Short Description. (use period). pass data to array if you don't have any cart
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function get_form_classic( $cart = array(), $notification_url, $array = array() )
    {
        //EchoPre($array) ;


        $array['business']  = get_option( 'PAYPAL_BUSINESS_ACCOUNT', false );

        if ( false === $array['business'] ) {

            return new WP_Error( 'error', __( 'Paypal account not added', static::TEXT_DOMAIN ) );

        }

        $sandbox = get_option( 'PAYPAL_SANDBOX', 1 );

        if( $sandbox ) {

            $sandbox ='.sandbox';

        } else {

            $sandbox='';
        }

        $form="<form action='https://www$sandbox.paypal.com/cgi-bin/webscr' method='post'>
                     <input type='hidden' name='cmd' value='_xclick'>";
        
        if( ! empty( $cart ) )
        {
            //make array from cart
            $array['currency_code'] = $cart->currency_code;   

            $array['invoice']       = get_option( 'PAYPAL_INVOICE_PREFIX', 'paypal' ) . '-' . $cart->ID;

            $array['amount']        = $cart->get_amount();

            $array['item_name']     = sprintf( __( "Invoice no #%d", static::TEXT_DOMAIN ), $cart->ID );

            $array['shopping_url']  = $notification_url;

            $array['no_note']       = 1;

            $array['return']        = $notification_url . '?return_from_gateway=true&cart_id=' . $cart->ID;

            $array['cancel_return'] = $notification_url . '?cancel_from_gateway=true&cart_id=' . $cart->ID;

            $array['notify_url']    = $notification_url . '?notification=paypal';

            $array['tax']           = 0;

            $array['tax_rate']      = 0;

        }
        var_dump($array);
        
        if ( empty( $array ) ) {

            new WP_Error( 'No data given for the paypal button', __( 'Paypal account not added', static::TEXT_DOMAIN ) );

        }
        
        foreach ( $array as $key => $val )
        {

            $form.="<input type='hidden' name='$key' value='$val'>";

        }
        
        if(  !array_key_exists( 'shipping', $array ) && ! array_key_exists( 'shipping2', $array ) && ! array_key_exists( 'no_shipping' , $array ) ) {

            $form.="<input type='hidden' name='no_shipping' value='1'>";

        }
        
         $form.="    <input type='submit' name='submit' value='". __( "Pay with papal", static::TEXT_DOMAIN ) ."'>
                     <img alt='' border='0' src='https://www$sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>
                     </form>";

        return $form;
    }


       public static function GetPaypalFeed($cart_id){             
            // global $PAYPAL_BUSINESS_ACCOUNT,$PAYPAL_RETURN_URL,$PAYPAL_CANCEL_URL,$PAYPAL_CURRENCY_CODE,$IMAGE_URL,$BASE_URL,$PAYPAL_INVOICE_PREFIX;
            // $cart = ICodeCart::GetInfo($cart_id);

            // if(!isset($PAYPAL_BUSINESS_ACCOUNT)|| $PAYPAL_BUSINESS_ACCOUNT=='')
            //      error_log("paypal business account not found");
            // if(!isset($PAYPAL_INVOICE_PREFIX) || $PAYPAL_INVOICE_PREFIX=='')
            //      error_log("paypal prefix not found");       
            // $invoiceId=$PAYPAL_INVOICE_PREFIX."-".$cart_id;


            // $paypalFeed = array('invoice'=>$invoiceId,
            //                     'currency_code'=>$cart->currency_code'],         //$cart->currency_code'],
            //                     'amount'=>$cart->amount'],
            //                     'business'=>$PAYPAL_BUSINESS_ACCOUNT,
            //                     'item_name'=>"Invoice #$cart_id: Voucher of amount ".$cart->amount'],
            //                     'shopping_url'=>$BASE_URL,
            //                     'no_note'=>1,
            //                     'return'=>$PAYPAL_RETURN_URL."cartId=$cart_id",
            //                     'cancel_ return'=>$PAYPAL_CANCEL_URL,
            //                     'image'=>$IMAGE_URL.'paypal.gif'
            //                     );
            // return $paypalFeed;  
                                
       }
    public static function IPN() //the IPN notifier calls it   invoice id prefix-323
    {

        //verify if it's a IPN url

        // echo "I am in IPN";

        $notification_type = get_query_var('notification', '');

        // var_dump($notification_type);

        if( $notification_type != 'paypal' ) {

            return;

        }


        $PAYMENT_NOTIFICATION_EMAIL = get_option( 'PAYMENT_NOTIFICATION_EMAIL', get_option( 'admin_email' ) );
        $PAYPAL_BUSINESS_ACCOUNT    = get_option( 'PAYPAL_BUSINESS_ACCOUNT', '' );
        $PAYPAL_INVOICE_PREFIX      = get_option( 'PAYPAL_INVOICE_PREFIX', 'paypal' );
        // The majority of the following code is a direct copy of the example code specified on the Paypal site.

        // Paypal POSTs HTML FORM variables to this page
        // we must post all the variables back to paypal exactly unchanged and add an extra parameter cmd with value _notify-validate

        // initialise a variable with the requried cmd parameter

        $logTransactions = get_option( 'PAYPAL_LOG_TRANSACTIONS', 0 );

        // var_dump($logTransactions);

        if ( get_option( 'PAYPAL_LOG_TRANSACTIONS', 0 ) == true )
           $logError=true;
        else
           $logError=false;

        if(!isset($PAYPAL_BUSINESS_ACCOUNT)|| $PAYPAL_BUSINESS_ACCOUNT=='')
           error_log("paypal business account not found");

        if(!isset($PAYPAL_INVOICE_PREFIX) || $PAYPAL_INVOICE_PREFIX=='')
           error_log("paypal prefix not found");

        $req = 'cmd=_notify-validate';

        // var_dump($_POST);
        // var_dump($logError);

        // go through each of the POSTed vars and add them to the variable
        foreach ($_POST as $key => $value) {

            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";

        }
        $header = '';
        // post back to PayPal system to validate
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        // In a live application send it back to www.paypal.com
        // but during development you will want to uswe the paypal sandbox

        // comment out one of the following lines
                      
        if( get_option( 'PAYPAL_SANDBOX', 1 ) )
           $fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
        else
           $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

        // or use port 443 for an SSL connection
        //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);


        if ( !$fp ) {
        // HTTP ERROR
          error_log('Could not connect to paypal');

        }
        else
        {     
            if($logError)
                error_log( print_r($_POST,true) );

            fputs ($fp, $header . $req);

            while (!feof($fp))
            {
               $res = fgets ($fp, 1024);
               if (strcmp ($res, "VERIFIED") == 0)
               {

                    if($logError)
                        error_log('Verified Paypal Transaction:');
                    // assign posted variables to local variables
                    // the actual variables POSTed will vary depending on your application.
                    // there are a huge number of possible variables that can be used. See the paypal documentation.

                    // the ones shown here are what is needed for a simple purchase
                    // a "custom" variable is available for you to pass whatever you want in it. 
                    // if you have many complex variables to pass it is possible to use session variables to pass them.

                    $invoiceId = $_POST['invoice'];
                    $invoiceArr=explode( "-", $invoiceId );

                    if($invoiceArr[0]!=$PAYPAL_INVOICE_PREFIX) 
                     error_log("paypal prefix $PAYPAL_INVOICE_PREFIX didn't match with {$invoiceArr[0]}");
                     
                    $cart_id=$invoiceArr[1];
                    $payment_status = $_POST['payment_status'];
                    $payment_amount = floatval($_POST['mc_gross']);         //full amount of payment. payment_gross in US
                    $payment_currency = $_POST['mc_currency'];
                    $txn_id = $_POST['txn_id'];                   //unique transaction id
                    $receiver_email = $_POST['receiver_email'];
                    $payer_email = $_POST['payer_email'];

                    // use the above params to look up what the price of "item_name" should be.

                    $cart= new Adhocmaster_Cart( $cart_id );

                    if ( $cart->status == 'payment_received' )
                     error_log("Payment already received for cart # $cart_id before");

                    if ( $logError )
                     error_log(print_r($cart,true));

                    //$amount_they_should_have_paid = lookup_price($item_name); // you need to create this code to find out what the price for the item they bought really is so that you can check it against what they have paid. This is an anti hacker check.

                    // the next part is also very important from a security point of view
                    // you must check at the least the following...

                    if( 
                        ( $payment_status == 'Completed' ) &&   //payment_status = Completed
                        ( $receiver_email == $PAYPAL_BUSINESS_ACCOUNT ) &&   // receiver_email is same as your account email
                        ( strcmp($txn_id, $cart->txn_id) != 0 ) //txn_id isn't same as previous to stop duplicate payments. You will need to write a function to do this check.
                    )
                    {  

                       if($logError)
                       {
                           error_log("all conditions ok. calling cart->accept_payment($payment_amount,'paypal', $currency_code, $txn_id)");
                       }

                       $errors = $cart->accept_payment( $payment_amount, 'paypal', $currency_code, $txn_id );

                       if( !is_wp_error($errors) )
                       {

                           $mail_Subject = "completed status received from paypal for invoice $invoiceId";
                           $mail_Body = "completed:  \n\nThe Invoice ID number is: $invoiceId";                                                      
                       }
                       else
                       {

                           $error_messages = implode( '\n', $errors->get_error_messages() );
                           $mail_Subject = "Error: completed status received from paypal for invoice $invoiceId";
                           $mail_Body = "Errors: {$error_messages} \n completed:  \n\nThe Invoice ID number is: $invoiceId but the invoice was not updated. Please update it manually";

                       }

                       // ICodeTools::ICodeMail($PAYMENT_NOTIFICATION_EMAIL, get_option( 'SYSTEM_EMAIL_ADDRESS' ), $mail_Subject, $mail_Body,get_option( 'WEBMASTER_EMAIL' ))

                       wp_mail( $PAYMENT_NOTIFICATION_EMAIL, $mail_Subject, $mail_Body );

                    }
                    else
                    {
                        //             

                        if( $logError )
                            error_log('Potential fraud attack');

                        if( ! ( $payment_status == 'Completed' ) )
                            error_log('Payment status not completed');

                        if( ! ( $receiver_email == $PAYPAL_BUSINESS_ACCOUNT ) )       
                            error_log("receiver email is not business account:$PAYPAL_BUSINESS_ACCOUNT");

                        if( ! ( $payment_amount == floatval( $cart->get_amount() ) ) )
                            error_log('amount doesn\'t match');

                        if( ! (strcmp( $txn_id, $cart->txn_id)==0 ) )
                            error_log('transaction id same');

                        if( ! ( $payment_currency == $cart->currency_code ) )
                            error_log('currency codes doesn\'t match');
                        //
                        // we will send an email to say that something went wrong
                        $mail_Subject = "PayPal IPN status not completed or security check fail";
                        $mail_Body = "Something wrong. \n\nThe Invoice ID number is: $invoiceId \n\nThe transaction ID number is: $txn_id \n\n Payment status = $payment_status \n\n Payment amount = $payment_amount".print_r($_POST,true);;

                        // ICodeTools::ICodeMail($PAYMENT_NOTIFICATION_EMAIL, get_option( 'SYSTEM_EMAIL_ADDRESS' ), $mail_Subject, $mail_Body,get_option( 'WEBMASTER_EMAIL' ));
                        wp_mail( $PAYMENT_NOTIFICATION_EMAIL, $mail_Subject, $mail_Body );

                    }
               }
               else if (strcmp ($res, "INVALID") == 0)
               {
                     //
                     // Paypal didnt like what we sent. If you start getting these after system was working ok in the past, check if Paypal has altered its IPN format
                     //   
                   
                     if($logError)
                        error_log('INVALID for cartId'.$cart_id);
                     $mail_Subject = "PayPal - Invalid IPN ";

                     $invoiceId = $_POST['invoice'];
                     $txn_id = $_POST['txn_id'];                   //unique transaction id
                     $mail_Body = "We have had an INVALID response.  \n\nThe Invoice ID number is: $invoiceId \n\nThe transaction ID number is: $txn_id ";

                     // ICodeTools::ICodeMail($PAYMENT_NOTIFICATION_EMAIL, get_option( 'SYSTEM_EMAIL_ADDRESS'), $mail_Subject, $mail_Body,get_option( 'WEBMASTER_EMAIL' ));


                     wp_mail( $PAYMENT_NOTIFICATION_EMAIL, $mail_Subject, $mail_Body );

               }
               else
               {  
                     error_log('StrangeData from paypal server: '.$res);
               }
            } //end of while
            fclose ($fp);
        }

        exit();

    }

}

