<?php 

# The class provides public method:
#   - submit ( string $t_type, array @t_request )
#     return hash with transaction response or error message
#
#  Copyright 2013 eComCharge Ltd alexander@ecomcharge.com
#
#  License: OSL 3.0

class Gateway {
  protected $_shop_id;
  protected $_secret_key;
  protected $_host;
  protected $_debug;

  public function __construct ($shop_id, $shop_key, $host, $debug = false) {
    $this->_shop_id	= $shop_id;
    $this->_shop_key	= $shop_key;
    $this->_host	= $host;
    $this->_debug	= $debug;
  }

  public function submit ($t_type, $t_request) {
    $process = curl_init($this->_host);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-type: application/json'));
    curl_setopt($process, CURLOPT_URL, $this->_host.'/'.$t_type); 
    curl_setopt($process, CURLOPT_USERPWD, $this->_shop_id . ":" . $this->_shop_key);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($t_request) );
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($process);

    curl_close($process);

    return json_decode($response);
  }

  public function info ($t_response) {
    if ($t_response->{'transaction'}) {
      echo " --------------------------------------\n";
      echo "\n\t\t Your transaction data \n";
      echo "Type:\t\t ".$t_response->{'transaction'}->{'type'}."\n";
      echo "Status:\t\t ".$t_response->{'transaction'}->{'status'}."\n";
      echo "UID:\t\t ".$t_response->{'transaction'}->{'uid'}."\n";
      echo "Message:\t ".$t_response->{'transaction'}->{'message'}."\n";
      echo " --------------------------------------\n";
    }else{
      echo "\n Request error\n";
      echo $t_response->{'response'}->{'message'};
    }


  }
  
}
?>