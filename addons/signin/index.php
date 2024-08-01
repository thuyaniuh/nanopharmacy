<?php
$gt = "";
if(isset($_REQUEST['continue']))
{
	$gt = $_REQUEST['continue'];
}
?>
 <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2 class="mb-2"><?php echo $appSession->getLang()->find("Sign In"); ?></h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active"><?php echo $appSession->getLang()->find("Sign In"); ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- log in section start -->
   <section class="log-in-section background-image-2 section-b-space">
     <div class="container-fluid-lg w-100">
       <div class="row">
         <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
           <div class="image-contain">
             <img src="<?php echo URL; ?>assets/images/log-in.png" class="img-fluid" alt="">
           </div>
         </div>
         <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
           <div class="log-in-box">
				<div class="log-in-title">
			
					<h4><?php echo $appSession->getLang()->find("Log In Your Account"); ?></h4>
				</div>

             <div class="input-box">
               <form class="row g-4" onsubmit="return false;">
                 <div class="col-12">
                   <div class="form-floating theme-form-floating log-in-form">
                     <input type="text" class="form-control" id="edituser" placeholder="Username" autocomplete=false>
                     <label for="edituser"><?php echo $appSession->getLang()->find("Username"); ?></label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating log-in-form">
                     <input type="password" class="form-control" id="editpassword" placeholder="Password" autocomplete=false>
                     <label for="editpassword"><?php echo $appSession->getLang()->find("Password"); ?></label>
                   </div>
                 </div>
				<div class="col-12">
					<div class="forgot-box">
						<div class="form-check ps-0 m-0 remember-box">
							
						</div>
						<a href="<?php echo URL;?>forgot" class="forgot-password"><?php echo $appSession->getLang()->find("Forgot Password"); ?>?</a>
					</div>
				</div>
                 <div class="col-12">
                   <button id="signInBTN" class="btn btn-animation w-100 justify-content-center" type="button" onclick="signIn()"><?php echo $appSession->getLang()->find("Sign In"); ?></button>
                 </div>
               </form>
             </div>
             <div class="other-log-in">
               <h6><?php echo $appSession->getLang()->find("New Customer"); ?></h6>
             </div>
             <div class="sign-up-box">
               <h4><?php echo $appSession->getLang()->find("Create a New Account"); ?></h4>
               <p><?php echo $appSession->getLang()->find("Sign up for a free account at our store. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register"); ?>.</p>
               <a href="<?php echo URL; ?>signup"><?php echo $appSession->getLang()->find("Sign Up"); ?></a>
             </div>
           </div>
         </div>
       </div>
     </div>
   </section>

   <script>
    function signIn() {
       var login = document.getElementById('edituser').value;

       // INFO: no secure anymore
       var password = Sha1.hash(document.getElementById('editpassword').value);

       var _url = BASE_URL +
         "api/account/?ac=login_web&user=" + encodeURIComponent(login) +
         "&password=" + encodeURIComponent(password);
		console.log(_url);
       loadPage('contentView', _url, function(status, message) {
         if (status === 0) {
           if (message.search("INCORRECT") !== -1 || message.search("INVALID") !== -1) {
             alert('<?php echo $appSession->getLang()->find("Invalid login or password"); ?>');

             return;
           }else if(message.search("OK") != -1){
			   if('<?php echo $gt;?>' == 'checkout')
			   {
				   location.href = BASE_URL + '/checkout/';
			   }else{
				   location.href = BASE_URL;
			   }
			   
		   }else
		   {
				alert("Error login");
				console.log(message);
		   }
		  

           
         } else {
           console.log(message);
         }
       }, true);
     }

     document.querySelector('#editpassword').addEventListener('keyup', event => {
       if (event.key !== "Enter") return;
       document.getElementById('signInBTN').click();
       event.preventDefault();
     })

     document.querySelector('#edituser').addEventListener('keyup', event => {
       if (event.key !== "Enter") return;
       document.getElementById('signInBTN').click();
       event.preventDefault();
     })
   </script>
