<!-- TODO: DRY one page for remove -->
<?php
include(ABSPATH . 'app/lang/' . $appSession->getConfig()->getProperty("lang_id") . '.php');
foreach ($langs as $key => $item) {
  $appSession->getLang()->setProperty($key, $item);
}
?>

<div class="row">
  <div class="col-lg-12">
    <div class="login-body-wrapper">
      <div class="login-body">
        <div class="login-header">
          <h3><?php echo $appSession->getLang()->find("Login with your account"); ?></h3>
        </div>
        <div class="login-content">
          <input type="text" id="edituser" class="form-control" placeholder="<?php echo $appSession->getLang()->find("Username"); ?>" autocomplete="off">
          <br>
          <input type="password" id="editpassword" class="form-control" placeholder="<?php echo $appSession->getLang()->find("Password"); ?>" autocomplete="off">
          <br>
          <button type="button" class="btn btn-primary" onclick="doLogin()"><?php echo $appSession->getLang()->find("Login"); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
