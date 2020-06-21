<?php

/* import checksum generation utility */
require_once("PaytmChecksum.php");

/* string we need to verify against checksum */  
$body = "{"\mid\":"\njRJZH34728156468179\","\orderId\":"\ORDERID_98765\"}";

/* checksum that we need to verify */
$paytmChecksum = "CHECKSUM_VALUE";

/**
* Verify checksum
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$isVerifySignature = PaytmChecksum::verifySignature($body, 'KZBwwSADhoz_dRTt', $paytmChecksum);
if($isVerifySignature) {
	echo "Checksum Matched";
} else {
	echo "Checksum Mismatched";
}


?>