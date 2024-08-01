// INFO: search modul
var category

function setCategory(code) {
  document.getElementById("editsearch-category").setAttribute("value", String(code))
}

document.querySelector("#editsearch").addEventListener("keyup", event => {
  if (event.key !== "Enter") return;
  doSearch('editsearch');
  event.preventDefault();
});

document.querySelector("#editsearchSideMenu").addEventListener("keyup", event => {
  if (event.key !== "Enter") return;
  doSearch('editsearchSideMenu');
  event.preventDefault();
});

function doSearch(elementID) {
  var categoryCode = document.getElementById('editsearch-category').getAttribute("value");
  var product = document.getElementById(String(elementID)).value;

  document.location.href = categoryCode !== "" ? categoryCode + "/?search=" + encodeURIComponent(product) : "?search=" + encodeURIComponent(product);
}

var BASE_URL = '<?php echo URL; ?>';

function doLoginAccount(user_name, password, oncompleted) {

  var _url = BASE_URL + "api/account/?ac=login_web";
  _url = _url + "&user=" + encodeURIComponent(user_name);
  _url = _url + "&password=" + encodeURIComponent(password);
  console.log(_url);
  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (oncompleted != null) {
        oncompleted(status, message);
      }
    } else {
      console.log(message);
    }

  }, true);
}

function doLogout() {
  var _url = BASE_URL + "api/account/?ac=logout";

  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      document.location.reload();
    } else {
      console.log(message);
    }

  }, true);
}

function loadCard() {
  var _url = BASE_URL + "api/sale_action/?ac=viewCardCount";

  loadPage('contentView', _url, function(status, message) {

    // if (status == 0) {
    //   var index = message.indexOf(';');
    //   if (index != -1) {
    //     var ctr = document.getElementById('sitebar-drawar');
    //
    //     var itemCount = message.substring(0, index);
    //     var totalPrice = message.substring(index + 1);
    //     if (itemCount == "0") {
    //       ctr.style.display = "none";
    //     } else {
    //       ctr.style.display = "block";
    //     }
    //     ctr = document.getElementById('itemCount');
    //     if (ctr != null) {
    //       ctr.innerHTML = itemCount;
    //     }
    //     ctr = document.getElementById('itemCountMobile');
    //     if (ctr != null) {
    //       ctr.innerHTML = itemCount;
    //     }
    //     ctr = document.getElementById('totalPrice');
    //     if (ctr != null) {
    //       ctr.innerHTML = totalPrice;
    //     }
    //     ctr = document.getElementById('totalPriceMobile');
    //     if (ctr != null) {
    //       ctr.innerHTML = totalPrice;
    //     }
    //
    //     ctr = document.getElementById('itemCountCard');
    //     if (ctr != null) {
    //       ctr.innerHTML = itemCount;
    //     }
    //     ctr = document.getElementById('totalPriceCard');
    //     if (ctr != null) {
    //       if (itemCount == "0") {
    //         ctr.innerHTML = "";
    //       } else {
    //         ctr.innerHTML = totalPrice;
    //       }
    //
    //     }
    //   }
    // }

  }, true);

}

function addProduct(product_id, currency_id, unit_id, attribute_id, quantity, unit_price, second_unit_id, factor, description, company_id, price_id, type_id, oncompleted) {
  if (factor == "" || factor == "0") {
    factor = 1;
  }
  var _url = BASE_URL + "api/sale_action/?ac=addProduct";
  _url = _url + "&product_id=" + product_id;
  _url = _url + "&currency_id=" + currency_id;
  _url = _url + "&unit_id=" + unit_id;
  _url = _url + "&attribute_id=" + attribute_id;
  _url = _url + "&quantity=" + quantity;
  _url = _url + "&unit_price=" + encodeURIComponent(unit_price);
  _url = _url + "&second_unit_id=" + second_unit_id;
  _url = _url + "&factor=" + factor;
  _url = _url + "&description=" + encodeURIComponent(description);
  _url = _url + "&company_id=" + company_id;
  _url = _url + "&rel_id=" + price_id;
  _url = _url + "&type_id=" + type_id;

  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.length == 36) {
        if (oncompleted != null) {
          oncompleted(status, message);

        }
        loadCardContent();
      } else {
        console.log(message);
      }

    }

  }, true);
}

