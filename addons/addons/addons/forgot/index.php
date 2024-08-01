<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $appSession->getLang()->find("Forgot Password");?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="index.html">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item active"><?php echo $appSession->getLang()->find("Forgot Password");?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->

<!-- log in section start -->
<section class="log-in-section section-b-space forgot-section">
	<div class="container-fluid-lg w-100">
		<div class="row">
			<div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
				<div class="image-contain">
					<img src="<?php echo URL;?>/assets/images/inner-page/forgot.png" class="img-fluid" alt="">
				</div>
			</div>

			<div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
				<div class="d-flex align-items-center justify-content-center h-100">
					<div class="log-in-box">
						<div class="log-in-title">
							<h4><?php echo $appSession->getLang()->find("Forgot your password");?></h4>
						</div>

						<div class="input-box">
							<form class="row g-4">
								<div class="col-12">
									<div class="form-floating theme-form-floating log-in-form">
										<input type="email" class="form-control" id="edituser"
											placeholder="Email Address">
										<label for="email"><?php echo $appSession->getLang()->find("Email or Account");?></label>
									</div>
								</div>

								<div class="col-12">
									<button class="btn btn-animation w-100" type="button" id="btnForgot" onclick="forgotPassword()">
										<?php echo $appSession->getLang()->find("Forgot Password");?></button>
								</div>
							</form>
						</div>
						<div class="other-log-in">
						   <h6><?php echo $appSession->getLang()->find("New Customer"); ?></h6>
						 </div>
						 <div class="sign-up-box">
						   <h4><?php echo $appSession->getLang()->find("Create a New Account"); ?></h4>
						   <p><?php echo $appSession->getLang()->find("Sign up for a free account at our store. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register"); ?>.</p>
						   <a href="<?php echo URL; ?>signup/"><?php echo $appSession->getLang()->find("Sign Up"); ?></a>
						 </div>
					   </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
function forgotPassword() {
		var ctr = document.getElementById('edituser');
       if (ctr.value == '') {
         alert('<?php echo $appSession->getLang()->find("Account is not empty");?>');
         ctr.focus();
         return;
       }
       var login = ctr.value;
	   
       var _url = BASE_URL +
         "api/account/?ac=forget_password&user=" + encodeURIComponent(login);
		
       loadPage('contentView', _url, function(status, message) {
         if (status === 0) {
          
			if (message.indexOf("OK") != -1) {
			   var index = message.indexOf("OK:");
			   var id = message.substring(index + 3);
               document.location.href = '<?php echo URL; ?>signin_otp/?id=' + encodeURIComponent(id);
           }else if(message.indexOf("INVALID") != -1)
		   {
			    alert('<?php echo $appSession->getLang()->find("Invalid account");?>');
				ctr.focus();
		   }else{
			   alert(message);
		   }
         } else {
           console.log(message);
         }
       }, true);
     }

     document.querySelector('#edituser').addEventListener('keyup', event => {
       if (event.key !== "Enter") return;
       document.getElementById('btnForgot').click();
       event.preventDefault();
     })

  
   </script>