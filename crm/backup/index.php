<style>
.col-centered {
    float: none;
    margin: 0 auto;
}

.carousel-control {
    width: 8%;
    width: 0px;
}

.carousel-control.left,
.carousel-control.right {
    margin-right: 40px;
    margin-left: 32px;
    background-image: none;
    opacity: 1;
}

.carousel-control>a>span {
    color: white;
    font-size: 29px !important;
}

.carousel-col {
    position: relative;
    min-height: 1px;
    padding: 5px;
    float: left;
}

.active>div {
    display: none;
}

.active>div:first-child {
    display: block;
}

/*xs*/
@media (max-width: 767px) {
    .carousel-inner .active.left {
        left: -50%;
    }

    .carousel-inner .active.right {
        left: 50%;
    }

    .carousel-inner .next {
        left: 50%;
    }

    .carousel-inner .prev {
        left: -50%;
    }

    .carousel-col {
        width: 50%;
    }

    .active>div:first-child+div {
        display: block;
    }
}

/*sm*/
@media (min-width: 768px) and (max-width: 991px) {
    .carousel-inner .active.left {
        left: -50%;
    }

    .carousel-inner .active.right {
        left: 50%;
    }

    .carousel-inner .next {
        left: 50%;
    }

    .carousel-inner .prev {
        left: -50%;
    }

    .carousel-col {
        width: 50%;
    }

    .active>div:first-child+div {
        display: block;
    }
}

/*md*/
@media (min-width: 992px) and (max-width: 1199px) {
    .carousel-inner .active.left {
        left: -33%;
    }

    .carousel-inner .active.right {
        left: 33%;
    }

    .carousel-inner .next {
        left: 33%;
    }

    .carousel-inner .prev {
        left: -33%;
    }

    .carousel-col {
        width: 33%;
    }

    .active>div:first-child+div {
        display: block;
    }

    .active>div:first-child+div+div {
        display: block;
    }
}

/*lg*/
@media (min-width: 1200px) {
    .carousel-inner .active.left {
        left: -25%;
    }

    .carousel-inner .active.right {
        left: 25%;
    }

    .carousel-inner .next {
        left: 25%;
    }

    .carousel-inner .prev {
        left: -25%;
    }

    .carousel-col {
        width: 25%;
    }

    .active>div:first-child+div {
        display: block;
    }

    .active>div:first-child+div+div {
        display: block;
    }

    .active>div:first-child+div+div+div {
        display: block;
    }
}
</style>

<?php
$product = new Product($appSession);
$dt_category_home = $product->categoryList();

$dt_product_group = $product->productGroup();
$hasHome = false;
for ($i = 0; $i < $dt_product_group->getRowCount(); $i++) {
    if ($dt_product_group->getString($i, "group_category_name") == "HOME") {
        $hasHome = true;
        break;
    }
}
?>

