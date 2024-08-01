<?php
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
$search = "";
if(isset($_REQUEST['search']))
{
	$search = $_REQUEST['search'];
}
$sql = "SELECT d3.id AS employee_id, d3.code, d3.first_name, d3.middle_name, d3.last_name, d3.address, d3.mobile, p.document_id FROM res_user_company d1 LEFT OUTER JOIN res_user_group d2 ON(d1.group_id = d2.id) LEFT OUTER JOIN hr_employee d3 ON(d1.employee_id = d3.id) LEFT OUTER JOIN poster p ON(d3.id = p.rel_id AND p.status =0 AND p.publish =1) WHERE d1.status =0 AND d2.name='SELLER' AND d3.status =0";
if ($search != "") 
{
  $sql = $sql . " AND (" . $appSession->getTier()->buildSearch(["d1.code", "d3.first_name", "d3.middle_name", "d3.last_name"], $search) . ")";
}
$arr = $appSession->getTier()->paging($sql, $p, $ps, " d3.code ASC");




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
$dt_employee = $appSession->getTier()->getTable($msg);

$ids = "";
for($i =0; $i<$dt_employee->getRowCount(); $i++)
{
	if($ids != "")
	{
		$ids = $ids ." OR ";
	}
	$ids = $ids." rel_id='".$dt_employee->getString($i, "employee_id")."'";
}
$sql = "SELECT d1.rel_id, COUNT(d1.price_id) AS c FROM product_price_rel d1 WHERE d1.status =0 AND d1.type ='SELLER'";
if($ids != "")
{
	$sql = $sql." AND (".$ids.")";
}else{
	$sql = $sql." AND 1=0";
}
$sql = $sql." GROUP BY d1.rel_id";
$msg->add("query", $sql);
$dt_product = $appSession->getTier()->getTable($msg);


?>
<!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2><?php echo $appSession->getLang()->find("Seller");?></h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo URL;?>seller_grid">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $appSession->getLang()->find("Seller");?></li>
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
				for($i =0; $i<$dt_employee->getRowCount(); $i++)
				{
					$employee_id = $dt_employee->getString($i, "employee_id");
					$address = $dt_employee->getString($i, "address");
					$phone = $dt_employee->getString($i, "phone");
					$document_id = $dt_employee->getString($i, "document_id");
					$employee_name = $dt_employee->getString($i, "last_name")." ".$dt_employee->getString($i, "middle_name")." ".$dt_employee->getString($i, "first_name");
					$employee_code = $dt_employee->getString($i, "code");
					$products = 0;
					for($j=0; $j<$dt_product->getRowCount(); $j++)
					{
						if($dt_product->getString($j, "rel_id") == $employee_id)
						{
							$products = $dt_product->getFloat($j, "c");
						}
					}
					
				?>
				<div class="col-xxl-4 col-md-6">
                    <a href="<?php echo URL;?>seller_detail/?empid=<?php echo $employee_id;?>" class="seller-grid-box">
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
                                    <h6><?php echo $employee_code;?></h6>
                                    <h3><?php echo $employee_name;?></h3>
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
                                    <span class="product-label"><?php echo $appSession->getFormats()->getINT()->format($products);?> Products</span>
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
					<a class="page-link" href="javascript:void(0)" tabindex="-1" aria-disabled="true">
						<i class="fa-solid fa-angles-left"></i>
					</a>
				</li>
					<?php
					if($page_count == 0)
					{
						$page_count = 1;
					}
					for($i =0; $i<$page_count; $i++)
					{
					?>
					 <li class="page-item <?php if($i == $p){ ?> active<?php };?>"  >
						<a class="page-link" href="javascript:void(0)"><?php echo $i+1;?></a>
					</li>
		
					<?php
					}
					?>
					
		  
				<li class="page-item">
					<a class="page-link" href="javascript:void(0)">
						<i class="fa-solid fa-angles-right"></i>
					</a>
				</li>
			</ul>
		</nav>
        </div>
    </section>