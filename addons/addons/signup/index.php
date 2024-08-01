   <!-- page-header-section start -->
   <div class="page-header-section">
     <div class="container">
       <div class="row">
         <div class="col-12 d-flex justify-content-between justify-content-md-end">
           <ul class="breadcrumb">
             <li><a href="<?php echo URL; ?>"><?php echo $appSession->getLang()->find("Home"); ?></a></li>
             <li><span>/</span></li>
             <li><?php echo $appSession->getLang()->find("Sign Up"); ?></li>
           </ul>
         </div>
       </div>
     </div>
   </div>
   <!-- page-header-section end -->

   <!-- log in section start -->
   <section class="log-in-section section-b-space">
     <div class="container-fluid-lg w-100">
       <div class="row">
         <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
           <div class="image-contain">
             <img src="assets/images/sign-up.png" class="img-fluid" alt="">
           </div>
         </div>
         <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
           <div class="log-in-box">
             <div class="log-in-title">
         
               <h4><?php echo $appSession->getLang()->find("Create New Account"); ?></h4>
             </div>
             <div class="input-box">
               <form action="#" class="row g-4">
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="text" class="form-control" id="editregister_name" autocomplete=false required>
                     <label for="editregister_name"><?php echo $appSession->getLang()->find("Name"); ?>(*)</label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="text" class="form-control" id="editregister_sale_code" autocomplete=false>
                     <label for="editregister_sale_code"><?php echo $appSession->getLang()->find("Referral code"); ?></label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="text" class="form-control" id="editregister_phone" autocomplete=false required>
                     <label for="editregister_phone"><?php echo $appSession->getLang()->find("Phone"); ?>(*)</label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="text" class="form-control" id="editregister_username" autocomplete=false>
                     <label for="editregister_username"><?php echo $appSession->getLang()->find("Username"); ?>(*)</label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="password" class="form-control" id="editregister_password" placeholder="Password" autocomplete=false>
                     <label for="editregister_password"><?php echo $appSession->getLang()->find("Password"); ?>(*)</label>
                   </div>
                 </div>
                 <div class="col-12">
                   <div class="form-floating theme-form-floating">
                     <input type="password" class="form-control" id="editregister_re_password" autocomplete=false>
                     <label for="editregister_re_password"><?php echo $appSession->getLang()->find("Retype password"); ?>(*)</label>
                   </div>
                 </div>
               
                 <div class="col-12">
                   <button onclick="doRegister()" class="submit btn btn-animation w-100" type="button"><?php echo $appSession->getLang()->find("Register"); ?></button>
                 </div>
               </form>
             </div>
   </section>
   <!-- log in section end -->

   <!-- INFO: I don't think that is safe -->
   <script>
	 var isLoging = false;
     function doRegister() {
       var ctr = document.getElementById('editregister_name');
       if (ctr.value == '') {
         alert('<?php echo $appSession->getLang()->find("Name is not empty"); ?>');
         ctr.focus();
         return;
       }
       var name = ctr.value;

       ctr = document.getElementById('editregister_sale_code');
    
       var sale_code = ctr.value;

       ctr = document.getElementById('editregister_phone');
       if (ctr.value == '') {
         alert('<?php echo $appSession->getLang()->find("Phone is not empty"); ?>');
         ctr.focus();
         return;
       }
       var phone = ctr.value;

       ctr = document.getElementById('editregister_username');
       if (ctr.value == '') {
         alert('Username is not empty');
         ctr.focus();
         return;
       }
       var user_name = ctr.value;

       ctr = document.getElementById('editregister_password');
       if (ctr.value == '') {
         alert('<?php echo $appSession->getLang()->find("Password is not empty"); ?>');
         ctr.focus();
         return;
       }
       var password = ctr.value;

       var ctr = document.getElementById('editregister_re_password');

       if (password != ctr.value) {
         ctr.focus();
         alert('<?php echo $appSession->getLang()->find("Password is not mark"); ?>');
         return false;
       }
       password = Sha1.hash(password);
     
       var address = "";
		if(isLoging == true)
		{
			return;
		}
		isLoging = true;
       var _url = "<?php echo URL; ?>api/account/?ac=register_account";
       _url = _url + "&name=" + encodeURIComponent(name);
       _url = _url + "&phone=" + encodeURIComponent(phone);
       _url = _url + "&sale_code=" + encodeURIComponent(sale_code);
       _url = _url + "&user=" + encodeURIComponent(user_name);
       _url = _url + "&address=" + encodeURIComponent(address);
       _url = _url + "&lang_id=" + "vi";
       _url = _url + "&source=" + "WEB";
       _url = _url + "&password=" + encodeURIComponent(password);
		
       loadPage('contentView', _url, function(status, message) {
         if (status == 0) {
			 isLoging = true;
           if (message.indexOf("OK") != -1) {
			   var index = message.indexOf("OK:");
			   var id = message.substring(index + 3);
               document.location.href = '<?php echo URL; ?>signup_otp/?id=' + encodeURIComponent(id);
           } else if (message.indexOf("USER_AVAIBLE") != -1) {
             alert('<?php echo $appSession->getLang()->find("Username is avaible"); ?>');
           } else if (message.indexOf("PHONE_AVAIBLE") != -1) {
             alert('<?php echo $appSession->getLang()->find("Phone is avaible"); ?>');
           } else if (message.indexOf("EMAIL_AVAIBLE") != -1) {
             alert('<?php echo $appSession->getLang()->find("Email is avaible"); ?>');
           } else {
             console.log(message);
           }
         }
       }, true);

     }

     function validateEmail(email) {
       const re =
         /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
       return re.test(String(email).toLowerCase());
     }

     function isValidPhone(str) {
       str = str.replace(/[^0-9]/g, '');
       var l = str.length;
       if (l < 10) return ['error', 'Tel number length < 10'];

       var tel = '',
         num = str.substr(-7),
         code = str.substr(-10, 3),
         coCode = '';
       if (l > 10) {
         coCode = '+' + str.substr(0, (l - 10));
       }
       tel = coCode + ' (' + code + ') ' + num;

       return ['succes', tel];
     }
   </script>