<main class="site-main">
    <?php
    if ($hasHome == true) {
    ?>
    <div class="home-slide">
        <?php
            for ($i = 0; $i < $dt_product_group->getRowCount(); $i++) {
                if ($dt_product_group->getString($i, "group_category_name") == "HOME") {
                    $id = $dt_product_group->getString($i, "id");
                    $code = $dt_product_group->getString($i, "code");
                    $name = $dt_product_group->getString($i, "name_lg");
                    $document_id = $dt_product_group->getString($i, "document_id");
                    if ($name == "") {
                        $name = $dt_product_group->getString($i, "name");
                    }

            ?>
        <div class="item">
            <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>" alt="img" />
            <div class="home-slide-des">
                <h1><?php echo $name ?></h1>
                <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/g-<?php echo $code; ?>"
                    class="btn">XEM THÊM </a>
            </div>
        </div>
        <?php
                }
            }
            ?>
    </div>
    <?php
    }
    ?>
    <div class="product-category">
        <div class="container">
            <div class="block-title home-heading">
                <div class="home-heading-subtitle">DANH MỤC SẢN PHẨM</div>
                <h2 class="home-heading-title">DANH MỤC</h2>
            </div>

            <div class="row">
                <div class="col-xs-11 col-md-10 col-centered">

                    <div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi"
                        data-interval="2500">
                        <div class="carousel-inner">
                            <?php
                            for ($i = 0; $i < $dt_category_home->getRowCount(); $i++) {
                                $parent_id = $dt_category_home->getString($i, "parent_id");


                                if ($parent_id == "880f58b6-9840-4e2b-9e50-b0244f4f210b") {

                                    $document_id = $dt_category_home->getString($i, "document_id");

                                    $category_code = $dt_category_home->getString($i, "code");
                                    $category_name = $dt_category_home->getString($i, "name_lg");
                                    if ($category_name == "") {
                                        $category_name = $dt_category_home->getString($i, "name");
                                    }

                            ?>

                            <div class="carousel-item active">
                                <div class="carousel-col">
                                    <div class="col-6 col-sm-12" style="padding: 5px;">
                                        <div class=" slider-item">
                                            <div class="slider-image">
                                                <a
                                                    href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>">
                                                    <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>&h=100"
                                                        class="img-responsive" /></a>
                                            </div>
                                            <div class="slider-main-detail">
                                                <div class="slider-detail">
                                                    <div class="product-detail">
                                                        <h5> <a
                                                                href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"><?php echo $category_name ?></a>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="cart-section">
                                                    <div class="row">

                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <a href="<?php echo URL; ?><?php echo $appSession->getTool()->validUrl($category_name); ?>/c-<?php echo $category_code; ?>"
                                                                class="add-card btn btn-info"><i class="fa fa-view"
                                                                    aria-hidden="true"></i>
                                                                XEM</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                }
                            }
                            ?>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php
    $productList = $product->productByGroup("SALE");
    if ($productList->getRowCount() > 0) {
    ?>
    <div class="product-category">
        <div class="container">
            <div class="block-title home-heading">
                <div class="home-heading-subtitle">DANH SÁCH SẢN PHẨM</div>
                <h2 class="home-heading-title">SẢN PHẨM GIẢM GIÁ</h2>
            </div>
            <div class="row">

                <?php
                    for ($i = 0; $i < $productList->getRowCount(); $i++) {

                        $id = $productList->getString($i, "id");
                        $code = $productList->getString($i, "code");
                        $document_id = $productList->getString($i, "document_id");
                        $name = $productList->getString($i, "name_lg");
                        $unit_price = $productList->getFloat($i, "unit_price");
                        $old_price = $productList->getFloat(0, "old_price");
                        if ($name == "") {
                            $name = $productList->getString($i, "name");
                        }

                    ?>
                <div class="col-6 col-sm-3" style="padding: 5px;">
                    <div class=" slider-item">
                        <div class="slider-image">
                            <a
                                href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>">
                                <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                    class="img-responsive" /></a>
                        </div>
                        <div class="slider-main-detail">
                            <div class="slider-detail">
                                <div class="product-detail">
                                    <h5> <a
                                            href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"><?php echo $name ?></a>
                                    </h5>
                                    <h5 class="detail-price"><?php echo $unit_price; ?>đ
                                        <?php
                                                if ($old_price != 0) {
                                                    $p = -100;
                                                    if ($unit_price != 0) {
                                                        $p = $old_price - $unit_price;
                                                        $p = ($p / $old_price) * 100;
                                                    }

                                                ?><?php echo $old_price; ?>đ<?php
                                                                        }
                                                                            ?>


                                    </h5>
                                </div>
                            </div>
                            <div class="cart-section">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-heart" aria-hidden="true"></i>
                                            YÊU THÍCH</a>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-shopping-cart"
                                                aria-hidden="true"></i> XEM</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                    ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <?php
    $productList = $product->productByGroup("FEATURED PRODUCT");
    if ($productList->getRowCount() > 0) {
    ?>
    <div class="product-category">
        <div class="container">
            <div class="block-title home-heading">
                <div class="home-heading-subtitle">DANH SÁCH SẢN PHẨM</div>
                <h2 class="home-heading-title">SẢN PHẨM NỔI BẬT</h2>
            </div>
            <div class="row">

                <?php
                    for ($i = 0; $i < $productList->getRowCount(); $i++) {

                        $id = $productList->getString($i, "id");
                        $code = $productList->getString($i, "code");
                        $document_id = $productList->getString($i, "document_id");
                        $name = $productList->getString($i, "name_lg");
                        $unit_price = $productList->getFloat($i, "unit_price");
                        if ($name == "") {
                            $name = $productList->getString($i, "name");
                        }

                    ?>
                <div class="col-6 col-sm-3" style="padding: 5px;">
                    <div class=" slider-item">
                        <div class="slider-image">
                            <a
                                href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>">
                                <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                    class="img-responsive" /></a>
                        </div>
                        <div class="slider-main-detail">
                            <div class="slider-detail">
                                <div class="product-detail">
                                    <h5> <a
                                            href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"><?php echo $name ?></a>
                                    </h5>
                                    <h5 class="detail-price"><?php echo $unit_price; ?>đ</h5>
                                </div>
                            </div>
                            <div class="cart-section">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-heart" aria-hidden="true"></i>
                                            YÊU THÍCH</a>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-shopping-cart"
                                                aria-hidden="true"></i> XEM</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                    ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>

    <?php
    $productList = $product->productByGroup("BEST SELLER");
    if ($productList->getRowCount() > 0) {
    ?>
    <div class="product-category">
        <div class="container">
            <div class="block-title home-heading">
                <div class="home-heading-subtitle">DANH SÁCH SẢN PHẨM</div>
                <h2 class="home-heading-title">SẢN PHẨM BÁN CHẠY</h2>
            </div>
            <div class="row">
                <?php
                    for ($i = 0; $i < $productList->getRowCount(); $i++) {
                        $id = $productList->getString($i, "id");
                        $code = $productList->getString($i, "code");
                        $document_id = $productList->getString($i, "document_id");
                        $name = $productList->getString($i, "name_lg");
                        $unit_price = $productList->getFloat($i, "unit_price");
                        if ($name == "") {
                            $name = $productList->getString($i, "name");
                        }

                    ?>
                <div class="col-6 col-sm-3" style="padding: 5px;">
                    <div class="slider-item">
                        <div class="slider-image">
                            <a
                                href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>">
                                <img src="<?php echo URL; ?>document/?id=<?php echo $document_id; ?>"
                                    class="img-responsive" /></a>
                        </div>
                        <div class="slider-main-detail">
                            <div class="slider-detail">
                                <div class="product-detail">
                                    <h5> <a
                                            href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"><?php echo $name ?></a>
                                    </h5>
                                    <h5 class="detail-price"><?php echo $unit_price; ?>đ</h5>
                                </div>
                            </div>
                            <div class="cart-section">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-heart" aria-hidden="true"></i>
                                            YÊU THÍCH</a>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-6">
                                        <a href="<?php echo URL; ?><?php echo  $appSession->getTool()->validUrl($name); ?>/p-<?php echo $code; ?>"
                                            class="add-card btn btn-info"><i class="fa fa-shopping-cart"
                                                aria-hidden="true"></i> XEM</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                    ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>

    <div class="container">
        <div class="home-service">
            <div class="home-service-item">
                <div class="home-service-item-icon">
                    <img class="lazy" data-src="<?php echo URL; ?>assets/img/logo.jpg" alt="img" />
                </div>
                <div class="home-service-detail">
                    <h3>Tải app</h3>
                    <p>Lorem ipsum dolor sit amet, cons ectetur adipiscing elit. Phasellus ac mole stie sapien. </p>
                </div>
            </div>
            <div class="home-service-item">
                <div class="home-service-item-icon">
                    <img class="lazy" data-src="<?php echo URL; ?>assets/images/service2.png" alt="img" />
                </div>
                <div class="home-service-detail">
                    <h3><a href="#"> Khách hàng thân thiết </a></h3>
                    <p>Lorem ipsum dolor sit amet, cons ectetur adipiscing elit. Phasellus ac mole stie sapien. </p>
                </div>
            </div>
            <div class="home-service-item">
                <div class="home-service-item-icon">
                    <img class="lazy" data-src="<?php echo URL; ?>assets/images/service3.png" alt="img" />
                </div>
                <div class="home-service-detail">
                    <h3> Săn voucher</h3>
                    <p>Lorem ipsum dolor sit amet, cons ectetur adipiscing elit. Phasellus ac mole stie sapien. </p>
                </div>
            </div>
        </div>

        <div class="home-social">
            <h2>Kết nối với chúng tôi </h2>

            <a href="#" class="social"><img class="lazy" data-src="<?php echo URL; ?>assets/images/s-instagram.svg"
                    alt="img" /></a>
            <a href="#" class="social"><img class="lazy" data-src="<?php echo URL; ?>assets/images/s-facebook.svg"
                    alt="img" /></a>
            <a href="#" class="social"><img class="lazy" data-src="<?php echo URL; ?>assets/images/s-youtube.svg"
                    alt="img" /></a>
            <a href="#" class="social"><img class="lazy" data-src="<?php echo URL; ?>assets/images/s-tictoc.svg"
                    alt="img" /></a>
        </div>
    </div>
</main>
<script>
$('.carousel[data-type="multi"] .item').each(function() {
    var next = $(this).next();
    if (!next.length) {
        next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));

    for (var i = 0; i < 4; i++) {
        next = next.next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }

        next.children(':first-child').clone().appendTo($(this));
    }
});
</script>