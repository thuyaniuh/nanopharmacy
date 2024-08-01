
(function($) {
    "use strict";


    $(document).ready(function() {

        $("img.lazy").Lazy({
            effect: "fadeIn",
        });

        $("[data-action='toggle-nav']").on( 'click', function() {
            $( this ).toggleClass('active');
            $(".header-navigation").toggleClass("has-open");
            $("body").toggleClass("menu-open");
            return false;
            
        }) ;

        $(".header-navigation .sub-menu-toggle").on( 'click', function() {
            $( this ).parent().toggleClass('open-sub-menu');
            $( this ).toggleClass('active');
            return false;
        }) ;

        $(".header-search .label").on( 'click', function() {
            $(".header-search").toggleClass("active");
            return false;
            
        }) ;

        $(".filter-action").on( 'click', function() {
        	$( this ).toggleClass('active');
            $(".filter-options").toggleClass("active");
            $("body").toggleClass("filter-open");
            return false;
            
        }) ;

        jQuery('.home-slide').slick({
            infinite: true,
            speed: 300,
            autoplay: true,
            autoplaySpeed: 3000,
            centerMode: false,
            centerPadding: '0px',
            slidesToShow: 1,
            arrows: true,
            dots: true,
            lazyLoad: 'ondemand',
            prevArrow: '<span class="slick-prev"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.18109 11.1713L10.3135 0.89735C10.9031 0.234037 12 0.651085 12 1.53857C12 1.7673 11.9188 1.9886 11.7708 2.16301L4.0979 11.206C3.46459 11.9524 3.46459 13.0476 4.0979 13.794L11.7708 22.837C11.9188 23.0114 12 23.2327 12 23.4614C12 24.3489 10.9031 24.766 10.3135 24.1026L1.18109 13.8287C0.507516 13.071 0.507517 11.929 1.18109 11.1713Z" fill="#fff"/></svg></span>',
            nextArrow: '<span class="slick-next"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8189 11.1713L1.68653 0.89735C1.09692 0.234037 0 0.651085 0 1.53857C0 1.7673 0.081233 1.9886 0.229218 2.16301L7.9021 11.206C8.53541 11.9524 8.53541 13.0476 7.9021 13.794L0.229217 22.837C0.081233 23.0114 0 23.2327 0 23.4614C0 24.3489 1.09692 24.766 1.68653 24.1026L10.8189 13.8287C11.4925 13.071 11.4925 11.929 10.8189 11.1713Z" fill="#fff"/></svg></span>',
        });

        jQuery('.home-product-slide').slick({
            infinite: true,
            speed: 300,
            autoplay: false,
            autoplaySpeed: 2000,
            centerMode: false,
            centerPadding: '0px',
            slidesToShow: 1,
            arrows: true,
            dots: true,
            lazyLoad: 'ondemand',
            prevArrow: '<span class="slick-prev"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.18109 11.1713L10.3135 0.89735C10.9031 0.234037 12 0.651085 12 1.53857C12 1.7673 11.9188 1.9886 11.7708 2.16301L4.0979 11.206C3.46459 11.9524 3.46459 13.0476 4.0979 13.794L11.7708 22.837C11.9188 23.0114 12 23.2327 12 23.4614C12 24.3489 10.9031 24.766 10.3135 24.1026L1.18109 13.8287C0.507516 13.071 0.507517 11.929 1.18109 11.1713Z" fill="#000"/></svg></span>',
            nextArrow: '<span class="slick-next"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8189 11.1713L1.68653 0.89735C1.09692 0.234037 0 0.651085 0 1.53857C0 1.7673 0.081233 1.9886 0.229218 2.16301L7.9021 11.206C8.53541 11.9524 8.53541 13.0476 7.9021 13.794L0.229217 22.837C0.081233 23.0114 0 23.2327 0 23.4614C0 24.3489 1.09692 24.766 1.68653 24.1026L10.8189 13.8287C11.4925 13.071 11.4925 11.929 10.8189 11.1713Z" fill="#000"/></svg></span>',
        });


        jQuery('.product-item-slide').slick({
            infinite: false,
            speed: 300,
            autoplay: false,
            autoplaySpeed: 2000,
            centerMode: false,
            centerPadding: '0px',
            slidesToShow: 1,
            arrows: false,
            dots: true,
            lazyLoad: 'progressive',
        });

        $('.product-media-base').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.product-media-thumb',
            
            infinite: false,
        });
        $('.product-media-thumb').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.product-media-base',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            
            vertical: false,
            infinite: false,
            prevArrow: '<span class="slick-prev"></span>',
            nextArrow: '<span class="slick-next"></span>',

            responsive: [

                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 5,
                    vertical: false,
                  }
                },
                {
                  breakpoint: 360,
                  settings: {
                    slidesToShow: 4,
                    vertical: false,
                  }
                }
              ]
        });

        $('.block-product-slide .product-items').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            
            vertical: false,
            infinite: false,
            prevArrow: '<span class="slick-prev"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.18109 11.1713L10.3135 0.89735C10.9031 0.234037 12 0.651085 12 1.53857C12 1.7673 11.9188 1.9886 11.7708 2.16301L4.0979 11.206C3.46459 11.9524 3.46459 13.0476 4.0979 13.794L11.7708 22.837C11.9188 23.0114 12 23.2327 12 23.4614C12 24.3489 10.9031 24.766 10.3135 24.1026L1.18109 13.8287C0.507516 13.071 0.507517 11.929 1.18109 11.1713Z" fill="#333333"/></svg></span>',
            nextArrow: '<span class="slick-next"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8189 11.1713L1.68653 0.89735C1.09692 0.234037 0 0.651085 0 1.53857C0 1.7673 0.081233 1.9886 0.229218 2.16301L7.9021 11.206C8.53541 11.9524 8.53541 13.0476 7.9021 13.794L0.229217 22.837C0.081233 23.0114 0 23.2327 0 23.4614C0 24.3489 1.09692 24.766 1.68653 24.1026L10.8189 13.8287C11.4925 13.071 11.4925 11.929 10.8189 11.1713Z" fill="#333333"/></svg></span>',

            responsive: [

                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3,
                    vertical: false,
                  }
                },
                {
                  breakpoint: 640,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    vertical: false,
                  }
                }
              ]
        });

        $('.account-product .product-items').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            
            vertical: false,
            infinite: false,
            prevArrow: '<span class="slick-prev"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.18109 11.1713L10.3135 0.89735C10.9031 0.234037 12 0.651085 12 1.53857C12 1.7673 11.9188 1.9886 11.7708 2.16301L4.0979 11.206C3.46459 11.9524 3.46459 13.0476 4.0979 13.794L11.7708 22.837C11.9188 23.0114 12 23.2327 12 23.4614C12 24.3489 10.9031 24.766 10.3135 24.1026L1.18109 13.8287C0.507516 13.071 0.507517 11.929 1.18109 11.1713Z" fill="#333333"/></svg></span>',
            nextArrow: '<span class="slick-next"><svg width="12" height="25" viewBox="0 0 12 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8189 11.1713L1.68653 0.89735C1.09692 0.234037 0 0.651085 0 1.53857C0 1.7673 0.081233 1.9886 0.229218 2.16301L7.9021 11.206C8.53541 11.9524 8.53541 13.0476 7.9021 13.794L0.229217 22.837C0.081233 23.0114 0 23.2327 0 23.4614C0 24.3489 1.09692 24.766 1.68653 24.1026L10.8189 13.8287C11.4925 13.071 11.4925 11.929 10.8189 11.1713Z" fill="#333333"/></svg></span>',

            responsive: [

                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3,
                    vertical: false,
                  }
                },
                {
                  breakpoint: 640,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    vertical: false,
                  }
                }
              ]
        });

        

        jQuery('.btn-number').on( 'click', function(e) {

            e.preventDefault();
            
            var fieldName = jQuery(this).attr('data-field');
            var type      = jQuery(this).attr('data-type');
            var input = jQuery("input[name='"+fieldName+"']");
            var currentVal = parseInt(input.val() , 10);
            if (!isNaN(currentVal)) {
                if(type == 'minus') {
                    
                    if(currentVal > input.attr('minlength')) {
                        input.val(currentVal - 1).change();
                    } 

                } else if(type == 'plus') {

                    input.val(currentVal + 1).change();
                }
            } else {
                input.val(0);
            }
        });

        if(jQuery('[data-countdown]').length){
            $('[data-countdown]').each(function() {
                var $this = $(this), finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function(event) {
                    var fomat ='<div class="box-count box-days"><div class="number">%D</div><div class="text">NGÀY</div></div><div class="box-count box-hours"><div class="number">%H</div><div class="text">GIỜ </div></div><div class="box-count box-min"><div class="number">%M</div><div class="text">PHÚT</div></div><div class="box-count box-secs"><div class="number">%S</div><div class="text">GIÂY</div></div>';
                    $this.html(event.strftime(fomat));
               });
            });
        }

        

        

    }); 

})(jQuery);