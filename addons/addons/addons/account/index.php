<!-- INFO: do vaucher and loyalty section if need -->

<?php
require_once(ABSPATH . 'api/Sale.php');

$type = "order";
$user_id = $appSession->getUserInfo()->getId();
$company_currency_id = $appSession->getConfig()->getProperty("currency_id");

for ($i = 0; $i < count($arr); $i++) {
  $index = $appSession->getTool()->indexOf($arr[$i], '=');
  if ($index != -1) {
    $k = substr($arr[$i], 0, $index);
    $v = substr($arr[$i], $index + 1);
    if ($k == "type") {
      $type = $v;
    }
  }
}

if ($appSession->getUserInfo()->getId() == "") {
  $type = "order";
}

$banklist = [];

if ($type == "wallet") {
}

$msg = $appSession->getTier()->createMessage();
?>

 <section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $appSession->getLang()->find("Profile");?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="index.html">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $appSession->getLang()->find("Profile");?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
$user_id = $appSession->getConfig()->getProperty("user_id");
$sql = "SELECT d2.publish, d3.name, d3.phone, d3.email, d4.name AS group_name, pc.document_id AS company_document_id, d2.phone AS company_phone, d2.email AS company_email, d2.name AS company_name, d2.address AS company_address FROM res_user_company d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id= d2.id) LEFT OUTER JOIN res_user d3 ON(d1.user_id = d3.id) LEFT OUTER JOIN res_user_group d4 ON(d1.group_id = d4.id) LEFT OUTER JOIN poster pc ON(d2.id = pc.rel_id AND pc.status =0 AND pc.publish=1) WHERE d1.status =0 AND d1.user_id ='" .$user_id . "'";

$msg->add("query", $sql);
$dt = $appSession->getTier()->getTable($msg);
$isShop = false;
$account_name = "";
$account_phone = "";
$account_email = "";
$company_document_id = "";
$company_address = "";
if($dt->getRowCount()>0)
{
	if($dt->getString(0, "group_name") == "SHOP")
	{
		$isShop = true;
		$company_document_id = $dt->getString(0, "company_document_id");
		$account_name = $dt->getString(0, "company_name");
		$account_phone = $dt->getString(0, "company_phone");
		$account_email = $dt->getString(0, "company_email");
		
	}else{
		$account_name = $dt->getString(0, "name");
		$account_phone = $dt->getString(0, "phone");
		$account_email = $dt->getString(0, "email");
	}
	$company_address = $dt->getString(0, "company_address");
	
}
?>




