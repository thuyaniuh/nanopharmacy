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

  <script src='https://vftfarms.com/assets/js/jquery.min.js'></script>
  <script type="text/javascript" src="https://vftfarms.com/assets/js/controller.js"></script>
  <script type="text/javascript" src="https://vftfarms.com/assets/js/tool.js"></script>
</div>
<!-- page-header-section end -->

<!-- log in section start -->
<section class="log-in-section otp-section section-b-space">
  <div class="container-fluid-lg">
    <div class="row">
      <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
        <div class="image-contain">
          <img src="assets/images/otp.png" class="img-fluid" alt="">
        </div>
      </div>

      <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
        <div class="d-flex align-items-center justify-content-center h-100">
          <div class="log-in-box">
            <div class="log-in-title">
              <h3 class="text-title">Cảm ơn bạn đã đăng ký thành viên của VFSC</h3>
              <h5 class="text-content">Bạn hãy đăng nhập để tiến hành lựa chọn những sản phẩm yêu thích</span></h5>
            </div>
            <div id="otp" class="inputs d-flex flex-row justify-content-center">
              <input type="text" class="text-center form-control rounded" id="editotp" name="editotp">
            </div>
            <div>
              <a href="javascript:activeAccount()" class="btn btn-animation w-100 mt-3">KÍCH HOẠT</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- log in section end -->

<!-- INFO: I think it is not safe -->
<script>
  function activeAccount() {
    var ctr = document.getElementById('editotp');
    if (ctr.value == "") {
      alert("Nhập mã OTP để kích hoạt tài khoản");
      ctr.focus();
      return;
    }
    var _url = "<?php echo URL; ?>api/account/?ac=active";
    _url = _url + "&code=" + encodeURIComponent(ctr.value);

    loadPage('contentView', _url, function(status, message) {
      if (status == 0) {
        if (message.indexOf("OK") != -1) {
          alert("Tài khoản được kích hoạt");
          document.location.href = '<?php echo URL; ?>signup';
        } else if (message.indexOf("INVALID") != -1) {
          alert("Mã không hợp lệ");
        }
      }
    }, true);
  }
</script>
<!-- Product order success section end -->
