<?php
class Product
{
	public $appSession;
	public $msg;
	public function __construct($appSession) {
		$this->appSession = $appSession;
		$this->msg = $appSession->getTier()->createMessage();
	}
	function categoryList()
	{
		$sql = "SELECT d1.id, d1.parent_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id";
		$sql = $sql." FROM product_category d1";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status=0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_category_name' AND lg.status =0)";
		$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d1.type='PRODUCT_CATEGORY'";
		$sql = $sql." AND d1.company_id='".$this->appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql." ORDER BY d1.sequence ASC, lg.description ASC, d1.name ASC";
	
		$this->msg->add("query", $sql);
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	function productGroup()
	{
		$sql = "SELECT d1.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name group_category_name";
		
		$sql = $sql." FROM product_group d1";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_group_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_group_category d3 ON(d1.category_id = d3.id)";
		$sql = $sql." WHERE d1.status =0";
		$sql = $sql." AND d1.company_id='".$this->appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql." ORDER BY d1.sequence ASC, lg.description ASC, d1.name ASC";
	
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	function productGroupApp()
	{
		$sql = "SELECT d1.id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d3.name group_category_name";
		
		$sql = $sql." FROM product_group d1";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_group_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_group_category d3 ON(d1.category_id = d3.id)";
		$sql = $sql." WHERE d1.status =0 AND d1.app=1";
		//$sql = $sql." AND d1.company_id='".$this->appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql." ORDER BY d1.sequence ASC, lg.description ASC, d1.name ASC";
	
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	
	function productByGroup($name)
	{
		$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id, m.description, d7.name AS attribute_name, d7.code AS attribute_code, d8.name attribute_category_name, d5.id AS price_id";
		$sql = $sql.", (SELECT SUM(product_count.quantity) FROM product_count WHERE product_count.status =0 AND product_count.rel_id = d5.id) AS unit_in_stock, d5.attribute_id"; 
		$sql = $sql." FROM product_group_product m";
		$sql = $sql." LEFT OUTER JOIN product d1 ON(m.product_id = d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status=0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_group d3 ON(m.group_id = d3.id)";
		$sql = $sql." LEFT OUTER JOIN product_group_category d4 ON(d3.category_id = d4.id)";
		$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d5.attribute_id = d7.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
		$sql = $sql." WHERE m.status =0 AND d1.status =0 AND d1.publish = 1 AND d3.status =0 AND d4.name='".$name."'";
		//$sql = $sql." AND d1.company_id='".$this->appSession->getConfig()->getProperty("company_id")."'";
		$sql = $sql." ORDER BY d1.sequence ASC";
		
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	function productByGroupById($appSession, $id,  $limit)
	{
		$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
		$sql = $sql.", d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock, d5.attribute_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.name AS second_unit_name"; 
		$sql = $sql.", d5.company_id, d10.commercial_name, d10.name AS company_name, d5.id AS price_id, d12.document_id AS price_document_id, d5.description, d14.name AS type_name, d15.unit_price AS unit_price_cust, d15.unit_id AS unit_id_cust, d15.currency_id AS currency_id_cust, d15.id AS price_id_cust, d16.unit_price AS unit_price_cat, d16.unit_id AS unit_id_cat, d16.currency_id AS currency_id_cat, d16.id AS price_id_cat";
		$sql = $sql." FROM product_group_product m LEFT OUTER JOIN product d1 ON(m.product_id= d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN res_company d10 ON(d5.company_id = d10.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_line d11 ON(d5.attribute_id = d11.id AND d11.rel_id=d1.id) ";
		
		$sql = $sql." LEFT OUTER JOIN poster d12 ON(d11.id = d12.rel_id AND d12.publish=1 AND d12.status =0)";
		$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d11.attribute_id = d7.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d7.unit_id = d9.id)";
		$sql = $sql." LEFT OUTER JOIN product_category d13 ON(d1.category_id = d13.id)";
		$sql = $sql." LEFT OUTER JOIN product_type d14 ON(d11.type_id = d14.id)";
		$sql = $sql." LEFT OUTER JOIN product_price d15 ON(d1.id = d15.product_id AND d15.type='CUSTOMER' AND d15.status =0 AND d15.rel_id='".$appSession->getConfig()->getProperty("customer_id")."' AND d15.publish=1 AND d15.attribute_id = d11.id AND d15.unit_id = d5.unit_id)";
		$sql = $sql." LEFT OUTER JOIN product_price d16 ON(d1.id = d16.product_id AND d16.type='CUSTOMER_CATEGORY' AND d16.status =0 AND d16.rel_id='".$appSession->getConfig()->getProperty("customer_category_id")."' AND d16.publish=1 AND d16.attribute_id = d11.id AND d16.unit_id = d5.unit_id)";
		$sql = $sql." WHERE d1.status =0 AND d1.publish = 1";
		$sql = $sql." AND m.group_id='".$id."' AND m.status =0";
		
		
		$sql = $sql." ORDER BY m.sequence ASC, d1.sequence ASC, d5.sequence ASC";
		$sql = $sql."";
		if($limit != -1)
		{
			$sql = $sql." LIMIT ".$limit;
		}
		
		
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	function productByGroupId($appSession, $id)
	{
		$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
		$sql = $sql.", d7.name AS attribute_name, d8.name attribute_category_name, (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock,  d5.attribute_id, d7.quantity AS factor, d7.unit_id AS second_unit_id, d9.name AS second_unit_name"; 
		$sql = $sql.", d5.company_id, d10.commercial_name, d10.name AS company_name, d5.id AS price_id, d12.document_id AS price_document_id, d5.description, d14.name AS type_name, d15.unit_price AS unit_price_cust, d15.unit_id AS unit_id_cust, d15.currency_id AS currency_id_cust, d15.id AS price_id_cust, d16.unit_price AS unit_price_cat, d16.unit_id AS unit_id_cat, d16.currency_id AS currency_id_cat, d16.id AS price_id_cat";
		$sql = $sql." FROM product_group_product m LEFT OUTER JOIN product d1 ON(m.product_id= d1.id)";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1 AND d2.status =0)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN res_company d10 ON(d5.company_id = d10.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_line d11 ON(d5.attribute_id = d11.id AND d11.rel_id=d1.id) ";
		
		$sql = $sql." LEFT OUTER JOIN poster d12 ON(d11.id = d12.rel_id AND d12.publish=1 AND d12.status =0)";
		$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d11.attribute_id = d7.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d9 ON(d7.unit_id = d9.id)";
		$sql = $sql." LEFT OUTER JOIN product_category d13 ON(d1.category_id = d13.id)";
		$sql = $sql." LEFT OUTER JOIN product_type d14 ON(d11.type_id = d14.id)";
		$sql = $sql." LEFT OUTER JOIN product_price d15 ON(d1.id = d15.product_id AND d15.type='CUSTOMER' AND d15.status =0 AND d15.rel_id='".$appSession->getConfig()->getProperty("customer_id")."' AND d15.publish=1 AND d15.attribute_id = d11.id AND d15.unit_id = d5.unit_id)";
		$sql = $sql." LEFT OUTER JOIN product_price d16 ON(d1.id = d16.product_id AND d16.type='CUSTOMER_CATEGORY' AND d16.status =0 AND d16.rel_id='".$appSession->getConfig()->getProperty("customer_category_id")."' AND d16.publish=1 AND d16.attribute_id = d11.id AND d16.unit_id = d5.unit_id)";
		$sql = $sql." WHERE d1.status =0 AND d1.publish = 1";
		$sql = $sql." AND m.group_id='".$id."' AND m.status =0";
		
		
		$sql = $sql." ORDER BY m.sequence ASC, d1.sequence ASC, d5.sequence ASC";
		$sql = $sql."";
		
		$this->msg->add("query", $sql);
		
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	
	function productByCategory($category_id, $limit)
	{
		$sql = "SELECT d1.id, d1.category_id, d1.code, d1.name, lg.description AS name_lg, d2.document_id, d5.unit_price, d5.old_price, d5.unit_id, d6.name AS unit_name, d5.currency_id";
		$sql = $sql.", (SELECT SUM(product_count.quantity) FROM product_count LEFT OUTER JOIN res_company ON(product_count.company_id = res_company.id) WHERE product_count.status =0 AND product_count.rel_id= d5.id) AS unit_in_stock,  d7.quantity AS factor, d7.unit_id AS second_unit_id, d7.name AS attribute_name, d8.name attribute_category_name"; 
		$sql = $sql." FROM product d1";
		$sql = $sql." LEFT OUTER JOIN poster d2 ON(d1.id = d2.rel_id AND d2.publish=1)";
		$sql = $sql." LEFT OUTER JOIN res_lang_line lg ON(lg.lang_id='".$this->appSession->getConfig()->getProperty("lang_id")."' AND lg.rel_id = d1.id AND lg.name='product_name' AND lg.status =0)";
		$sql = $sql." LEFT OUTER JOIN product_price d5 ON(d1.id = d5.product_id AND d5.status =0 AND d5.publish = 1 AND d5.type='PRODUCT') LEFT OUTER JOIN product_unit d6 ON(d5.unit_id = d6.id)";
		$sql = $sql." LEFT OUTER JOIN attribute d7 ON(d5.attribute_id = d7.id)";
		$sql = $sql." LEFT OUTER JOIN attribute_category d8 ON(d7.category_id = d8.id)";

		$sql = $sql." WHERE d1.status =0 AND d1.publish = 1 AND d1.category_id='".$category_id."'";
		
		$sql = $sql." AND d1.company_id='".$this->appSession->getConfig()->getProperty("company_id")."'";
		
		$sql = $sql." ORDER BY d1.sequence ASC";
		if($limit != -1)
		{
			$sql = $sql." LIMIT ".$limit;
		}
		
		$this->msg->add("query", $sql);
		$dt = $this->appSession->getTier()->getTable($this->msg);
		return $dt;
	}
	public function findSeenId(){
		$session_id = $this->appSession->getConfig()->getProperty("session_id");
		$user_id = $this->appSession->getUserInfo()->getId();
		
		if($session_id == "")
		{
			$session_id = $this->appSession->getTool()->getId();
			$this->appSession->getConfig()->setProperty("session_id", $session_id);
			$this->appSession->getConfig()->save();
		}
		
		$sql = "SELECT d1.id FROM product_seen d1 WHERE d1.status = 0 AND (d1.session_id='".$session_id."' OR d1.create_uid='".$this->appSession->getUserInfo()->getId()."')";
		$this->msg->add("query", $sql);
		$seen_id = $this->appSession->getTier()->getValue($this->msg);
		if($seen_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("product_seen");
			$sale_id = $this->appSession->getTool()->getId();
			$builder->add("id", $sale_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("session_id", $session_id);
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$builder->add("customer_id", $this->appSession->getConfig()->getProperty("customer_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		return $seen_id;
	}
	public function addProductSeen($product_id){
		
		$seen_id = $this->findSeenId();
		$builder = $this->appSession->getTier()->createBuilder("product_seen_product");
		$id = $this->appSession->getTool()->getId();
		$builder->add("id", $id);
		$builder->add("seen_id", $seen_id);
		$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
		$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
		$builder->add("product_id", $product_id);
		$builder->add("status", 0);
		$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
		$sql = $this->appSession->getTier()->getInsert($builder);
		
		$this->msg->add("query", $sql);
		$this->appSession->getTier()->exec($this->msg);
		return $id;
		
	}
	
	public function findWishLitId(){
		$session_id = $this->appSession->getConfig()->getProperty("session_id");
		$user_id = $this->appSession->getUserInfo()->getId();
		
		if($session_id == "")
		{
			$session_id = $this->appSession->getTool()->getId();
			$this->appSession->getConfig()->setProperty("session_id", $session_id);
			$this->appSession->getConfig()->save();
		}
		
		$sql = "SELECT d1.id FROM product_wishlist d1 WHERE d1.status = 0 AND (d1.session_id='".$session_id."' OR d1.create_uid='".$this->appSession->getUserInfo()->getId()."')";
		$this->msg->add("query", $sql);
		$wishlist_id = $this->appSession->getTier()->getValue($this->msg);
		if($wishlist_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("product_wishlist");
			$sale_id = $this->appSession->getTool()->getId();
			$builder->add("id", $sale_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("session_id", $session_id);
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$builder->add("customer_id", $this->appSession->getConfig()->getProperty("customer_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		return $wishlist_id;
	}
	public function addProductWishList($product_id){
		
		$wishlist_id = $this->findWishLitId();
		$sql = "SELECT id FROM product_wishlist_product WHERE status =0 AND wishlist_id='".$wishlist_id."' AND product_id='".$product_id."'";
		$wishlist_product_id = $this->appSession->getTier()->getValue($this->msg);
		if($wishlist_product_id == "")
		{
			$builder = $this->appSession->getTier()->createBuilder("product_wishlist_product");
			$wishlist_product_id = $this->appSession->getTool()->getId();
			$builder->add("id", $wishlist_product_id);
			$builder->add("wishlist_id", $wishlist_id);
			$builder->add("create_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("write_uid", $this->appSession->getUserInfo()->getId());
			$builder->add("create_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("write_date", $this->appSession->getTier()->getDateString(), 'f');
			$builder->add("product_id", $product_id);
			$builder->add("status", 0);
			$builder->add("company_id", $this->appSession->getConfig()->getProperty("company_id"));
			$sql = $this->appSession->getTier()->getInsert($builder);
			
			$this->msg->add("query", $sql);
			$this->appSession->getTier()->exec($this->msg);
		}
		
		return $wishlist_product_id;
		
	}
	public function countProductWishList()
	{
		$wishlist_id = $this->findWishLitId();
		$sql = "SELECT COUNT(d1.id) FROM product_wishlist_product d1 WHERE d1.status =0 AND d1.wishlist_id='".$wishlist_id."'";
		$this->msg->add("query", $sql);
		$arr = $this->appSession->getTier()->getArray($this->msg);
		if(count($arr)>0 && $arr[0][0] != "")
		{
			return $arr[0][0];
		}
		return "0";
	}
	public function countProduct($productList)
	{
		$ids = "";
		for ($i = 0; $i < $productList->getRowCount(); $i++) {

            $id = $productList->getString($i, "id");
			if($ids != "")
			{
				$ids = $ids." OR ";
			}
			$ids = $ids." d1.product_id='".$id."'";
		}
		if($ids != "")
		{
			$sql = "SELECT d1.product_id, d1.unit_id, d1.attribute_id, d1.type_id, d1.quantity FROM product_count d1 WHERE d1.status =0 AND (".$ids.")";
			$this->msg->add("query", $sql);
			$arr = $this->appSession->getTier()->getArray($this->msg);
			for ($i = 0; $i < $productList->getRowCount(); $i++) 
			{
				$product_id = $productList->getString($i, "id");
				$attribute_id = $productList->getString($i, "attribute_id");
				$type_id = $productList->getString($i, "type_id");
				$unit_id = $productList->getString($i, "unit_id");
				$factor = $productList->getFloat($i, "factor");
				if($factor == 0){
					$factor = 1;
				}
				$quantity = 0;
				for($j=0; $j<count($arr); $j++)
				{
					
					
					if($product_id == $arr[$j][0])
					{
						if( $unit_id == $arr[$j][1] && $attribute_id == $arr[$j][2] && $type_id == $arr[$j][3])
						{
							
							$quantity = $quantity + floatval($arr[$j][4]);
						}else if($unit_id != $arr[$j][1]){
							if($attribute_id == $arr[$j][2] && $type_id == $arr[$j][3])
							{
								$quantity = $quantity + (floatval($arr[$j][4]) * $factor);
							}else if( $attribute_id == $arr[$j][2])
							{
								$quantity = $quantity + (floatval($arr[$j][4]) * $factor);
							}else if( $type_id == $arr[$j][3])
							{
								$quantity = $quantity + (floatval($arr[$j][4]) * $factor);
							}
							
						}
					}
					
					
				}
				echo $quantity."<br>";
				$productList->setValue($quantity, $i, "unit_in_stock");
			}
		}
		return $productList;
	}
	
}

?>