<section class="user-dashboard-section section-b-space">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-xxl-2 col-lg-4">
				<div class="dashboard-left-sidebar">
					<div class="close-button d-flex d-lg-none">
						<button class="close-sidebar">
							<i class="fa-solid fa-xmark"></i>
						</button>
					</div>
					<div class="profile-box">
						<div class="cover-image">
						<img src="<?php echo URL;?>/assets/images/inner-page/cover-img.jpg" class="img-fluid blur-up lazyload"
								alt="">
						
						</div>

						<div class="profile-contain">
							<div class="profile-image">
								<div class="position-relative">
									<?php 
						if($company_document_id != "")
						{
						
						?>
							<img src="<?php echo URL;?>document/?id=<?php echo $company_document_id;?>&h=500"
										class="blur-up lazyload update_img" alt="">
							<?php
						}else{
						?>
						<img src="<?php echo URL;?>/assets/images/inner-page/user/1.jpg"
										class="blur-up lazyload update_img" alt="">
						<?php
						}
						?>
									
									<div class="cover-icon">
										<i class="fa-solid fa-pen">
											<input type="file" onchange="readURL(this,0)">
										</i>
									</div>
								</div>
							</div>

							<div class="profile-name">
								<h3><?php echo $appSession->getConfig()->getProperty("user_name");?></h3>
							   
							</div>
						</div>
					</div>

					<ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
								data-bs-target="#pills-profile" type="button" role="tab"
								aria-controls="pills-profile" aria-selected="false"><i data-feather="user"></i>
								<?php echo $appSession->getLang()->find("Profile");?></button>
						</li>
						
						

						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-order-tab" data-bs-toggle="pill"
								data-bs-target="#pills-order" type="button" role="tab" aria-controls="pills-order"
								aria-selected="false"><i data-feather="shopping-bag"></i><?php echo $appSession->getLang()->find("Order");?></button>
						</li>
						<?php 
						if($isShop == true)
						{
						?>
						
						
						<li class="nav-item" role="presentation" onclick="loadProduct()">
							<button class="nav-link" id="pills-product-tab" data-bs-toggle="pill"
								data-bs-target="#pills-product" type="button" role="tab"
								aria-controls="pills-admin" aria-selected="false"><i data-feather="user"></i>
								<?php echo $appSession->getLang()->find("Product");?></button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="pills-report-tab" data-bs-toggle="pill"
								data-bs-target="#pills-report" type="button" role="tab"
								aria-controls="pills-admin" aria-selected="false"><i data-feather="user"></i>
								<?php echo $appSession->getLang()->find("Report");?></button>
						</li>
						
						<?php
						}
						?>
					</ul>
				</div>
			</div>
			<div class="col-xxl-9 col-lg-8">
			 <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
                        Menu</button>
				<div class="dashboard-right-sidebar">
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-profile" role="tabpanel"
							aria-labelledby="pills-profile-tab">
							<div class="dashboard-home">
								<div class="title">
									<h2><?php echo $appSession->getLang()->find("Profile");?></h2>
									<span class="title-leaf">
										<svg class="icon-width bg-gray">
											<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
										</svg>
									</span>
								</div>
								
								<div class="profile-about dashboard-bg-box">
									<div class="row">
										<div class="col-xxl-7">
											<div class="dashboard-title mb-3">
												<h3><?php echo $appSession->getLang()->find("Profile About");?></h3>
											</div>

											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<td><?php echo $appSession->getLang()->find("Name");?> :</td>
															<td><?php echo $account_name;?></td>
														</tr>
														
														<tr>
															<td><?php echo $appSession->getLang()->find("Phone");?> :</td>
															<td>
																<a href="javascript:void(0)"> <?php echo $account_phone;?></a>
															</td>
														</tr>
														<tr>
															<td><?php echo $appSession->getLang()->find("Email");?> :</td>
															<td><?php echo $account_email;?></td>
														</tr>
														<?php
														if($company_address != "")
														{
														?>
														<tr>
															<td><?php echo $appSession->getLang()->find("Address");?> :</td>
															<td><?php echo $company_address;?></td>
														</tr>
														<?php
														}
														?>
													</tbody>
												</table>
											</div>

											<div class="dashboard-title mb-3">
												<h3><?php echo $appSession->getLang()->find("Login Detail");?></h3>
											</div>

											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<td><?php echo $appSession->getLang()->find("User");?> :</td>
															<td>
																<?php echo $appSession->getConfig()->getProperty("user_name");?>
															</td>
														</tr>
														<tr>
															<td><?php echo $appSession->getLang()->find("Password");?> :</td>
															<td>
																<a href="<?php echo URL;?>change_password">●●●●●●
																	<span><?php echo $appSession->getLang()->find("Edit");?></span></a>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>

										<div class="col-xxl-5">
											<div class="profile-image">
												<img src="<?php echo URL;?>/assets/images/inner-page/dashboard-profile.png"
													class="img-fluid blur-up lazyload" alt="">
											</div>
										</div>
									</div>

								</div>
                            </div>
                        </div>
								
						
						<div class="tab-pane fade show" id="pills-order" role="tabpanel"
							aria-labelledby="pills-order-tab">
							<div class="dashboard-home">
								<div class="title">
									<h2><?php echo $appSession->getLang()->find("Order");?></h2>
									<span class="title-leaf">
										<svg class="icon-width bg-gray">
											<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
										</svg>
									</span>
								</div>
								<div class="profile-about dashboard-bg-box">
									<div class="row">
										<div class="col-xxl-7">
											<?php
											$sql = "SELECT d1.id, d1.order_no, d1.order_date, (SELECT COUNT(id) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS items, (SELECT SUM(quantity * unit_price) FROM sale_product_local WHERE status =0 AND sale_id=d1.id) AS amount, (SELECT name FROM res_status_line WHERE rel_id=d1.id AND status =0 ORDER BY create_date DESC LIMIT 1) AS status_name, d1.status, d2.commercial_name FROM sale_local d1 LEFT OUTER JOIN res_company d2 ON(d1.company_id = d2.id) WHERE (d1.status=0 OR d1.status=3)";
											if($user_id != "")
											{
												$sql = $sql." AND (d1.session_id='".$session_id."' OR d1.create_uid='".$user_id."')";
											}else{
												$sql = $sql." AND d1.session_id='".$session_id."'";
											}
											$sql = $sql." ORDER BY d1.order_date DESC";
											$sql = $sql." LIMIT 10";
											
											$msg->add("query", $sql);
											
											$dt = $appSession->getTier()->getTable($msg);
											?>
											<div class="table-responsive">
												<table class="table">
													<thead>
													<tr>
															<td><?php echo $appSession->getLang()->find("Order No");?></td>
															<td><?php echo $appSession->getLang()->find("Status");?></td>
															<td><?php echo $appSession->getLang()->find("Items");?></td>
															<td><?php echo $appSession->getLang()->find("Amount");?></td>
													</tr>
													</thead>
													<tbody>
														<?php
		for($i =0; $i<$dt->getRowCount(); $i++)
		{
			$sale_id = $dt->getString($i, "id");
			$order_no = $dt->getString($i, "order_no");
			$order_date = $dt->getString($i, "order_date");
			$status_name = $dt->getString($i, "status_name");
			$items = $appSession->getTool()->toDouble($dt->getString($i, "items"));
			$total = $appSession->getTool()->toDouble($dt->getString($i, "amount"));
			$sale = new Sale($appSession);
			$dt_product = $sale->productListSaleId($sale_id);
		?>
		
														<tr>
															<td><a href="<?php echo URL;?>order-tracking/<?php echo $order_no;?>"><?php echo $order_no;?></a></td>
															<td><?php echo $status_name;?></td>
															<td><?php echo $items;?></td>
															<td><?php echo $appSession->getCurrency()->format($company_currency_id, $total);?></td>
														</tr>
														<?php
														}
														?>
													</tbody>
												</table>
											</div>
										</div>

										
									</div>

								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="pills-product" role="tabpanel"
							aria-labelledby="pills-product-tab">
							<div class="dashboard-home">
								<div class="title">
									<h2><?php echo $appSession->getLang()->find("Product");?></h2>
									<span class="title-leaf">
										<svg class="icon-width bg-gray">
											<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
										</svg>
									</span>
								</div>
								<div  class="dashboard-bg-box" id="pnProduct">
									
								</div>
							</div>
						</div>
						
						<div class="tab-pane fade show" id="pills-report" role="tabpanel"
							aria-labelledby="pills-report-tab">
							<div class="dashboard-home">
								<div class="title">
									<h2><?php echo $appSession->getLang()->find("Report");?></h2>
									<span class="title-leaf">
										<svg class="icon-width bg-gray">
											<use xlink:href="<?php echo URL;?>/assets/svg/leaf.svg#leaf"></use>
										</svg>
									</span>
								</div>
								<?php
							$module_id = "7da7e675-12a4-4780-ce1a-85c92560b0ac";
							$sql = "SELECT d1.id, d1.parent_id, d1.name, d1.module_id FROM ir_module_report d1 WHERE d1.status =0 AND d1.publish=1 AND d1.rel_id='".$module_id."' ORDER BY d1.sequence ASC";
							$msg->add("query", $sql);
							$values = $appSession->getTier()->getArray($msg);
							?>
								<div  class="dashboard-bg-box">
								<table class="table">
									<thead>
										<tr class="text-left">
											<th  style="width: 20px">#</th>
											<th>Tên</th>
											<th  style="width: 20px">Word</th>
											<th  style="width: 20px">Excel</th>
										</tr>
									</thead>
									<tbody>
										<?php
										for($i =0; $i<count($values); $i++)
										{
											$report_id = $values[$i][0];
											$module_id = $values[$i][3];
										?>
										<tr>
											<td style="width: 20px; text-align:center; valign-text:middle"><?php echo ($i + 1);?></td>
											<td style="valign-text:middle"><a href="javascript:report('<?php echo $report_id;?>', '<?php echo $module_id;?>', 'view')"><?php echo $values[$i][2];?></a></th>
											<td style="valign-text:middle"><a href="javascript:report('<?php echo $report_id;?>', '<?php echo $module_id;?>', 'word')">Tải</a></td>
											<td style="valign-text:middle"><a href="javascript:report('<?php echo $report_id;?>', '<?php echo $module_id;?>', 'excel')">Tải</a></td>
											
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
									
								</div>
							</div>
							
							
					
						</div>
					</div>
					
				</div>
				
			</div>
					
		</div>
	</div>
</section>
<script>
	function loadProduct()
	{
		var _url = '<?php echo URL;?>addons/product_list/?ac=view';
		
		loadPage('pnProduct', _url, function(status, message)
		{
			if(status== 0)
			{
				
			}
			
		}, false);
	}
	
	
</script>



