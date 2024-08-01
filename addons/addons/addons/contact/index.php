<!-- page-header-section start -->
<div class="page-header-section">
  <div class="container">
    <div class="row">
      <div class="col-12 d-flex justify-content-between justify-content-md-end">
        <ul class="breadcrumb">
          <li><a href="<?php echo URL; ?>"><?php echo $appSession->getLang()->find("Home"); ?></a></li>
          <li><span>/</span></li>
          <li><?php echo $appSession->getLang()->find("Contact"); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- page-header-section end -->

<!-- Contact Box Section Start -->
<section class="contact-box-section">
  <div class="container-fluid-lg">
    <div class="row g-lg-5 g-3">
      <div class="col-lg-6">
        <div class="left-sidebar-box">
          <div class="row">
            <div class="col-xl-12">
              <div class="contact-image">
                <img src="assets/images/contact-us.png" class="img-fluid blur-up lazyloaded" alt="">
              </div>
            </div>
            <div class="col-xl-12">
              <div class="contact-title">
                <h3>Liên hệ chúng tôi</h3>
              </div>
              <div class="contact-detail">
                <div class="row g-4">
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-phone"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4>Số điện thoại</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <p>0785 873 618</p>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-message"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4>Zalo</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <a href="https://zalo.me/0785873618" target="_blank">0785 873 618</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-envelope"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4>Địa chỉ Email</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <p>cskh@nanopharmacy.vn</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-money-bill"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4>Mã số thuế</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <p>0318120242</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-location-dot"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4> TP. HCM</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <p>55, Phạm Văn Ngôn, P. Bình Khánh, TP. Thủ Đức</p>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-6 col-lg-12 col-sm-6">
                    <div class="contact-detail-box">
                      <div class="contact-icon">
                        <i class="fa-solid fa-file-contract"></i>
                      </div>
                      <div class="contact-detail-title">
                        <h4>Số tài khoản</h4>
                      </div>
                      <div class="contact-detail-contain">
                        <p>6768399999 - Ngân hàng TMCP Quận Đội</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="title d-xxl-none d-block">
          <h2>Thông tin liên hệ</h2>
        </div>
        <div class="right-sidebar-box">
          <div class="row">
            <div class="col-xxl-6 col-lg-12 col-sm-6">
              <div class="mb-md-4 mb-3 custom-form">
                <label for="editcontact_name" class="form-label">Họ tên</label>
                <div class="custom-input">
                  <input type="text" class="form-control" id="editcontact_name" placeholder="Họ tên">
                  <i class="fa-solid fa-user"></i>
                </div>
              </div>
            </div>
            <div class="col-xxl-6 col-lg-12 col-sm-6">
              <div class="mb-md-4 mb-3 custom-form">
                <label for="editcontact_phone" class="form-label">Số điện thoại</label>
                <div class="custom-input">
                  <input type="text" class="form-control" id="editcontact_phone" placeholder="Số điện thoại" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value =
                                            this.value.slice(0, this.maxLength);">
                  <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
              </div>
            </div>
            <div class="col-xxl-6 col-lg-12 col-sm-6">
              <div class="mb-md-4 mb-3 custom-form">
                <label for="editcontact_email" class="form-label">Địa chỉ email</label>
                <div class="custom-input">
                  <input type="email" class="form-control" id="editcontact_email" placeholder="Địa chỉ email">
                  <i class="fa-solid fa-envelope"></i>
                </div>
              </div>
            </div>
            <div class="col-xxl-6 col-lg-12 col-sm-6">
              <div class="mb-md-4 mb-3 custom-form">
                <label for="editcontact_title" class="form-label">Tiêu đề</label>
                <div class="custom-input">
                  <input type="tel" class="form-control" id="editcontact_title" placeholder="Tiêu đề">
                  <i class="fa-solid fa-heading"></i>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-md-4 mb-3 custom-form">
                <label for="exampleFormControlTextarea" class="form-label">Nội dung</label>
                <div class="custom-textarea">
                  <textarea class="form-control" id="editcontact_message" placeholder="Nội dung" rows="6"></textarea>
                  <i class="fa-solid fa-message"></i>
                </div>
              </div>
            </div>
          </div>
          <button class="btn btn-animation btn-md fw-bold ms-auto" onclick="sendContact()">Gửi tin nhắn</button>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Contact Box Section End -->

<!-- Map Section Start -->
<section class="map-section">
  <div class="container-fluid p-0">
    <div class="map-box">
      <iframe src="https://www.google.com/maps/embed/v1/place?q=55+phạm+văn+ngôn,+p.+bình+khánh&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>
<!-- Map Section End -->

<script>
  function sendContact() {
    var ctrName = document.getElementById('editcontact_name');
    if (ctrName.value == '') {
      alert('Họ tên không được để trống!');
      ctrName.focus();
      return;
    }

    var ctrPhone = document.getElementById('editcontact_phone');
    if (ctrPhone.value == '') {
      alert('Số điện thoại không được để trống!');
      ctrPhone.focus();
      return;
    }

    var ctrEmail = document.getElementById('editcontact_email');
    if (!validateEmail(ctrEmail.value)) {
      alert('Email không đúng định dạng!');
      ctrEmail.focus();
      return;
    }

    if (ctrEmail.value == '') {
      alert('Email không được để trống!');
      ctrEmail.focus();
      return;
    }

    var ctrTitle = document.getElementById('editcontact_title');
    if (ctrTitle.value == '') {
      alert('Tiêu đề không được để trống!');
      ctrTitle.focus();
      return;
    }

    var ctrMessage = document.getElementById('editcontact_message');
    if (ctrMessage.value == '') {
      alert('Nội dung không được để trống!');
      ctrMessage.focus();
      return;
    }

    sendEmailContact(ctrName.value, ctrPhone.value, ctrEmail.value, ctrTitle.value, ctrMessage.value,
      function(status, message) {
        if (status == 0) {

          if (message.indexOf("OK") != -1) {
            document.location.href = '<?php echo URL; ?>';
          } else {
            console.log(message);
          }
        }
      })
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
