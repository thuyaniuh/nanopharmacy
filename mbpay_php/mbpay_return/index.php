<?php
require_once(ABSPATH."mbpay_php/config.php");  
require_once(ABSPATH.'api/Wallet.php' );
require_once(ABSPATH.'api/Sale.php' );

	
$mac = $_GET['mac'];
$mac_type = $_GET['mac_type'];

$inputData = array();
$returnData = array();
$startTime = date("YmdHis");
$session_id = $startTime;
try {
	foreach ($_GET as $key => $value) {
		$inputData[$key] = $value;
		if($key == "session_id")
		{
			$session_id = $value;
		}
	}
	$cache_file = ABSPATH."log/mbpay/return_".$session_id.".txt";
	$encodedString = json_encode($inputData);
	file_put_contents($cache_file, $encodedString);
	
	unset($inputData['mac']);
	unset($inputData['mac_type']);
	ksort($inputData);
	$i = 0;
	$hashData = "";
	foreach ($inputData as $key => $value) {
		if($value == "")
		{
			continue;
		}
		if($hashData != "")
		{
			$hashData = $hashData."&";
		}
		$hashData .= $key . "=" .$value;
	}
	
	
	if($mac_type == "SHA256")
	{
		 $secureHash = strtoupper(hash('sha256', $hashSecret.$hashData));
	}else{
		$secureHash = strtoupper(md5($hashSecret.$hashData));
	}		
	
	?>
	<div class="container">
		<div class="header clearfix">
			<h3 class="text-muted"><?php META_TITLE;?></h3>
		</div>
		<div class="table-responsive">
			<div class="form-group">
				<label >Mã đơn hàng:</label>

				<label><?php echo $_GET['pg_order_info'] ?></label>
			</div>    
			<div class="form-group">

				<label >Số tiền:</label>
				<label><?php echo $_GET['pg_amount'] ?></label>
			</div>  
			<div class="form-group">
				<label >Nội dung thanh toán:</label>
				<label><?php echo $_GET['pg_order_reference'] ?></label>
			</div> 
			<div class="form-group">
				<label >Mã phản hồi (vnp_ResponseCode):</label>
				<label><?php echo $_GET['error_code'] ?></label>
			</div> 
			
			<div class="form-group">
				<label >Phương thức thanh toán:</label>
				<label><?php echo $_GET['pg_payment_method'] ?></label>
			</div> 
			
			<div class="form-group">
				<label >Kết quả:</label>
				<label>
					<?php
					if ($secureHash == $mac) {
						if (isset($_GET['error_code']) && $_GET['error_code'] == '00') {
							echo "<span style='color:blue'>GD Thanh cong</span>";
							?>
							<script>
								setTimeout(function(){location.href="<?php echo URL;?>paid"} , 5000);  
							</script>
							<?php
						} else {
							echo "<span style='color:red'>GD Khong thanh cong</span>";
							?>
							<script>
								setTimeout(function(){location.href="<?php echo URL;?>checkout"} , 5000);  
							</script>
							<?php
						}
					} else {
						echo "<span style='color:red'>Chu ky khong hop le</span>";
					}
					?>

				</label>
			</div> 
		</div>
		<p>
			&nbsp;
		</p>
		<footer class="footer">
			   <p>&copy; <?php echo META_TITLE;?> <?php echo date('Y')?></p>
		</footer>
	</div> 
	<?php

} catch (Exception $e) {
    
}
?>