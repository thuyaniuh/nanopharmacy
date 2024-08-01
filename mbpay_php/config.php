<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$url_create = "https://api-sandbox.mbbank.com.vn/private/ms/pg-paygate/paygate/v2/create-order";
$url_refund = "https://api-sandbox.mbbank.com.vn/private/ms/pg-paygate/paygate/refund/single";
$merchant_id = "103890";
$access_code = "KTDIXRKWRT";
$hashSecret = "a50898884c5b192a2fdbbf7f4d9d52e8";
$return_url = URL."mbpay_php/mbpay_return/";
$cancel_url = URL."mbpay_php/mbpay_cancel/";
$ipn_url = URL."mbpay_php/mbpay_ipn/";
$ipn_url = "https://api-sandbox.mbbank.com.vn/integration-paygate-mganano1/v1.0/payIpn";
$url_detail = "https://api-sandbox.mbbank.com.vn/private/ms/pg-paygate/paygate/detail";

?>
