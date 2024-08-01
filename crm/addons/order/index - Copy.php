<?php
validUser($appSession);
$sql = "SELECT id, name, forecolor, backcolor FROM res_status WHERE status =0 AND table_id='sale_local' ORDER BY sequence ASC";
$msg = $appSession->getTier()->createMessage();
$msg->add("query", $sql);

$status = $appSession->getTier()->getArray($msg);


?>


<div class="page_title">
	<div class="row align-items-center mx-0">
		<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0">
			<div class="title_inner d-flex">
				<button type="button" class="btn"><a data-toggle="modal" data-target="#add_people"><?php echo $appSession->getLang()->find("Add New");?></a></button>
				<div id="reportrange" style="cursor: pointer; width: 100%">
					<img src="<?php echo URL;?>assets/images/calendar.png" border="0" /></i>&nbsp;
					<span></span> <img src="<?php echo URL;?>assets/images/menu_down.png" border="0" />
				</div>
				
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 p-0 d-flex" style="vertical-align: top;">
			<form class="search_box col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 p-0 px-lg-3 mt-3 mt-lg-0 pb-3 pb-md-0 ml-auto" onsubmit="return false">
				<div class="form-group d-flex">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="zmdi zmdi-search"></i></div>
					</div>
					<input type="text" class="form-control" placeholder="Search" id="editsearch" onKeyDown="if(event.keyCode == 13){loadOrders();}" >
					
				</div>
			</form>
		</div>
	</div>
	<br>
	<div class="row align-items-center mx-0">
		<div class="col-12">
		<table border="0" cellpadding="2" cellspacing="4">
				
					<tr>
					<?php
					for($i = 0; $i<count($status); $i++)
					{
						
					?>
					<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3];?>; color:<?php echo $status[$i][2];?>"><input onchange="loadOrders()" type="checkbox" name="status[]" value="<?php echo $status[$i][0];?>"></td>
					<td nowrap="nowrap" style="background-color:<?php echo $status[$i][3];?>; color:<?php echo $status[$i][2];?>"><?php echo $status[$i][1];?>&nbsp;</td>
					<td>&nbsp;</td>
					<?php
					}
					?>
					</tr>
				</table>
		</div>
	</div>
</div>
<div class="order_container" id="pnOrders">
</div>
<script>
var fdate = "";
var tdate = "";
$( document ).ready(function() {
	var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		fdate = start.format('YYYY-MM-D') + ' 00:00:00.000';
		tdate = end.format('YYYY-MM-D')+ ' 23:59:59.999';
		loadOrders();
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
});
function loadOrders()
{
	var search= document.getElementById('editsearch').value;
	
	var _url = '<?php echo URL;?>addons/order/list/?search=' + encodeURIComponent(search);
	_url = _url + "&fdate=" + encodeURIComponent(fdate);
	_url = _url + "&tdate=" + encodeURIComponent(tdate);
	var x= document.getElementsByName('status[]');
	var status = '';
	for(var i=0; i<x.length; i++)
	{
		if(x[i].checked)
		{
			if(status != ''){
				status = status + ",";
			}
			status = status + x[i].value;
		}
		
	}
	_url = _url + "&status=" + encodeURIComponent(status);
	loadPage('pnOrders', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}
function loadSale(id)
{
	var _url = '<?php echo URL;?>addons/order/line/?sale_id=' + id;
	loadPage('contentView', _url, function(status, message)
	{
		if(status== 0)
		{
			
		}
		
	}, false);
}

</script>