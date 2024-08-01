<?php
require_once(ABSPATH.'api/Sale.php' );
include( ABSPATH .'app/lang/'.$appSession->getConfig()->getProperty("lang_id").'.php');
foreach($langs as $key => $item)
{
	$appSession->getLang()->setProperty($key, $item);				
}

$msg = $appSession->getTier()->createMessage();
$sale = new Sale($appSession);
$sale_id = $sale->findSaleId();
$dt = $sale->productListSaleId($sale_id);

?>
<div class="sc-head d-flex justify-content-between align-items-center">
            <div class="cart-count"><svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="20px" height="20px" viewBox="0 0 472.337 472.336" style="enable-background:new 0 0 472.337 472.336;"
                xml:space="preserve"><path d="M406.113,126.627c0-5.554-4.499-10.05-10.053-10.05h-76.377V91.715C319.684,41.143,278.543,0,227.969,0
                   c-50.573,0-91.713,41.143-91.713,91.715v24.862H70.45c-5.549,0-10.05,4.497-10.05,10.05L3.914,462.284
                   c0,5.554,4.497,10.053,10.055,10.053h444.397c5.554,0,10.057-4.499,10.057-10.053L406.113,126.627z M156.352,91.715
                   c0-39.49,32.13-71.614,71.612-71.614c39.49,0,71.618,32.13,71.618,71.614v24.862h-143.23V91.715z M146.402,214.625
                   c-9.92,0-17.959-8.044-17.959-17.961c0-7.269,4.34-13.5,10.552-16.325v17.994h14.337v-18.237
                   c6.476,2.709,11.031,9.104,11.031,16.568C164.363,206.586,156.319,214.625,146.402,214.625z M310.484,214.625
                   c-9.922,0-17.959-8.044-17.959-17.961c0-7.269,4.341-13.495,10.548-16.325v17.994h14.338v-18.241
                   c6.478,2.714,11.037,9.108,11.037,16.568C328.448,206.586,320.407,214.625,310.484,214.625z"/></svg>
                   <span><?php echo $sale->getItemCount();?> <?php echo $appSession->getLang()->find("Item");?></span>
                </div>
                <span onclick="cartclose()" class="close-icon"><i class="fas fa-times"></i></span>
        </div>
        <div class="cart-product-container">
			<?php
			$total = 0;
			$company_currency_id = $appSession->getConfig()->getProperty("currency_id");
			for($i =0; $i<$dt->getRowCount(); $i++)
			{
				$sale_product_id = $dt->getString($i, "id");
				$code = $dt->getString($i, "code");
				$name = $dt->getString($i, "name_lg");
				$document_id = $dt->getString($i, "document_id");
				if ($name == "") {
					$name = $dt->getString($i, "name");
				}
				$attribute_category_name = $dt->getString($i, "attribute_category_name");
				$attribute_name = $dt->getString($i, "attribute_name");
				$currency_id = $dt->getString($i, "currency_id");
				$quantity = $appSession->getTool()->toDouble($dt->getString($i, "quantity"));
				$unit_price = $appSession->getTool()->toDouble($dt->getString($i, "unit_price"));
				$amount = $quantity * $unit_price;
				$total = $total + $amount;
			?>
            <div class="cart-product-item">
                <div class="close-item"><a href="javascript:removeCard('<?php echo $sale_product_id;?>', function(status, message){ loadCard(); loadCardContent();})"><i class="fas fa-times"></i></a></div>
				
                <div class="row align-items-center">
                    <div class="col-6 p-0">
                        <div class="thumb">
                            <a href="#"><img src="<?php echo URL; ?>document/?id=<?php echo $document_id;?>&w=190&h=120" alt="<?php echo $name ?>"></a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="product-content">
                            <?php echo $name;?>
							<?php 
								if($attribute_category_name != "")
								{
									echo $attribute_category_name.": ".$attribute_name;
								}
								?>
                            <div class="product-cart-info">
                                <?php echo $quantity;?> x <?php echo $appSession->getCurrency()->format($currency_id, $unit_price);?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center" style="padding-top:4px" >
                    <div class="col-4">
                        <div class="price-increase-decrese-group d-flex">
                            <span class="decrease-btn">
                                <button type="button" <?php if($quantity>1){ ?> onclick="updateCard('<?php echo $sale_product_id;?>', <?php echo $quantity-1;?>, function(status, message){ loadCard(); loadCardContent();})"<?php } ?>
                                    class="btn quantity-left-minus">-
                                </button> 
                            </span>
                            <input type="text" name="quantity" class="form-controls input-number" value="<?php echo $quantity;?>">
                            <span class="increase">
                                <button type="button" onclick="updateCard('<?php echo $sale_product_id;?>', <?php echo $quantity + 1;?>, function(status, message){ loadCard(); loadCardContent();})"
                                    class="btn quantity-right-plus">+
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="product-price">
                             <span class="ml-4"> <?php echo $appSession->getCurrency()->format($currency_id, $amount);?></span>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			}
			?>
			<?php
						$sql = "SELECT d1.percent, d1.value, d1.category_id, d1.operator, d2.name";
						$sql = $sql." FROM account_service_line_local d1";
						$sql = $sql." LEFT OUTER JOIN account_service d2 ON(d1.service_id = d2.id)";
						$sql = $sql." WHERE d1.rel_id='".$sale_id."' AND d1.status =0 ORDER BY d1.sequence ASC";
						$msg->add("query", $sql);
						
						$serviceList = $appSession->getTier()->getArray($msg);
						$amount = $total;
						for($i =0; $i<count($serviceList); $i++)
						{
							$a = ($total * floatval($serviceList[$i][0])) + floatval($serviceList[$i][1]);
							if($serviceList[$i][3] == "+")
							{
								$amount =  $amount + $a;
								$total =  $total + $a;
							}else if($serviceList[$i][3] == "-")
							{
								$amount =  $amount -  $a;
								$total =  $total - $a;
							}else if($serviceList[$i][3] == "*")
							{
								$amount =  $amount *  $a;
								$total =  $total * $a;
							}else if($serviceList[$i][3] == "/")
							{
								$amount =  $amount /  $a;
								$total =  $total / $a;
							}
							$service_name = $serviceList[$i][4];
						?>
						 <div class="cart-product-item">
						<div class="row">
                                <div class="col-6">
								<?php echo $service_name;?>
								</div>
								 <div class="col-6"><?php echo $appSession->getCurrency()->format($appSession->getConfig()->getProperty("currency_id"), $a); ?>
								</div>
							</div>
							</div>
						 
						<?php
						}
						?>
                    </div>
        </div>
        <div class="cart-footer">
            
            <div class="cart-total">
               
                <p class="total-price d-flex justify-content-between">
                    <span><?php echo $appSession->getLang()->find("Total");?></span> 
                    <span><?php echo $appSession->getCurrency()->format($company_currency_id, $total);?></span>
                </p>
                <a href="<?php echo URL;?>checkout" class="procced-checkout"><?php echo $appSession->getLang()->find("Check Out");?></a>
            </div>
        </div>