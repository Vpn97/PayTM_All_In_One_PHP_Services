<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("PaytmChecksum.php");

$paytmParams = array();

$orderid = $_GET['ORDER_ID'];
$amount = $_GET['AMOUNT'];
$MerchantKey = 'O514teDlmoyaUs9L';
$MID=$_GET['MID'];


if ( isset( $MID ) ){

    $enablePaymentMode=array(
        "mode"    => "UPI",
        "channels" => "UPIPUSH",
    );

    $paytmParams["body"] = array(
        "requestType"   => "Payment",
        "mid"           =>  $MID,
        "websiteName"   => "DEFAULT",
        "orderId"       => $orderid ,
        "callbackUrl"   => "https://merchant.com/callback",
        "txnAmount"     => array(
            "value"     => $amount,
            "currency"  => "INR",
        ),
        "userInfo"      => array(
            "custId"    => "CUST_001",
        ),
    );

    /*
    * Generate checksum by parameters we have in body
    * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
    */
    $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $MerchantKey);

    $paytmParams["head"] = array(
        "signature"	=> $checksum
    );

    $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

    /* for Staging */
    //$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=$MID&orderId=$orderid";

    /* for Production */
    $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=$MID&orderId=$orderid";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
    $response = curl_exec($ch);
    print_r($response);
}