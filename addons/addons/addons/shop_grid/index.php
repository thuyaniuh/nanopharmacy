<?php
$table_name = "res_company";
$columns = [ "name", "parent_id"];
$searchs = ["d1.code", "d1.commercial_name", "d1.address"];

$search = "";
if(isset($_REQUEST['search']))
{
	$search = $_REQUEST['search'];
}else{
	if(isset($_SESSION[$page_id."_search"]))
	{
		$search = $_SESSION[$page_id."_search"];
	}
}
$p = 0;
if(isset($_REQUEST['p']))
{
	$p = $_REQUEST['p'];
}
$ps = 30;
if(isset($_REQUEST['ps']))
{
	$ps = $_REQUEST['ps'];
}

$sql = "SELECT d1.id, d1.code, d1.name, d1.commercial_name, d1.address, d1.phone, p.document_id, doc.path, doc.ext FROM res_company d1 LEFT OUTER JOIN poster p ON(d1.id = p.rel_id AND p.status =0 AND p.publish =1) LEFT OUTER JOIN document doc ON(p.document_id = doc.id) WHERE d1.status =0 AND d1.publish =1";
if($search != "")
{
	$sql = $sql." AND (".$appSession->getTier()->buildSearch($searchs, $search).")";
}
$arr = $appSession->getTier()->paging($sql, $p, $ps, "d1.sequence ASC");

$item_count = 0;
$sql = $arr[1];
$msg->add("query", $sql);
$values = $appSession->getTier()->getArray($msg);

if(count($values)>0)
{
	
	$item_count = $values[0][0];
}

$page_count = (int)($item_count / $ps);
if ($item_count - ($page_count * $ps) > 0)
{
	$page_count = $page_count + 1;
}

$start = 0;
if($item_count>0)
{
	$start = ($p * $ps) + 1;
}
$end = $p + 1;
if((($p + 1) * $ps)<$item_count)
{
	$end = ($p + 1) * $ps;
}else
{
	$end = $item_count;
}

$sql = $arr[0];

$msg->add("query", $sql);

$dt_company = $appSession->getTier()->getTable($msg);

$ids = "";
for($i =0; $i<$dt_company->getRowCount(); $i++)
{
	if($ids != "")
	{
		$ids = $ids ." OR ";
	}
	$ids = $ids." d1.company_id='".$dt_company->getString($i, "id")."'";
}

$sql = "SELECT d1.company_id, COUNT(d1.id) AS c FROM product d1 WHERE d1.status =0";
if($ids != "")
{
	$sql = $sql." AND (".$ids.")";
}else{
	$sql = $sql." AND 1=0";
}
$sql = $sql." GROUP BY d1.company_id";
$msg->add("query", $sql);
$dt_product = $appSession->getTier()->getTable($msg);

?>
<!-- Breadcrumb Section Start -->
<section class="breadscrumb-section pt-0">
	<div class="container-fluid-lg">
		<div class="row">
			<div class="col-12">
				<div class="breadscrumb-contain">
					<h2><?php echo $appSession->getLang()->find("Shop");?></h2>
					<nav>
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?php echo URL;?>shop_grid">
									<i class="fa-solid fa-house"></i>
								</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $appSession->getLang()->find("Shop");?></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Breadcrumb Section End -->

<!-- Grid Section Start -->
<section class="seller-grid-section">
	<div class="container-fluid-lg">
		<div class="row g-4">
			<?php 
			for($i =0; $i<$dt_company->getRowCount(); $i++)
			{
				$company_id = $dt_company->getString($i, "id");
				$company_name = $dt_company->getString($i, "commercial_name");
				$company_code = $dt_company->getString($i, "code");
				$address = $dt_company->getString($i, "address");
				$phone = $dt_company->getString($i, "phone");
				$document_id = $dt_company->getString($i, "document_id");
				$products = 0;
				for($j=0; $j<$dt_product->getRowCount(); $j++)
				{
					if($dt_product->getString($j, "product_id") == $company_id)
					{
						$products = $dt_product->getFloat($j, "c");
					}
				}
					
			?>
			<div class="col-xxl-4 col-md-6">
				<a href="<?php echo URL;?>shop/<?php echo $appSession->getTool()->validUrl($company_name); ?>/<?php echo $company_code;?>" class="seller-grid-box">
					<div class="grid-contain">
						<div class="seller-contact-details">
							<div class="saller-contact">
								<div class="seller-icon">
									<i class="fa-solid fa-map-pin"></i>
								</div>

								<div class="contact-detail">
									<h5><?php echo $appSession->getLang()->find("Address");?>: <span> <?php echo $address;?></span></h5>
								</div>
							</div>

							<div class="saller-contact">
								<div class="seller-icon">
									<i class="fa-solid fa-phone"></i>
								</div>

								<div class="contact-detail">
									<h5><?php echo $appSession->getLang()->find("Contact Us");?>: <span><?php echo $phone;?></span></h5>
								</div>
							</div>
						</div>
						<div class="contain-name">
							<div>
								<h6><?php echo $company_code;?></h6>
								<h3><?php echo $company_name;?></h3>
								<div class="product-rating">
									<ul class="rating">
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star" class="fill"></i>
										</li>
										<li>
											<i data-feather="star"></i>
										</li>
									</ul>
									<h6 class="theme-color ms-2">(26)</h6>
								</div>
								<span class="product-label"><?php echo $appSession->getFormats()->getINT()->format($products);?>  <?php echo $appSession->getLang()->find("Product");?></span>
							</div>

							<div class="grid-image">
								<?php
								if($document_id != "")
								{
								?>
								<img src="<?php echo URL;?>document/?id=<?php echo $document_id;?>&h=100" alt="" class="img-fluid">
								
								<?php
								}else{
								?>
								<img src="<?php echo URL;?>/assets/images/vendor-page/logo/1.png" alt="" class="img-fluid">
								<?php
								}
								?>
							</div>
						</div>
					</div>
				</a>
			</div>
			<?php
			}
			?>
			
		</div>

		<nav class="custome-pagination">
			<ul class="pagination justify-content-center">
				<li class="page-item disabled">
					<a class="page-link" href="javascript:void(0)" tabindex="-1">
						<i class="fa-solid fa-angles-left"></i>
					</a>
				</li>
				<li class="page-item active">
					<a class="page-link" href="javascript:void(0)">1</a>
				</li>
				<li class="page-item" aria-current="page">
					<a class="page-link" href="javascript:void(0)">2</a>
				</li>
				<li class="page-item">
					<a class="page-link" href="javascript:void(0)">3</a>
				</li>
				<li class="page-item">
					<a class="page-link" href="javascript:void(0)">
						<i class="fa-solid fa-angles-right"></i>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</section>