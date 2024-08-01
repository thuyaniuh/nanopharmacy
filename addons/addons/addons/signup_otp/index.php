<?php
$msg = $appSession->getTier()->createMessage();
$id = "";
$phone = "";
if(isset($_REQUEST['id']))
{
	$id = $_REQUEST['id'];
	$verify_id = $appSession->getTool()->decrypt($id, true);
	$sql = "SELECT d2.phone FROM res_user_verification d1 LEFT OUTER JOIN res_user d2 ON (d1.create_uid = d2.id) WHERE d1.id ='".$verify_id."'";
	$msg->add("query", $sql);
	$phone = $appSession->getTier()->getValue($msg);
	$len = strlen($phone);
	if($len>4)
	{
		$phone = $appSession->getTool()->substring($phone, $len-4);
	}
	for($i =0; $i<$len-4; $i++)
	{
		$phone = "*".$phone;
	}
	
	
}

?>
<!-- Breadcrumb Section Start -->
<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2>OTP</h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="index.html">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item active">OTP</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->

<!-- log in section start -->
<section class="log-in-section otp-section section-b-space">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
				<div class="image-contain">
					<img src="<?php echo URL;?>/assets/images/inner-page/otp.png" class="img-fluid" alt="">
				</div>
			</div>

			<div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
				<div class="d-flex align-items-center justify-content-center h-100">
					<div class="log-in-box">
						<div class="log-in-title">
							<h3 class="text-title"><?php echo $appSession->getLang()->find("Please enter the one time password to verify your account");?></h3>
							<h5 class="text-content"><?php echo $appSession->getLang()->find("A code has been sent to");?> <span><?php echo $phone;?></span></h5>
						</div>

						<div  class="inputs d-flex flex-row justify-content-center">
							<input id="otp1" onkeyup="numChanged(1)" class="text-center form-control rounded" type="text"  maxlength="1"
								placeholder="-">
							<input id="otp2" onkeyup="numChanged(2)" class="text-center form-control rounded" type="text" maxlength="1"
								placeholder="-">
							<input id="otp3" onkeyup="numChanged(3)" class="text-center form-control rounded" type="text" maxlength="1"
								placeholder="-">
							<input id="otp4" onkeyup="numChanged(4)" class="text-center form-control rounded" type="text" maxlength="1"
								placeholder="-">
						 
						</div>

						<div class="send-box pt-4">
							<h5><?php echo $appSession->getLang()->find("I do not have code");?>? <a href="javascript:resend()" class="theme-color fw-bold"><?php echo $appSession->getLang()->find("Resend It");?></a></h5>
						</div>

						<button onclick="activeAccount();" class="btn btn-animation w-100 mt-3"
							type="button"><?php echo $appSession->getLang()->find("Validate");?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
    <!-- log in section end -->
<script>
  function activeAccount() {
    var code = document.getElementById('otp1').value + document.getElementById('otp2').value + document.getElementById('otp3').value + document.getElementById('otp4').value;
    if (code == "") {
      alert('<?php echo $appSession->getLang()->find("Please input OTP");?>');
 
      return;
    }
    var _url = "<?php echo URL; ?>api/account/?ac=active&id=" + encodeURIComponent('<?php echo $id;?>');
    _url = _url + "&code=" + encodeURIComponent(code);
    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          alert('<?php echo $appSession->getLang()->find("Account is actived");?>');
          document.location.href = '<?php echo URL; ?>signin';
        } else if (message.indexOf("INVALID") != -1) {
          alert('<?php echo $appSession->getLang()->find("Invalid OTP");?>');
        }
      }
    }, true);
  }
  function resend()
  {
	var _url = "<?php echo URL; ?>api/account/?ac=resend_otp&id=" + encodeURIComponent('<?php echo $id;?>');
	
    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          alert('<?php echo $appSession->getLang()->find("OTP is send");?>');
			document.getElementById('otp1').value = "";
			document.getElementById('otp2').value = "";
			document.getElementById('otp3').value = "";
			document.getElementById('otp4').value = "";
			document.getElementById('otp1').focus();
        } else if (message.indexOf("INVALID") != -1) {
          alert('<?php echo $appSession->getLang()->find("Invalid OTP");?>');
        }
      }
    }, true);
  }
  function numChanged(num)
  {
	  var ctr = document.getElementById('otp' + num);
	  if(ctr.value != "")
	  {
		  if(num == 4)
		  {
			  activeAccount();
		  }else{
			 ctr = document.getElementById('otp' + (num + 1));
			 if(ctr != null)
			 {
				ctr.focus();
			 }
		  }
	  }
  }
   document.getElementById('otp1').focus();
</script>
<!-- Product order success section end -->