function removeCard(id, oncompleted) {
  var result = confirm('<?php echo $appSession->getLang()->find("Are you sure to remove?"); ?>');
  if (!result) {
    return;
  }
  var _url = BASE_URL + "api/sale_action/?ac=removeCard";
  _url = _url + "&id=" + id;

  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.length == 36) {
        if (oncompleted != null) {
          oncompleted(status, message);
        }
      } else {
        console.log(message);
      }

    }

  }, true);
}

function updateCard(id, quantity, oncompleted) {
  var _url = BASE_URL + "api/product/?ac=sale_product_update_quantity";
  _url = _url + "&id=" + id + "&quantity=" + quantity;

  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.length == 36) {
        if (oncompleted != null) {
          oncompleted(status, message);
        }
      } else {
        console.log(message);
      }

    }

  }, true);
}

function loadWishList() {
  var _url = BASE_URL + "api/sale_action/?ac=viewWishListCount";

  loadPage('contentView', _url, function(status, message) {

    if (status == 0) {

    }

  }, true);

}

function addToWishList(product_id) {
  var _url = BASE_URL + "api/sale_action/?ac=addToWishList";
  _url = _url + "&product_id=" + product_id;
  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.length == 36) {
        loadWishList();
      } else {
        console.log(message);
      }

    }

  }, true);
}

function removeToWishList(product_id) {
  var _url = BASE_URL + "api/sale_action/?ac=removeToWishList";
  _url = _url + "&product_id=" + product_id;
  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.length == 36) {
        document.location.reload();
      } else {
        console.log(message);
      }

    }

  }, true);
}

function sendEmailContact(name, phone, email, title, message, oncompleted) {
  var _url = BASE_URL + "api/contact/?ac=send";
  _url = _url + "&name=" + encodeURIComponent(name);
  _url = _url + "&phone=" + encodeURIComponent(phone);
  _url = _url + "&email=" + encodeURIComponent(email);
  _url = _url + "&title=" + encodeURIComponent(title);
  _url = _url + "&message=" + encodeURIComponent(message);

  loadPage('contentView', _url, function(status, message) {
    if (status == 0) {
      if (message.indexOf("OK") != -1) {
        if (oncompleted != null) {
          oncompleted(status, message);
        }
      } else {
        console.log(message);
      }
    }
  }, true);
}

function loadCardContent() {
  var _url = BASE_URL + "addons/card/";
  loadPage('sitebar-cart', _url, function(status, message) {
    if (status == 0) {

    }
  }, false);
}

function cartopen() {

  var _url = BASE_URL + "addons/card/";
  loadPage('sitebar-cart', _url, function(status, message) {
    if (status == 0) {
      document.getElementById("sitebar-cart").classList.add('open-cart');
      document.getElementById("sitebar-drawar").classList.add('hide-drawer');
    }
  }, false);



}

function cartclose() {
  document.getElementById("sitebar-cart").classList.remove('open-cart');
  document.getElementById("sitebar-drawar").classList.remove('hide-drawer');
}

function openModalProduct(id) {

  var _url = '<?php echo URL; ?>addons/product_popup/?id=' + id;
  openPopup(_url);
}

function openPopup(_url, oncompleted) {

  loadPage('pnFullDialogContent', _url, function(status, message) {
    if (status == 0) {
      $("#pnFullDialog").modal();
      if (oncompleted != null) {
        oncompleted(status, message);
      }

    }

  }, false);
}

function closePopup() {
  var p = document.getElementById('closeFullDialog');
  p.click();
}

loadCard();
