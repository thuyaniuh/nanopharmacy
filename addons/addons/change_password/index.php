<?php
$msg = $appSession->getTier()->createMessage();
if($appSession->getConfig()->getProperty("user_id") == "")
{
	echo '<h1>Invalid Session</h1>';
	exit();
}
$id = "";
$ac = "view";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}

?>
<?php
if($ac == "view")
{
?>
<!-- Breadcrumb Section Start -->
<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $appSession->getLang()->find("Change Password");?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="index.html">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item active"><?php echo $appSession->getLang()->find("Change Password");?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->

<section class="log-in-section section-b-space">
	<div class="container-fluid-lg w-100">
		<div class="row">
			<div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
				<div class="image-contain">
					<img src="<?php echo URL;?>/assets/images/inner-page/sign-up.png" class="img-fluid" alt="">
				</div>
			</div>

			<div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
			
				<div class="log-in-box">
					<div class="log-in-title">
			
						<h4><?php echo $appSession->getLang()->find("Change Password");?></h4>
					</div>

					<div class="input-box">
						<form class="row g-4">
							
							<div class="col-12">
								<div class="form-floating theme-form-floating">
									<input type="password" class="form-control" id="editold_password"
										placeholder="<?php echo $appSession->getLang()->find("Old password");?>">
									<label for="editold_password"><?php echo $appSession->getLang()->find("Old Password");?></label>
								</div>
							</div>

							<div class="col-12">
								<div class="form-floating theme-form-floating">
									<input type="password" class="form-control" id="password"
										placeholder="<?php echo $appSession->getLang()->find("Password");?>">
									<label for="password"><?php echo $appSession->getLang()->find("Password");?></label>
								</div>
							</div>
							<div class="col-12">
								<div class="form-floating theme-form-floating">
									<input type="password" class="form-control" id="retype_password"
										placeholder="<?php echo $appSession->getLang()->find("Retype Password");?>">
									<label for="retype_password"><?php echo $appSession->getLang()->find("Retype Password");?></label>
								</div>
							</div>

							<div class="col-12">
								<button id="btnReset" class="btn btn-animation w-100" type="button" onclick="resetPassword()"><?php echo $appSession->getLang()->find("Ok");?></button>
							</div>
						</form>
					</div>
				</div>
				
			
			</div>

			<div class="col-xxl-7 col-xl-6 col-lg-6"></div>
		</div>
	</div>
</section>
	
    <!-- log in section end -->
<script>
  function resetPassword() {
	   var ctr = document.getElementById('editold_password');
    if (ctr.value == '') {
	 alert('<?php echo $appSession->getLang()->find("Old Password is not empty"); ?>');
	 ctr.focus();
	 return;
    }
   var old_password = ctr.value;
   ctr = document.getElementById('password');
   if (ctr.value == '') {
	 alert('<?php echo $appSession->getLang()->find("Password is not empty"); ?>');
	 ctr.focus();
	 return;
   }
   var password = ctr.value;

   var ctr = document.getElementById('retype_password');

   if (password != ctr.value) {
	 ctr.focus();
	 alert('<?php echo $appSession->getLang()->find("Password is not mark"); ?>');
	 return false;
   }
   old_password = Sha1.hash(old_password); 
   password = Sha1.hash(password);  
    var _url = "<?php echo URL; ?>api/account/?ac=change_password&password=" + encodeURIComponent(old_password);
    _url = _url + "&new_password=" + encodeURIComponent(password);
	 _url = _url + "&user_id=<?php echo $appSession->getConfig()->getProperty("user_id");?>";
	 console.log(_url);
    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          
           loadPage('contentView', BASE_URL + "api/account/?ac=logout", function(status, message) {
		  if (status == 0) {
			window.location.href = '<?php echo URL;?>signin';
		  } else {
			console.log(message);
		  }
		}, true);
        } else if (message.indexOf("INVALID_PASSWORD") != -1) {
          alert('<?php echo $appSession->getLang()->find("Invalid old password");?>');
        }
      }
    }, true);
  }
   document.querySelector('#password').addEventListener('keyup', event => {
       if (event.key !== "Enter") return;
       document.getElementById('btnReset').click();
       event.preventDefault();
     });
	 document.querySelector('#retype_password').addEventListener('keyup', event => {
       if (event.key !== "Enter") return;
       document.getElementById('btnReset').click();
       event.preventDefault();
     })
  
</script>
<!-- Product order success section end -->
<?php
}
else{
	?>
	  <!-- 404 Section Start -->
  <section class="section-404 section-lg-space">
    <div class="container-fluid-lg">
      <div class="row">
        <div class="col-12">
          <div class="image-404">
            <img src="<?php echo URL; ?>assets/images/404.png" class="img-fluid blur-up lazyload" alt="">
          </div>
        </div>

        <div class="col-12">
          <div class="contain-404">
            <h3 class="text-content">The page you are looking for could not be found. The link to this
              address may be outdated or we may have moved the since you last bookmarked it.</h3>
            <button onclick="location.href = '<?php echo URL; ?>';" class="btn btn-md text-white theme-bg-color mt-4 mx-auto">Back To
              Home Screen</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- 404 Section End -->

	<?php
}
?>
