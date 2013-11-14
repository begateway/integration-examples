<?php
require 'gateway.php';

// Initilization params
$shop_id	= '[ YOUR SHOP ID]' ;
$shop_key	= '[ YOUR SHOP KEY]';
$gateway_url	= "https://processing.ecomcharge.com/transactions";



$payment_request = array(
		  "request" => array (
		      "test"            => true,
		      "amount"		=> 100,
		      "currency"	=> "USD",
		      "description"	=> "Test transaction",
		      "tracking_id"	=> "your_uniq_number",
		      "billing_address" => array (
						  "first_name"	=> "John",
						  "last_name"	=> "Doe",
						  "country"	=> "US",
						  "city"	=> "Denver",
						  "state"	=> "CO",
						  "zip"		=> "96002",
						  "address"	=> "1st Street"
							  ),
		      "credit_card" => array (
					      "number"			=> "4200000000000000",
					      "verification_value"	=> "123",
					      "holder"			=> "John Doe",
					      "exp_month"		=> "05",
					      "exp_year"		=> "2020"
					      ),
		      "customer" => array (
					   "ip"		=> "127.0.0.1",
					   "email"	=> "john@example.com"
					   )
				      )
			  );


// Payment request
$client    = new Gateway($shop_id, $shop_key, $gateway_url);
$payment_response  = $client->submit('payments', $payment_request);
$client->info($payment_response);



// Refund request
if ($payment_response->{'transaction'} && $payment_response->{'transaction'}->{'status'} == 'successful') {
  $payment_uid = $payment_response->{'transaction'}->{'uid'};  
}

if ($payment_uid) {
  $refund_request = array(
			  "request" => array (
					      "amount"     => 100,
					      "parent_uid" => $payment_uid,
					      "reason"     => "Test reason"
					      )
			  );

  $refund_response  = $client->submit('refunds', $refund_request);
  $client->info($refund_response);
}



?>