<!-- INFO: blog details -->
<!-- INFO: it was broken and don't give any information -->

<?php
$sql = "SELECT name, content FROM post WHERE id='" . $post_id . "'";
$msg->add("query", $sql);
$dt_post = $appSession->getTier()->getTable($msg);
?>

<!-- page-header-section start -->
<div class="page-header-section">
  <div class="container">
    <div class="row">
      <div class="col-12 d-flex justify-content-between justify-content-md-end">
        <ul class="breadcrumb">
          <li><a href="<?php echo URL; ?>">Trang chủ</a></li>
          <li><span>/</span></li>
          <li><?php echo $dt_post->getString(0, "name"); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- page-header-section end -->

<!-- Blog Details Section Start -->
<?php if ($dt_post->getRowCount() > 0) { ?>
  <section class="blog-section section-b-space">
    <div class="container-fluid-lg">
      <div class="row g-sm-4 g-3">
        <div class="col-12 ratio_50">
          <div class="blog-detail-image rounded-3 mb-4">

            <!-- INFO: how is it storing content with tags -->
            <!-- <?php echo $dt_post->getString(0, "content"); ?> -->

            <!-- TODO: placeholder for layout -->
            <img src="#" class="bg-img blur-up lazyload" alt="Placeholder">

            <div class="blog-image-contain">
              <ul class="contain-list">
                <?php for ($i = 0; $i < count($post_category); $i++) { ?>
                  <li>
                    <a href="<?php echo URL; ?>blog/<?php echo $appSession->getTool()->validUrl($post_category[$i][1]); ?>/<?php echo $post_category[$i][0]; ?>">
                      <?php echo $post_category[$i][1]; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
              <h2=>Nhóm</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php } ?>
<!-- Blog Details Section End -->
