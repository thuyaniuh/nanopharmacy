<?php


$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}
if($ac == "")
{
	$ac = "view";
}

if($ac == "view"){
	
	echo "1. Giới thiệu;2. Catalog;3. Hồ sơ năng lực của công ty;4. Cam kết dịch vụ;5. Chính sách bán hàng;6. Hình thức và chính sách thanh toán;7. Chính sách đổi trả hàng;8. Chính sách giao hàng";
}else if($ac == "report")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	$ids = array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9");
	$id = $ids[$id];
	
	?>
	<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>VIFOTEC</title>
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<style>
		
html{box-sizing:border-box}*,*:before,*:after{box-sizing:inherit}
/* Extract from normalize.css by Nicolas Gallagher and Jonathan Neal git.io/normalize */
html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
body{margin:0}
article,aside,details,figcaption,figure,footer,header,main,menu,nav,section{display:block}summary{display:list-item}
audio,canvas,progress,video{display:inline-block}progress{vertical-align:baseline}
audio:not([controls]){display:none;height:0}[hidden],template{display:none}
abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}
b,strong{font-weight:bolder}
dfn{font-style:italic}
mark{background:#ff0;color:#000}
small{font-size:80%}
sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}
sub{bottom:-0.25em}sup{top:-0.5em}
figure{margin:1em 40px}
code,kbd,pre,samp{font-size:1em}
hr{box-sizing:content-box;height:0;overflow:visible}
button,input,select,textarea,optgroup{font:inherit;margin:0}
optgroup{font-weight:bold}
button,input{overflow:visible}
button,select{text-transform:none}
button,[type=button],[type=reset],[type=submit]{-webkit-appearance:button}
button::-moz-focus-inner,[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner{border-style:none;padding:0}
button:-moz-focusring,[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring{outline:1px dotted ButtonText}
fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:.35em .625em .75em}
legend{color:inherit;display:table;max-width:100%;padding:0;white-space:normal}textarea{overflow:auto}
[type=checkbox],[type=radio]{padding:0}
[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}
[type=search]{-webkit-appearance:textfield;outline-offset:-2px}
[type=search]::-webkit-search-decoration{-webkit-appearance:none}
::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}
/* End extract */
html,body{
	 font-family: "Helvetica Neue", "Roboto", sans-serif;
	 font-size: 1em;
	 line-height: 1.4;
	 margin: 0;
	  color: rgba(0,0,0, 0.87);
	 text-align: left;
}

a {
  color: #2E86C1;
  font-weight: 500; 
  text-decoration: none;
}

a:hover {
  color: #1d5cff;
  text-decoration: none;
}

a:not([href]):not([class]) {
  color: inherit;
  text-decoration: none;
}
a:not([href]):not([class]):hover {
  color: inherit;
  text-decoration: none;
}

h1{font-size:36px}h2{font-size:30px}h3{font-size:24px}h4{font-size:20px}h5{font-size:18px}h6{font-size:16px}
h1,h2,h3,h4,h5,h6{ffont-weight:400;margin:10px 0}.wide{letter-spacing:4px}

hr {
  display: block;
  height: 1px;
  border: 0;
  border-top: 1px solid #ccc;
  margin: 1em 0;
  padding: 0; }
 

.shadow--1dp {
  box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12); }
  
.shadow--2dp {
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12); }

.shadow--3dp {
  box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.14), 0 3px 3px -2px rgba(0, 0, 0, 0.2), 0 1px 8px 0 rgba(0, 0, 0, 0.12); }

.shadow--4dp {
  box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12), 0 2px 4px -1px rgba(0, 0, 0, 0.2); }

.shadow--6dp {
  box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12), 0 3px 5px -1px rgba(0, 0, 0, 0.2); }

.shadow--8dp {
  box-shadow: 0 8px 10px 1px rgba(0, 0, 0, 0.14), 0 3px 14px 2px rgba(0, 0, 0, 0.12), 0 5px 5px -3px rgba(0, 0, 0, 0.2); }

.shadow--16dp {
  box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2); }

.shadow--24dp {
  box-shadow: 0 9px 46px 8px rgba(0, 0, 0, 0.14), 0 11px 15px -7px rgba(0, 0, 0, 0.12), 0 24px 38px 3px rgba(0, 0, 0, 0.2); }
  
.table{
	
	border: 1px solid rgba(0, 0, 0, 0.12);
	border-collapse: collapse;
	width:100%;
  }
.table thead {
    padding-bottom: 3px; 
   }
   
.table tbody tr {
   
    height: 26px;
    transition-duration: 0.28s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-property: background-color;
}
.table tbody tr:hover {
    background-color: #eeeeee; }
.table th {
    
    vertical-align: bottom;
    text-overflow: ellipsis;
    font-size: 14px;
    font-weight: bold;
    line-height: 24px;
    letter-spacing: 0;
    height: 36px;
    padding-bottom: 8px;
    box-sizing: border-box; }
.table td:first-of-type, .table th:first-of-type {
      padding-left: 8px; }
.table td:last-of-type, .table th:last-of-type {
      padding-right: 8px; }
.table td {
   
    vertical-align: middle;
    border-top: 1px solid rgba(0, 0, 0, 0.12);
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    padding-top: 6px;
	padding-left: 8px;
	padding-right: 8px
    box-sizing: border-box; }
	
.table th {
vertical-align: bottom;
text-overflow: ellipsis;
font-size: 14px;
font-weight: bold;
line-height: 24px;
letter-spacing: 0;
height: 32px;
font-size: 12px;
padding-top: 6px;
box-sizing: border-box; }


.btn, .button{
background: transparent;
  border: none;
  border-radius: 2px;
  color: rgb(0,0,0);
  position: relative;
  height: 32px;
  margin: 0;
  min-width: 32px;
  padding: 0 16px;
  display: inline-block;
  font-size: 16px;
  line-height: 1;
  letter-spacing: 0;
  overflow: hidden;
  will-change: box-shadow;
  transition: box-shadow 0.2s cubic-bezier(0.4, 0, 1, 1), background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  outline: none;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
  line-height: 36px;
  vertical-align: middle;
}

.button-raised{
	background: rgba(158,158,158, 0.20);
	border: none;
	  border-radius: 2px;
	  color: rgb(0,0,0);
	  position: relative;
	  height: 32px;
	  margin: 0;
	  min-width: 64px;
	  padding: 0 16px;
	  display: inline-block;
	  font-family: "Roboto", "Helvetica", "Arial", sans-serif;
	  font-size: 16px;
	  font-weight: 500;
	  line-height: 1;
	  letter-spacing: 0;
	  overflow: hidden;
	  will-change: box-shadow;
	  transition: box-shadow 0.2s cubic-bezier(0.4, 0, 1, 1), background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	  outline: none;
	  cursor: pointer;
	  text-decoration: none;
	  text-align: center;
	  line-height: 36px;
	  vertical-align: middle;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
}

.btn-primary 
{
	color: #ffffff;
	background-color: #6993FF;
}
.btn-primary:hover {
  color: #ffffff;
  background-color: #4377ff;
  border-color: #366eff;
}
.btn-primary:focus, .btn-primary.focus {
  color: #ffffff;
  background-color: #4377ff;
  border-color: #366eff;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(128, 163, 255, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(128, 163, 255, 0.5);
}
.btn-default 
{
	color: #181C32;
	background-color: #E4E6EF;
}
.btn-secondary 
{
	color: #181C32;
	background-color: #E4E6EF;
}
.btn-secondary:hover {
  color: #181C32;
  background-color: #ccd0e1;
  border-color: #c4c8dc;
}
.btn-secondary:focus, .btn-secondary.focus {
  color: #181C32;
  background-color: #ccd0e1;
  border-color: #c4c8dc;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(197, 200, 211, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(197, 200, 211, 0.5);
}

.btn-success 
{
	color: #ffffff;
    background-color: #1BC5BD;
}
.btn-success:hover {
  color: #ffffff;
  background-color: #16a39d;
  border-color: #159892;
}
.btn-success:focus, .btn-success.focus {
  color: #ffffff;
  background-color: #16a39d;
  border-color: #159892;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(61, 206, 199, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(61, 206, 199, 0.5);
}


.btn-info 
{
	color: #ffffff;
	background-color: #8950FC;
}
.btn-info:hover {
  color: #ffffff;
  background-color: #702afb;
  border-color: #671efb;
}
.btn-info:focus, .btn-info.focus {
  color: #ffffff;
  background-color: #702afb;
  border-color: #671efb;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(155, 106, 252, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(155, 106, 252, 0.5);
}

.btn-warning 
{
	color: #181C32;
	background-color: #FFA800;
}
.btn-warning:hover {
  color: #ffffff;
  background-color: #d98f00;
  border-color: #cc8600;
}
.btn-warning:focus, .btn-warning.focus {
  color: #ffffff;
  background-color: #d98f00;
  border-color: #cc8600;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(220, 147, 8, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(220, 147, 8, 0.5);
}
.btn-danger 
{
	color: #ffffff;
	background-color: #F64E60;
}
.btn-danger:hover {
  color: #ffffff;
  background-color: #f42a3f;
  border-color: #f41d34;
}
.btn-danger:focus, .btn-danger.focus {
  color: #ffffff;
  background-color: #f42a3f;
  border-color: #f41d34;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(247, 105, 120, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(247, 105, 120, 0.5);
}

.btn-light 
{
	color: #181C32;
	background-color: #F3F6F9;
}
.btn-light:hover {
  color: #181C32;
  background-color: #dae3ec;
  border-color: #d1dde8;
}
.btn-light:focus, .btn-light.focus {
  color: #181C32;
  background-color: #dae3ec;
  border-color: #d1dde8;
  -webkit-box-shadow: 0 0 0 0.2rem rgba(210, 213, 219, 0.5);
  box-shadow: 0 0 0 0.2rem rgba(210, 213, 219, 0.5);
}


.badge,.tag{background-color:#000;color:#fff;display:inline-block;padding-left:8px;padding-right:8px;text-align:center}.badge{border-radius:50%}

.tooltip,.display-container{position:relative}.tooltip .text{display:none}.tooltip:hover .text{display:inline-block}
.ripple:active{opacity:0.5}.ripple{transition:opacity 0s}
.input{

  border: 1px solid rgba(0,0,0, 0.12);
  display: block;
  font-size: 16px;
  margin: 0;
  padding: 4px;
  width: 100%;
  height: 32px;
  background: none;
  text-align: left;
  color: inherit;
}
.textarea{

  border: 1px solid rgba(0,0,0, 0.12);
  display: block;
  font-size: 16px;
  margin: 0;
  padding: 4px;
  width: 100%;
  background: none;
  text-align: left;
  color: inherit;
}
.select{
  border: 1px solid rgba(0,0,0, 0.12);
  display: block;
  font-size: 16px;
  margin: 0;
  padding: 4px;
  width: 100%;
  height: 32px;
  background: none;
  text-align: left;
  color: inherit;
}
.input:focus, .select:focus , .textarea:focus{
    outline-color: #6993FF; 
 }
.check, .checkbox,.radio{
	position: relative;
   vertical-align: middle;
   display: inline-block;
  box-sizing: border-box;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(0,0,0, 0.54);
  border-radius: 2px;
}
 
.dropdown-content{
	display: none;
	position: absolute;
	background: rgb(255,255,255);
	min-width: 160px;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
  will-change: transform;
  transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	z-index: 100;
}
.dropdown-content a {
  padding: 0 16px;
  outline-color: rgb(189,189,189);
  text-decoration: none;
  display: block;
  font-size: 14px;
  font-weight: 400;
  letter-spacing: 0;
  text-decoration: none;
  cursor: pointer;
  height: 32px;
  line-height: 32px;
  white-space: nowrap;
}
.dropdown-content a:hover {background-color: rgb(238,238,238);}
.dropdown-hover:hover .dropdown-content {
  display: block;
}
.dropdown-click:active .dropdown-content {
  display: block;
}

.sidebar{height:100%;width:200px;background-color:#fff;position:fixed!important;z-index:1;overflow:auto}
.main,#main{transition:margin-left .4s}
.full-width {
  width: 100%; }
.modal{z-index:3;display:none;padding-top:30px;position:fixed;left:0;top:0;width:100%;height:100%;overflow:auto; box-shadow: 0 9px 46px 8px rgba(0, 0, 0, 0.14), 0 11px 15px -7px rgba(0, 0, 0, 0.12), 0 24px 38px 3px rgba(0, 0, 0, 0.2);
}
.modal-content{margin:auto;background-color:#fff;position:relative;padding:0;outline:0;width:100%; border: 1px solid #888; box-shadow:0 1px 2px 0 rgba(0,0,0,0.16),0 1px 5px 0 rgba(0,0,0,0.12)}
.responsive{display:block;overflow-x:auto}
.container:after,.container:before,.panel:after,.panel:before,.row:after,.row:before,.row-padding:after,.row-padding:before,
.cell-row:before,.cell-row:after,.clear:after,.clear:before,.bar:before,.bar:after{content:"";display:table;clear:both}
.col,.half,.third,.twothird,.threequarter,.quarter{float:left;width:100%}
@media (min-width:601px){.col.m1{width:8.33333%}.col.m2{width:16.66666%}.col.m3,.quarter{width:24.99999%}.col.m4,.third{width:33.33333%}
.col.m5{width:41.66666%}.col.m6,.half{width:49.99999%}.col.m7{width:58.33333%}.col.m8,.twothird{width:66.66666%}
.col.m9,.threequarter{width:74.99999%}.col.m10{width:83.33333%}.col.m11{width:91.66666%}.col.m12{width:99.99999%}}
@media (min-width:993px){.col.l1{width:8.33333%}.col.l2{width:16.66666%}.col.l3{width:24.99999%}.col.l4{width:33.33333%}
.col.l5{width:41.66666%}.col.l6{width:49.99999%}.col.l7{width:58.33333%}.col.l8{width:66.66666%}
.col.l9{width:74.99999%}.col.l10{width:83.33333%}.col.l11{width:91.66666%}.col.l12{width:99.99999%}}
.rest{overflow:hidden}.stretch{margin-left:-16px;margin-right:-16px}
.content,.auto{margin-left:auto;margin-right:auto}.content{max-width:980px}.auto{max-width:1140px}
.cell-row{display:table;width:100%}.cell{display:table-cell}
.cell-top{vertical-align:top}.cell-middle{vertical-align:middle}.cell-bottom{vertical-align:bottom}
.hide{display:none!important}.show-block,.show{display:block!important}.show-inline-block{display:inline-block!important}
@media (max-width:1205px){.auto{max-width:95%}}
@media (max-width:600px){.modal-content{margin:0 10px;width:auto!important}.modal{padding-top:30px}
.dropdown-hover.mobile .dropdown-content,.dropdown-click.mobile .dropdown-content{position:relative}	
.hide-small{display:none!important}.mobile{display:block;width:100%!important}.bar-item.mobile,.dropdown-hover.mobile,.dropdown-click.mobile{text-align:center}
.dropdown-hover.mobile,.dropdown-hover.mobile .btn,.dropdown-hover.mobile .button,.dropdown-click.mobile,.dropdown-click.mobile .btn,.dropdown-click.mobile .button{width:100%}}
@media (max-width:768px){.modal-content{width:500px}.modal{padding-top:50px}}
@media (min-width:993px){.modal-content{width:900px}.hide-large{display:none!important}
.sidebar.collapse{display:block!important}}
@media (max-width:992px) and (min-width:601px){.hide-medium{display:none!important}}
@media (max-width:992px){.sidebar.collapse{display:none}.main{margin-left:0!important;margin-right:0!important}.auto{max-width:100%}}
.top,.bottom{position:fixed;width:100%;z-index:1}.top{top:0}.bottom{bottom:0}
.overlay{position:fixed;display:none;width:100%;height:100%;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,0.5);z-index:2}
.display-topleft{position:absolute;left:0;top:0}.display-topright{position:absolute;right:0;top:0}
.display-bottomleft{position:absolute;left:0;bottom:0}.display-bottomright{position:absolute;right:0;bottom:0}
.display-middle{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%)}
.display-left{position:absolute;top:50%;left:0%;transform:translate(0%,-50%);-ms-transform:translate(-0%,-50%)}
.display-right{position:absolute;top:50%;right:0%;transform:translate(0%,-50%);-ms-transform:translate(0%,-50%)}
.display-container:hover .display-hover{display:block}.display-container:hover span.display-hover{display:inline-block}.display-hover{display:none}
.display-position{position:absolute}
.circle{border-radius:50%}

.container,.panel{padding:0.01em 0px}.panel{margin-top:16px;margin-bottom:16px}
.codespan{color:crimson;background-color:#f1f1f1;padding-left:4px;padding-right:4px;font-size:110%}
.card {
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-flex-direction: column;
      -ms-flex-direction: column;
          flex-direction: column;
  min-height: 200px;
  overflow: hidden;
  width: 330px;
  z-index: 1;
  position: relative;
  background: rgb(255,255,255);
  border-radius: 2px;
  box-sizing: border-box; }
  
.card-title {
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
  color: rgb(0,0,0);
  display: block;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-justify-content: stretch;
      -ms-flex-pack: stretch;
          justify-content: stretch;
  line-height: normal;
  padding: 16px 16px;
  -webkit-perspective-origin: 165px 56px;
          perspective-origin: 165px 56px;
  -webkit-transform-origin: 165px 56px;
          transform-origin: 165px 56px;
  box-sizing: border-box; }
  .card-title.card--border {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1); }

.card-title-text {
  -webkit-align-self: flex-end;
      -ms-flex-item-align: end;
          align-self: flex-end;
  color: inherit;
  display: block;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  font-size: 24px;
  font-weight: 300;
  line-height: normal;
  overflow: hidden;
  -webkit-transform-origin: 149px 48px;
          transform-origin: 149px 48px;
  margin: 0; }

.card-subtitle-text {
  font-size: 14px;
  color: rgba(0,0,0, 0.54);
  margin: 0; }
 
 .card__menu {
  position: absolute;
  right: 16px;
  top: 16px; }
  

.opacity,.hover-opacity:hover{opacity:0.60}.opacity-off,.hover-opacity-off:hover{opacity:1}
.opacity-max{opacity:0.25}.opacity-min{opacity:0.75}
.greyscale-max,.grayscale-max,.hover-greyscale:hover,.hover-grayscale:hover{filter:grayscale(100%)}
.greyscale,.grayscale{filter:grayscale(75%)}.greyscale-min,.grayscale-min{filter:grayscale(50%)}
.sepia{filter:sepia(75%)}.sepia-max,.hover-sepia:hover{filter:sepia(100%)}.sepia-min{filter:sepia(50%)}
.tiny{font-size:10px!important}.small{font-size:12px!important}.medium{font-size:15px!important}.large{font-size:18px!important}
.xlarge{font-size:24px!important}.xxlarge{font-size:36px!important}.xxxlarge{font-size:48px!important}.jumbo{font-size:64px!important}
.left-align{text-align:left!important}.right-align{text-align:right!important}.justify{text-align:justify!important}.center{text-align:center!important}
.border-0{border:0!important}
.border{border:1px solid #888!important}
.border-top{border-top:1px solid #888!important}
.border-bottom{border-bottom:1px solid #888!important}
.border-left{border-left:1px solid #888!important}
.border-right{border-right:1px solid #888!important}
.section,.code{margin-top:16px!important;margin-bottom:16px!important}
.margin{margin:16px!important}.margin-top{margin-top:16px!important}.margin-bottom{margin-bottom:16px!important}
.margin-left{margin-left:16px!important}.margin-right{margin-right:16px!important}
.padding-small{padding:4px 8px!important}.padding{padding:8px 16px!important}.padding-large{padding:12px 24px!important}
.padding-top-small{padding-top:8px}
.padding-16{padding-top:16px!important;padding-bottom:16px!important}.padding-24{padding-top:24px!important;padding-bottom:24px!important}
.padding-32{padding-top:32px!important;padding-bottom:32px!important}.padding-48{padding-top:48px!important;padding-bottom:48px!important}
.padding-64{padding-top:64px!important;padding-bottom:64px!important}
.left{float:left!important}.right{float:right!important}
.transparent,.hover-none:hover{background-color:transparent!important}
.hover-none:hover{box-shadow:none!important}

.label {
  padding: 2px 2px 2px 0;
  display: inline-block;
}
.input-group-prepend,
.input-group-append,
.input-group {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex; }
  .input-group-prepend .btn,
  .input-group-append .btn {
    position: relative;
    z-index: 2; }
    .input-group-prepend .btn:focus,
    .input-group-append .btn:focus {
      z-index: 3; }
  .input-group-prepend .btn + .btn,
  .input-group-prepend .btn + .input-group-text,
  .input-group-prepend .input-group-text + .input-group-text,
  .input-group-prepend .input-group-text + .btn,
  .input-group-append .btn + .btn,
  .input-group-append .btn + .input-group-text,
  .input-group-append .input-group-text + .input-group-text,
  .input-group-append .input-group-text + .btn {
    margin-left: -1px; }

.input-group-prepend {
  margin-right: -1px; }

.input-group-append {
  margin-left: -1px; }
  .input-group {
  margin-left: -1px; }
  
 .pagination {
  display: inline-block;
}

.pagination a {
  color: #181C32;
  float: left;
  padding: 4px 16px;
  text-decoration: none;
}
.pagination a.active {
   
   border-color: #159892;
}
.pagination a:hover{background-color: #ddd;}

.bar{width:100%;overflow:hidden}.center .bar{display:inline-block;width:auto}
.bar .bar-item{padding:8px 8px;float:left;width:auto;border:none;display:block;outline:0}


.tab {
  overflow: hidden;
}

/* Style the buttons that are used to open the tab content */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 6px 8px;
  transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
  
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding-top: 16px;
  border-top: 1px solid #ccc;
}
.content-header
{
	padding-left:16px;
	padding-right:16px;
	padding-top:6px;
	padding-bottom:6px;
	width: 100%;
	border-bottom: 1px solid #ccc;
	background-color: rgb(245,245,245)
}

.content-body
{
	
	position:fixed!important;
	right:0px;
	overflow:auto;
	padding-top:16px;
	padding-right:8px;
	padding-left:238px;
	padding-bottom:150px;
	width: 100%;
	height:100%;
}
.content-body-forecolor
{
	
}
.content-body-backcolor
{
	padding-top:16px;
	padding-bottom:72px;
	background-color: rgb(255,255,255);
}

@media (max-width:993px){
	.content-body{padding-left:8px; left:0px; right:8px;}
}
.content-footer
{
	position: fixed;
	bottom: 0;
	padding-left:16px;
	padding-right:16px;
	padding-top:6px;
	padding-bottom:6px;
	width: 100%;
	border-top: 1px solid #ccc;
	background-color: rgb(245,245,245)
}
.form-group{
	padding:4px 8px!important
}
.form-group label {
  font-size: 1rem;
  font-weight: 400;
  color: #3F4254; 
 }
 
.nav {
  margin: 0;
  padding: 0;
  list-style-type: none
}
.nav a {
	display: block;
	text-decoration: none;
	outline-color: rgb(189,189,189);
	font-size: 14px;
	font-weight: 400;
	letter-spacing: 0;
	cursor: pointer;
	height: 32px;
	line-height: 32px;
	white-space: nowrap;
}
.nav a:hover {background-color: rgb(238,238,238);}

.caret {
  cursor: pointer;
  user-select: none; 
}
.caret::before {

  content: "+";
  font-size: 18px;
  display: inline-block;
  position: absolute;
  right: 12px;
}

.caret-down::before {
  font-size: 18px;
  content: "-";
  right: 12px;
}

.nested {
 
  display: none;
  list-style-type: none
}
 .nav-active {
  display: block;
}

menu {
	margin: 0px auto; 
	text-align: left;
	
	padding: 0 ;
}

menu ul ul {
	display: none;
}

menu ul li:hover > ul {
	display: block;
}


menu ul {
	
	padding: 0 ;
	list-style: none;
	position: relative;
	display: inline-table;
	
}
menu ul:after {
	content: ""; clear: both; display: block;
}

menu ul li {
	float: left;
}
	
	menu ul li a {
		display: block; padding: 6px 40px;
		
	}
		
	
menu ul ul {
	background: rgb(255,255,255);
	text-decoration: none;
	position: absolute; top: 100%;
	min-width: 160px;
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
  will-change: transform;
  transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
	menu ul ul li {
		float: none; 
		position: relative;
	}
		menu ul ul li a {
			 padding: 0 16px;
outline-color: rgb(189,189,189);
text-decoration: none;
display: block;
font-size: 14px;
font-weight: 400;
letter-spacing: 0;
text-decoration: none;
cursor: pointer;
height: 32px;
line-height: 32px;
white-space: nowrap;
		}	
			menu ul ul li a:hover {
				background-color: rgb(238,238,238);
			}
	
menu ul ul ul {
	position: absolute; left: 100%; top:0;
}

.sub-module{
	border:0;
  }
.sub-module td {
    height:32px;
	min-width:90px;
}
.sub-module a {
    padding:0 16px;
	text-decoration: none;
	display: block;
	font-size: 14px;
	font-weight: 400;
	letter-spacing: 0;
	cursor: pointer;
	width:100%
	height: 32px;
	line-height: 32px;
	white-space: nowrap;
}
.sub-module a:hover 
{
    background-color: #eeeeee; 
}
input[readonly]{
  background-color: #F3F6F9;
}

.columns {
  float: left;
  padding: 8px;
}
@media only screen and (max-width: 600px) {
  .columns {
    width: 100%;
  }
}

.collapsible {
  background-color: rgb(238,238,238);
  cursor: pointer;
  padding: 8px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.collapsible-active, .collapsible:hover {
  background-color: rgb(189,189,189);
}

.collapsible:after {
  content: '\002B';
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.collapsible-active:after {
  content: "\2212";
}

.collapsible-content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
 
}
.search_input{
  width: 80px;
  outline: none;
  border-width: 0 0 0;
  border-color: blue
  -webkit-transition: width 0.4s ease-in-out;
  transition: width 0.4s ease-in-out;
}

.search_input:focus-within{
  width: 200px;
  border-width: 0 0 1px;
}
.autocomplete {
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;

  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}


.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}


	
		</style>
	</head>
<body class="content" style="padding:10px">
<?php
if($id == "1")
{
?>
	C&Ocirc;NG  TY C&#7892; PH&#7846;N VIFOTEC</strong><br />
  Tr&#7909; s&#7903;: T&#7847;ng 1, 130 Nguy&#7877;n &#272;&#7913;c C&#7843;nh, P. T&#432;&#417;ng Mai, Q.  Ho&agrave;ng Mai, TP. H&agrave; N&#7897;i<br />
  &#272;i&#7879;n  tho&#7841;i: 0243.202.9199 &ndash; 0935.386.788<br />
  Zalo: <a href="https://zalo.me/0935386788">https://zalo.me/0935386788</a> Fosacha Food<br />
  Facebook: <a href="https://www.facebook.com/vfscfood">https://www.facebook.com/vfscfood</a> Chu&#7895;i  an to&agrave;n th&#7921;c ph&#7849;m Vi&#7879;t Nam<br />
  Website: <a href="https://vifotec.com/">https://vifotec.com/</a> C&ocirc;ng  ty C&#7893; ph&#7847;n Vifotec<br />
  Email:  info@vifotec.com<br />
  M&atilde; s&#7889; thu&#7871;: 0109385090, c&#7845;p ng&agrave;y 21/10/2020 do S&#7903; KH &amp;  &#272;T TP. H&agrave; N&#7897;i c&#7845;p.<br />
  <strong>C&Acirc;U  CHUY&#7878;N C&#7910;A VIFOTEC - CAM K&#7870;T CH&#7844;T L&#431;&#7906;NG</strong><br />
  <strong>TH&#7920;C  PH&#7848;M XANH </strong><br />
  C&aacute;c s&#7843;n ph&#7849;m n&ocirc;ng s&#7843;n &#273;&#432;&#7907;c n&acirc;ng niu, theo d&otilde;i v&agrave; ch&#259;m s&oacute;c t&#7915;ng  ng&agrave;y ngay t&#7915; khi c&ograve;n l&agrave; nh&#7919;ng h&#7841;t gi&#7889;ng nh&#7887;. Ch&uacute;ng t&ocirc;i mang &#273;&#7871;n s&#7843;n ph&#7849;m an  to&agrave;n, h&#432;&#417;ng v&#7883; t&#432;&#417;i ngon v&agrave; h&#417;n th&#7871; n&#7919;a l&agrave; s&#7921; th&#7845;u hi&#7875;u nh&#7919;ng th&#7921;c ph&#7849;m tr&ecirc;n  b&agrave;n &#259;n c&#7911;a b&#7841;n.<br />
  <strong>MUA S&#7854;M  &ldquo;KH&Ocirc;NG CH&#7840;M&rdquo; </strong><br />
  S&#7843;n ph&#7849;m s&#7841;ch, xu&#7845;t x&#7913; r&otilde; r&agrave;ng, v&agrave; h&#417;n th&#7871; n&#7919;a - b&#7841;n kh&ocirc;ng  c&#7847;n &ldquo;ch&#7841;m&rdquo; m&agrave; v&#7851;n c&oacute; th&#7875; y&ecirc;n t&acirc;m v&#7873; th&#7921;c ph&#7849;m cho gia &#273;&igrave;nh.<br />
  <strong>G&Igrave;N GI&#7918;  B&#7842;N S&#7854;C N&Ocirc;NG NGHI&#7878;P VI&#7878;T </strong><br />
  Kh&ocirc;ng ch&#7881; th&#7845;u hi&#7875;u th&#7921;c ph&#7849;m tr&ecirc;n m&acirc;m c&#417;m c&#7911;a b&#7841;n, ch&uacute;ng  t&ocirc;i c&ograve;n th&#7845;u hi&#7875;u nh&#7919;ng v&#7845;t v&#7843; c&#7911;a ng&#432;&#7901;i n&ocirc;ng d&acirc;n. T&#7841;i VIFOTEC, nh&#7919;ng kinh nghi&#7879;m  tinh tu&yacute; nh&#7845;t c&#7911;a n&#7873;n n&ocirc;ng nghi&#7879;p Vi&#7879;t k&#7871;t h&#7907;p v&#7899;i c&ocirc;ng ngh&#7879; Blockchain th&#7901;i &#273;&#7841;i  4.0 t&#7841;o n&ecirc;n nh&#7919;ng s&#7843;n ph&#7849;m &#273;&#7863;c bi&#7879;t gi&aacute; tr&#7883; cho c&#7843; n&ocirc;ng d&acirc;n v&agrave; ng&#432;&#7901;i ti&ecirc;u d&ugrave;ng.<br />
  <strong>CH&Iacute;NH  S&Aacute;CH AN TO&Agrave;N TH&#7920;C PH&#7848;M</strong><br />
  Vifotec &#273;&#7843;m b&#7843;o m&#7885;i ngu&#7891;n l&#7921;c, duy tr&igrave; hi&#7879;u l&#7921;c h&#7879; th&#7889;ng qu&#7843;n  l&yacute; an to&agrave;n th&#7921;c ph&#7849;m, cung c&#7845;p th&#7921;c ph&#7849;m an to&agrave;n theo nguy&ecirc;n t&#7855;c t&#7915; trang tr&#7841;i  &#273;&#7871;n b&agrave;n &#259;n c&#7911;a ng&#432;&#7901;i ti&ecirc;u d&ugrave;ng.<br />
  Ch&uacute;ng t&ocirc;i cam k&#7871;t: <br />
  1. Cam kết phát triển nền nông nghiệp sạch Việt Nam. FOSACHA FOOD không chỉ cung cấp thực phẩm tươi ngon, đảm bảo sức khỏe và dinh dưỡng mà còn góp phần phát triển nền nông nghiệp bền vững, cải thiện môi trường canh tác, tạo công ăn việc làm, thu nhập ổn định cho nông dân. Nông nghiệp sạch, thực phẩm sạch là một sự thay đổi cần thiết nhằm cải thiện sức khỏe người Việt và thúc đẩy phát triển nền nông nghiệp Việt Nam;<br>
  2. Xây dựng, duy trì và cải tiến Hệ thống quản lý an toàn thực phẩm theo tiêu chuẩn ISO 22000:2018;<br>
  3. Thực hiện tuân thủ theo tiêu chuẩn, tài liệu, duy trì hồ sơ, định kỳ đánh giá, thực hiện hành động khắc phục sự không phù hợp, cải tiến liên tục;<br>
  4. Dịch vụ khách hàng chu đáo;<br>
  5. Đầu tư nhà xưởng, trang thiết bị, công nghệ sản xuất đáp ứng nhu cầu của khách hàng;<br>
  6. Thực hiện tốt các chương trình tiên quyết và các nguyên tắc HACCP, cung cấp cho khách hàng các sản phẩm đảm bảo chất lượng, an toàn thực phẩm;<br>
  7. Huấn luyện đào tạo cho toàn thể cán bộ nhân viên thấu hiểu và tuân thủ thực hiện theo chính sách này;<br>
<?php
}else if($id == "2")
{
?>
  <img src = "<?php echo URL;?>assets/VFT/VFT-1.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-2.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-3.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-4.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-5.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-6.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-7.png" width="100%"/><br>
	<img src = "<?php echo URL;?>assets/VFT/VFT-8.png" width="100%"/><br>
<?php

}else if($id == "3")
{
?>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-1.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-2.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-3.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-4.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-5.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-6.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-7.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-8.png" width="100%"/><br>
<img src = "<?php echo URL;?>assets/HOSONANGLUC/HOSONANG_LUC_CONG_TY_VIFOTEC-9.png" width="100%"/><br>
<?php
}else if($id == "4")
{
?>
<h4>Cam kết và dịch vụ </h4>
                        <p>
						
1. Cam kết hỗ trợ nông dân tiêu thụ sản phẩm<br>
Sản phẩm sau khi thu mua của các cơ sở sản xuất được sơ chế, bảo quản hàng hóa nông sản trong điều kiện thích hợp nhằm duy trì chất lượng dinh dưỡng và an toàn đối với nông sản thực phẩm tới tay người tiêu dùng.
Hỗ trợ phân phối nông sản thông qua hình thức giao dịch trực tuyến kết nối cung cầu giúp người nông dân trong tiêu thụ sản phẩm<br>
Hỗ trợ kết nối mua giống cây trồng và vật tư nông nghiệp với các địa chỉ tin cậy và hỗ trợ vật tư trên cơ sở đề nghị của đơn vị (nếu cần) thông qua việc ứng trước - trả sau khi thu hoạch sản phẩm.
Bảo vệ quyền lợi chính đáng của người nông dân khi có các tranh chấp liên quan tới nguồn gốc xuất xứ và chất lượng đối với sản phẩm ứng dụng VfSC.<br><br>

2. Cam kết về sản phẩm<br>
Tất cả nông sản là sản phẩm an toàn, có xuất xứ rõ ràng, minh bạch và được kiểm soát chặt chẽ thông qua phần mềm quản lý an toàn thực phẩm VfSC tại các trang trại.
Hoàn tiền giá trị hàng hoá bán ra nếu sản phẩm bán ra không đúng cam kết, sai nguồn gốc.
Các sản phẩm nông sản tới tay người tiêu dùng trong vòng 24 giờ kể từ khi thu hoạch.
Quy trình sơ chế, chế biến nông sản khép kín, đảm bảo an toàn vệ sinh thực phẩm theo quy định.<br><br>

3. Dịch vụ khách hàng chu đáo<br>
Khách hàng có thể đặt hàng mọi lúc qua trang web và ứng dụng di động FOSACHA FOOD.
Mọi thông tin về sản phẩm, đơn hàng đều có thể truy xuất bất cứ lúc nào thông qua trang web và ứng dụng bán hàng.
Tư vấn hoàn toàn miễn phí, bất cứ thắc mắc nào của khách hàng về sản phẩm đều được giải quyết.
Trung thực với khách hàng, chữ Tín luôn đặt lên hàng đầu.
Tuân thủ pháp luật Việt Nam về kinh doanh và bảo vệ quyền lợi người tiêu dùng.
Khách hàng được hưởng lợi ích từ các chương trình giảm giá, khuyến mại thường xuyên của FOSACHA FOOD

	</p>
<?php
}else if($id == "5")
{
?>
Chính sách bán hàng ứng dụng VFT FARMS:</span></b></p>


<section class="contact-section section-ptb">
    <div class="container">
        <div class="row">
          
            <div class="col-12">
                <div class="contact-info-wrapper">
                    <div class="contact-info">
                      <div class=WordSection1>

<p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:0in;text-align:center'><b style='mso-bidi-font-weight:
normal'><span style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-themecolor:text1'>Chính sách bán hàng ứng dụng VFT FARMS:<o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l2 level1 lfo5'><![if !supportLists]>
</p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level1 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span lang=vi style='font-size:12.0pt;
line-height:180%'><span style='mso-list:Ignore'>1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
lang=vi style='font-size:12.0pt;line-height:180%'>Khách hàng m&#7899;i, l&#7847;n
&#273;&#7847;u cài app:<o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>1.1.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Mỗi
		khách hàng, khi tải app </font><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font><font size="3" style="font-size: 12pt"> và đăng
		ký thành viên thành công sẽ có 1 mã số duy nhất theo
		số điện thoại và được gọi là “mã định danh
		VF”;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>1.2.</span></span><font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt">Khi
		khách hàng giới thiệu bạn bè, người thân của mình
		tải app </font></font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font ><font size="3" style="font-size: 12pt"> thì cung cấp “Mã VF” của mình cho người đó để </font></font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font ><font size="3" style="font-size: 12pt"> ghi nhận khách hàng mới này được ai giới thiệu. Có
		vậy, </font></font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font ><font size="3" style="font-size: 12pt"> mới có thể nhận diện người đã giới thiệu để
		cảm ơn;</font></font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>1.3.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Người
		nhận giới thiệu được tặng 01 voucher trị giá
		50.000VNĐ vào ví mua hàng và hưởng đầy đủ các chính
		sách từ </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>1.4.<span style='font:7.0pt "Times New Roman"'></span></span></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Khách
		hàng tự cài app và không có mã giới thiệu không được
		hưởng voucher này.</font></font></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level1 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span lang=vi style='font-size:12.0pt;
line-height:180%'><span style='mso-list:Ignore'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
lang=vi style='font-size:12.0pt;line-height:180%'>Chi&#7871;t kh&#7845;u trên
&#273;&#417;n hàng:<o:p></o:p></span></b></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:14.2pt;text-align:justify;line-height:180%'><font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
Farms</span></font></font><font size="3" style="font-size: 12pt"> có
các chính sách chiết khấu trên đơn hàng như sau:</font></font></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>2.1.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Chiết
		khấu trực tiếp trên tổng giá trị đơn hàng đặt,
		không giới hạn số lượng đơn hàng đặt, không giới
		hạn giá trị đơn hàng đặt - theo từng thời điểm
		khuyến mại được thông báo công khai trên trang web của </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-left:14.2pt;mso-add-space:
auto;text-align:justify;text-indent:0in;line-height:180%;mso-pagination:widow-orphan;
mso-list:l4 level2 lfo3;text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>2.2.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Chiết
		khấu cho từng mặt hàng trong mỗi đơn hàng theo từng
		thời điểm khuyến mại được thông báo công khai trên
		trang web của </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">;</font></font></p>

<p class=MsoListParagraphCxSpLast style='margin-left:.5in;mso-add-space:auto;
text-align:justify;text-indent:-.25in;line-height:180%;mso-pagination:widow-orphan;
mso-list:l4 level1 lfo3;text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><b><span
lang=vi style='font-size:12.0pt;line-height:180%'><span style='mso-list:Ignore'>3.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span></span></b><![endif]><b
style='mso-bidi-font-weight:normal'><span lang=vi style='font-size:12.0pt;
line-height:180%'>“Ví VF” – &#272;i ch&#7907; An toàn – Ti&#7879;n l&#7907;i –
Nhanh chóng<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></b></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:14.2pt;text-align:justify;line-height:180%'><span lang=vi
style='font-size:12.0pt;line-height:180%;mso-themecolor:text1'></span><font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
Farms</span></font></font><font ><font size="3" style="font-size: 12pt"> khuyến khích khách hàng sử dụng ví diện tử “VF”
nhằm giúp khách hàng có thể thực hiện giao dịch thanh
toán trực tuyến dễ dàng bởi các tiện ích sau:</font></font></font></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1;mso-bidi-font-weight:bold'><span style='mso-list:Ignore'>3.1.<span
style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1'></span><font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt">Thanh
		toán tiện lợi, an toàn, nhanh chóng;</font></font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1;letter-spacing:-.2pt;mso-bidi-font-weight:bold'><span style='mso-list:
Ignore'>3.2.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1;letter-spacing:-.2pt'></span><span
lang=vi style='font-size:12.0pt;line-height:180%;letter-spacing:-.2pt'><font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt"><span style="letter-spacing: -0.2pt">Chia
		sẻ ví điện tử VF cho các thành viên trong gia đình
		cùng sử dụng, giúp</span></font></font><font size="3" style="font-size: 12pt"><span style="letter-spacing: -0.2pt"> quản lý chi tiêu, tối ưu nhất hiệu suất mua sắm của
		các thành viên trong gia đình (ví dụ khi một người sử
		dụng “ví điện tử VF” thì mọi thành viên khác
		trong gia đình có thể sử dụng “ví điện tử VF”
		thanh toán để mua thực phẩm cho bữa cơm gia đình mà
		không cần dùng tiền mặt); </span></font></font><span
style='mso-themecolor:text1;mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>


<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:0in;margin-left:14.2pt;margin-bottom:.0001pt;mso-add-space:auto;
text-align:justify;text-indent:0in;line-height:180%;mso-pagination:widow-orphan;
mso-list:l4 level2 lfo3;text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1;mso-bidi-font-weight:bold'><span style='mso-list:Ignore'>3.3.<span
style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'><span style='mso-themecolor:text1'><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Việc
		chia sẻ ví chỉ thực hiện được khi các thành viên
		muốn sử dụng chung “ví VF“ cũng phải đăng ký là
		khách hàng của </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">;</font><font ><font size="3" style="font-size: 12pt"> Mọi đơn hàng do các khách hàng được chia sẻ ví mua
		và thanh toán sẽ được tính cho người chia sẻ ví;</font></font></font><span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-themecolor:
text1;mso-bidi-font-weight:bold'><span style='mso-list:Ignore'>3.4.<span
style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Khuyến
		mại 05% giá trị mỗi lần nạp ví, không giới hạn số
		lần nạp ví</font></font><span style='mso-themecolor:text1;mso-bidi-font-weight:
bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level1 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span lang=vi style='font-size:12.0pt;
line-height:180%'><span style='mso-list:Ignore'>4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
lang=vi style='font-size:12.0pt;line-height:180%'>Chính sách “Tr&#7891;ng rau
trên bàn phím”<span style='mso-spacerun:yes'>   </span><o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>4.1.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]>
  <font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt"> thực hiện chính sách “Trồng rau trên bàn bàn phím”:
		để cảm ơn các khách hàng giới thiệu khách hàng khác
		mua sản phẩm của </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>4.2.<span style='font:7.0pt "Times New Roman"'> <font size="2" style="font-size: 11pt"><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt"> không giới hạn số lần giới thiệu và số lần sử
		dụng mã giới thiệu;</font></font></span></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>4.3.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Người
		giới thiệu trực tiếp hưởng </font><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">chính
		sách “Hoa hồng tích lũy”. Mọi thông tin chi tiết </span></font><font size="3" style="font-size: 12pt">vui
		lòng liên hệ bộ phận CSKH - 0935 386 788 để biết thêm
		chi tiết.</font></font>
  <o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-left:.5in;mso-add-space:auto;
text-align:justify;text-indent:-.25in;line-height:180%;mso-pagination:widow-orphan;
mso-list:l4 level1 lfo3;text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span lang=vi style='font-size:12.0pt;
line-height:180%'><span style='mso-list:Ignore'>5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
lang=vi style='font-size:12.0pt;line-height:180%'>Tích l&#361;y &#273;i&#7875;m
- &#272;&#7893;i th&#432;&#7903;ng: <o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>5.1.</span></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Cứ
		1.000 VNĐ tiêu dùng trên </font><font ><font size="3" style="font-size: 12pt"><span lang="en-US" xml:lang="en-US">Vft
		Farms</span></font></font><font size="3" style="font-size: 12pt">,
		khách hàng tích lũy được 01 điểm “VF”, 01 điểm
		“VF” được tính bằng 01 VNĐ thanh toán (Tương đương
		1.000VNĐ được tích lỹ 01 VNĐ). Điểm “VF” sẽ được
		tính khi thanh toán đơn hàng thành công. Khi đơn hàng bị
		hủy hoặc trả lại thì điểm “VF” sẽ không được
		tính;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>5.2.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]><span
lang=vi style='font-size:12.0pt;line-height:180%'></span><font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Điểm
		“VF” được sử dụng để thanh toán đơn hàng dưới
		dạng điểm thưởng;</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;mso-bidi-font-weight:bold'><span
style='mso-list:Ignore'>5.3.<span style='font:7.0pt "Times New Roman"'> </span></span></span><![endif]>
  <font size="2" style="font-size: 11pt"><font size="3" style="font-size: 12pt">Điểm
		“VF” không có giá trị quy đổi thành tiền mặt và
		bất kỳ hình thức khuyến mại khác, không được
		chuyển nhượng dưới mọi hình thức.</font></font></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l4 level2 lfo3;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%'>
  <o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:14.2pt;text-align:justify'><span style='font-size:12.0pt;
line-height:107%;font-family:"Arial",sans-serif;mso-themecolor:
text1'>
  <o:p>&nbsp;</o:p></span><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-themecolor:text1'>
  <o:p></o:p>
  </span></p>

</div>
                        
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>




  <?php
}else if($id == "6")
{
?>
</p>

<h4>HÌNH THỨC VÀ CHÍNH SÁCH THANH TOÁN</h4>
                        <p><strong>Cách 1: Thanh toán  tiền mặt (giao hàng và thu tiền tận nơi).</strong><br />
Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
Bước 3: Xác nhận thông  tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh toán <strong>tiền  mặt</strong>.<br />
Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
Bước 5: Nhận hàng, kiểm tra sản phẩm trên đơn hàng, tiến  hành thanh toán tiền mặt trực tiếp cho shipper bằng giá trị trên hóa đơn.<br />
<strong>Cách 2: Thanh toán ghi nợ (Chỉ áp  dụng cho đơn hàng Thực Phẩm Sạch có kí hợp đồng mua bán với FOSACHA FOOD).</strong><br />
Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
Bước 3: Xác nhận  thông tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh  toán <strong>ghi nợ.</strong><br />
Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
Bước 5: Nhận hàng, kiểm tra sản phẩm trên đơn hàng, kí nhận  trên biên lai giao nhận xác nhận giao hàng thành công.<br />
Bước 6: Đối chiếu công nợ và thanh toán:</p>
                        
                        <ul>
                          <li>- FOSACHA FOOD chuyển nội dung đối chiếu công nợ cho  khách hàng trước ít nhất 05 ngày làm việc kể từ ngày kết thúc mỗi kì thanh  toán. Khách hàng xác nhận đối chiếu công nợ với FOSACHA FOOD trong vòng 02 ngày  làm việc kể từ ngày nhận được thông báo đối chiếu. FOSACHA FOOD chuyển hồ sơ  thanh toán cho khách hàng trong vòng 03 ngày làm việc kể từ ngày nhận được xác  nhận đối chiếu công nợ của khách hàng.</li>
                          <li>- Hoạt động thanh toán được thực hiện 01/tháng bằng  chuyển khoản:</li>
                          <li> a) Ngày 10 hàng tháng thanh toán cho các đơn hàng tháng.</li>
                          <li> b) Hình thức thanh toán chuyển khoản vào tài khoản của  công ty: 3666699999 - Ngân hàng TMCP Ngoại thương Việt Nam - Chi nhánh Nam Hà  Nội.</li>
                          <li> c) Nếu ngày thanh toán dự kiến trùng ngày nghỉ lễ hoặc thứ  7, chủ nhật sẽ được chuyển sang ngày đi làm tiếp theo của khách hàng.</li>
                          <li>- Hồ sơ thanh toán gồm:<strong></strong></li>
                          <li> a) Hóa đơn hợp lệ.</li>
                          <li> b) Các chứng cứ giao nhận hàng hóa có xác nhận của người  giao và người nhận giữa các bên.</li>
                          <li> c) Biên bản đối chiếu công nợ được 2 bên xác nhận qua  email/zalo.</li>
                          <li> d) Chậm thanh toán: Khách hàng sẽ chịu phí phạt theo lãi  suất ngân hàng từng thời điểm trả chậm.<strong></strong></li>
                        </ul>
                        <p><strong>Cách 3: Thanh toán bằng mã khuyến mãi.</strong><br />
                          Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
                          Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
                          Bước 3: Xác nhận  thông tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh  toán <strong>Mã khuyến mại.</strong><br />
  <em>Lưu ý</em>: Giá trị đơn hàng lớn hơn giá trị của mã  khuyến mãi, người mua có thể lựa chọn thêm thông tin thanh toán khác (có thể lựa  chọn nhiều hình thức thanh toán cùng lúc).</p>
                        <ul>
                          <li>Khách  lẻ: chọn thêm thông tin thanh toán <strong>tiền mặt, ví FSC, thanh toán VNPAY, điểm.</strong></li>
                          <li>Thực  phẩm sạch: chọn thêm thông tin thanh toán <strong>tiền mặt, thẻ ghi nợ, ví FSC,  thanh toán VNPAY, điểm.</strong></li>
                        </ul>
                        <p>Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
                          Bước 5: Nhận hàng, kiểm tra sản phẩm trên đơn hàng, tiến  hành thanh toán tiền mặt trực tiếp cho shipper bằng giá trị trên hóa đơn hoặc  kí nhận trên biên lai giao nhận xác nhận giao hàng thành công.<br />
  <strong>Cách 4: Thanh toán bằng Ví FSC.</strong><br />
                          Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
                          Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
                          Bước 3: Xác nhận  thông tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh  toán <strong>Ví FSC</strong><br />
  <em>Lưu ý:</em> Khách hàng nạp tiền vào Ví FSC thông qua  tài khoản ngân hàng hoặc quét mã QR Code của Vnpay.  Để thanh toán đơn hàng trên FOSACHA FOOD bằng  tài khoản Ví FSC, số dư trong Ví phải lớn hơn hoặc bằng số tiền của đơn hàng.  Trường hợp số dư Ví FSC thấp hơn giá trị đơn hàng, quý khách cần nạp thêm tiền  vào Ví để tiến hành thanh toán.<br />
                          Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
                          Bước 5: Nhận hàng, kiểm tra sản  phẩm trên đơn hàng, kí nhận trên biên lai giao nhận xác nhận giao hàng thành  công.                                                                     <br />
  <strong>Cách 5: Thanh toán qua cổng thanh toán </strong>VNPAY<sup>QR</sup><strong>.</strong><br />
                          Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
                          Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
                          Bước 3: Xác nhận  thông tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh  toán <strong>cổng thanh toán </strong>VNPAY<sup>QR</sup><strong> </strong></p>
                        <ul>
                          <li>Thanh  toán bằng <strong>mã QR</strong>, khách hàng dowload hoặc chụp màn hình điện thoại mã VNPAY<sup>QR</sup><strong>. </strong>Truy cập tài khoản  Internet Banking tại ngân hàng của khách hàng chọn mục <strong>QR pay, </strong>chọn hình  ảnh là mã VNPAY<sup>QR</sup><strong> , </strong>tiến hành quét mã  và tiến hành thanh toán qua tài khoản ngân hàng của khách hàng.<strong></strong></li>
                          <li>Thanh  toán bằng <strong>thẻ nội địa và tài khoản ngân hàng</strong>, khách hàng chọn ngân hàng  muốn sử dụng thanh toán, nhập số thẻ, tên chủ thẻ, ngày phát hành, xác nhận  thông tin thanh toán khách hàng và hoàn thành thanh toán.<strong></strong></li>
                          <li>Thanh  toán bằng <strong>thẻ Visa, thẻ tín dụng, </strong>khách hàng chọn ngân hàng muốn sử dụng  thanh toán, nhập số thẻ, tên chủ thẻ, ngày phát hành, ấn chọn tiếp tục, xác nhận  thông tin thanh toán khách hàng và hoàn thành thanh toán.<strong></strong></li>
                        </ul>
                        <p>Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
                          Bước 5: Nhận hàng, kiểm tra sản phẩm trên đơn hàng, kí  nhận trên biên lai giao nhận xác nhận giao hàng thành công.<br />
  <strong>Cách 6: Thanh toán bằng hình thức sử dụng điểm</strong><br />
                          Bước 1: Tìm hiểu  thông tin về sản phẩm, dịch vụ. Đăng ký thông tin tài khoản trên ứng dụng FOSACHA  FOOD.<br />
                          Bước 2: Lựa chọn sản  phẩm, đơn hàng.<br />
                          Bước 3: Xác nhận thông  tin, địa chỉ và số điện thoại giao nhận hàng, lựa chọn hình thức thanh toán <strong>sử  dụng điểm</strong><br />
                          Lưu ý: Giá trị đơn  hàng lớn hơn giá trị của điểm thưởng tích lũy, người mua có thể lựa chọn thêm  thông tin thanh toán khác (có thể lựa chọn nhiều hình thức thanh toán cùng  lúc).</p>
                        <ul>
                          <li>Khách  lẻ: chọn thêm thông tin thanh toán <strong>tiền mặt, mã khuyến mại, ví FSC, thanh  toán VNPAY.</strong></li>
                          <li>Thực  phẩm sạch: chọn thêm thông tin thanh toán <strong>tiền mặt, mã khuyến mại, thẻ ghi nợ,  ví FSC, thanh toán VNPAY.</strong></li>
                        </ul>
                        <p>Bước 4: FOSACHA  FOOD xác nhận đơn hàng và tiến hành giao hàng.<br />
                          Bước 5: Nhận hàng, kiểm tra sản phẩm trên đơn hàng, tiến  hành thanh toán tiền mặt trực tiếp cho shipper bằng giá trị trên hóa đơn hoặc  kí nhận trên biên lai giao nhận xác nhận giao hàng thành công.</p>
<?php
}else if($id == "7")
{
?>
<h4>Chính sách đổi trả hàng</h4>
                        <p><strong>1. FOSACHA  FOOD chấp nhận đổi trả</strong> <br />
- Sản phẩm  giao đến không nguyên vẹn, hư hại do quá trình vận chuyển. <br />
- Sản phẩm  giao sai không đúng với đơn đặt hàng ban đầu. <br />
- Sản phẩm  đã hết hạn sử dụng trước hoặc vào ngày giao hàng. <br />
- Sản phẩm  bị hỏng do lỗi của FOSACHA FOOD <br />
- Đáp ứng  điều kiện thời gian yêu cầu đổi trả hàng. <br />
<strong>- Sản  phẩm Thời gian yêu cầu đổi trả sau khi nhận hàng, ghi chú:</strong> <br />
Với tính  chất thực phẩm tươi sống (khách hàng vui lòng kiểm tra chất lượng sản phẩm ngay  khi nhận hàng). FOSACHA FOOD sẽ tiến hàng đổi/hoàn sản phẩm cho quý khách trong  vòng 24h kể từ thời điểm nhận được phản hồi. Yêu cầu: Sản phẩm đổi/hoàn được  bảo quản ngăn mát tủ lạnh đối với các sản phẩm tươi sống, sản phẩm chế biến,  sản phẩm làm từ sữa. Bảo quản ngăn đá tủ lạnh đối với các sản phẩm đông lạnh.  Đối với các sản phẩm khô bảo quản nơi khô ráo, thoáng mát. <br />
<strong>2. FOSACHA  FOOD cam kết chính sách Đổi Trả như sau</strong> <br />
- Bồi hoàn  100% những sản phẩm lỗi chất lượng dẫn tới hỏng không sử dụng được (Do FOSACHA  FOOD vận chuyển) bù vào những đơn hàng tiếp theo <br />
- Quý khách  có thể yêu cầu đổi trả, hoặc chuyển sang đơn hàng khác nếu sản phẩm lỗi chất  lượng, hư hỏng về tem, nhãn, sai nguồn gốc xuất xứ, hết hạn sử dụng, các sản  phẩm không đúng cam kết của FOSACHA FOOD. <br />
- Nếu quý  khách vẫn chưa hài lòng xin hãy phản hồi về bộ phận chăm sóc khách hàng của  chúng tôi qua: Hotline 0243.202.9199 hoặc số Zalo 0935.386.788<br />
Địa chỉ: 130  Nguyễn Đức Cảnh, phường Tương Mai, quận Hoàng Mai, thành phố Hà Nội. <br />
<strong>3. Quy  trình đổi trả hàng</strong> <br />
- Bạn gọi  đến hotline bộ phận CSKH của chúng tôi: Hotline 0243.202.9199 hoặc số Zalo  0935.386.788 thông báo yêu cầu đổi trả hàng<br />
- Thông báo  cho bộ phận CSKH số phiếu mua hàng và mặt hàng cần đổi trả.<br />
- Cung cấp  hình ảnh, thông tin liên quan đến sản phẩm đổi trả <br />
- Bộ phận  CSKH của FOSACHA FOOD xác nhận nhu cầu đổi trả. <br />
- Sản phẩm  gửi trả lại phải bao gồm phiếu mua hàng của FOSACHA FOOD. <br />
- Sản phẩm  đổi trả còn giữ bao gói sản phẩm, tem truy xuất của FOSACHA FOOD và thông tin  đơn hàng trên ứng dụng hợp lệ. <br />
- Sản phẩm  được yêu cầu đổi trả phải được kiểm tra chất lượng. FOSACHA FOOD sẽ đổi sản  phẩm mới hoặc hoàn tiền 100% toàn bộ giá trị đơn hàng trong vòng 24h nếu đáp  ứng các yêu cầu đổi trả. <br />
- Thông báo  đến bạn việc hoàn tất thủ tục đổi trả. <br />
<strong>4. Chi  phí đổi trả</strong> <br />
- Đối với  các sản phẩm đổi lại do lỗi của FOSACHA FOOD hoặc Nhà cung cấp, bạn sẽ được  miễn phí giao hàng tới địa điểm ghi trên phiếu yêu cầu đổi trả. <br />
<strong>Mọi thắc  mắc góp ý của quý khách sẽ được giải đáp trong thời gian sớm nhất. Xin cảm ơn  quý khách đã tin tưởng sử dụng sản phẩm từ FOSACHA FOOD</strong> </p>
<?php
}else if($id == "8")
{
?>
	<h4>Chính sách giao hàng FOSACHA
FOOD:
                              
                        </span></b></h4>
                        <p>
						

-</span><span
lang=vi style='font-size:12.0pt;line-height:180%'>
  Đối với đơn hàng trị giá &gt; 150.000đ: Miễn phí giao hàng khu vực nội thành Hà Nội. Nếu địa chỉ nhận hàng ở khu vực ngoại thành Hà Nội vui lòng liên hệ bộ phận CSKH - 0935 386 788 để biết thêm chi tiết.
  <o:p></o:p></span></p>
                        <p style="margin-bottom: 2.0pt"><span
style='font-size:12.0pt;line-height:180%;mso-ansi-language:EN-US'>- </span><span
lang=vi style='font-size:12.0pt;line-height:180%'>Đối với đơn hàng trị giá &lt; 150.000VNĐ, phí giao hàng được hỗ trợ tính theo khu vực nhận hàng:
                        <o:p></o:p>
                                              </span></p>
                        <p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l3 level1 lfo7;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;font-family:Wingdings;
mso-fareast-font-family:Wingdings;mso-bidi-font-family:Wingdings'><span
style='mso-list:Ignore'>&gt;<span style='font:7.0pt "Times New Roman"'></span></span></span><span
lang=vi style='font-size:12.0pt;line-height:180%'>Phí giao hàng 15.000 VNĐ tới các khu vực: Hoàng Mai, Hai Bà Trưng, Đống Đa, Cầu Giấy, Bắc Từ Liêm
<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l3 level1 lfo7;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;font-family:Wingdings;
mso-fareast-font-family:Wingdings;mso-bidi-font-family:Wingdings;letter-spacing:
-.3pt'><span style='mso-list:Ignore'> &gt;<span style='font:7.0pt "Times New Roman"'></span></span></span><span lang=vi style='font-size:12.0pt;
line-height:180%;letter-spacing:-.3pt'>Phí giao hàng 20.000 VNĐ tới các khu vực: Hoàn Kiếm, Nam Từ Liêm, Ba Đình, Tây Hồ, Hà Đông
    <o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;mso-add-space:auto;text-align:justify;
text-indent:0in;line-height:180%;mso-pagination:widow-orphan;mso-list:l3 level1 lfo7;
text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;font-family:Wingdings;
mso-fareast-font-family:Wingdings;mso-bidi-font-family:Wingdings;letter-spacing:
-.3pt'><span style='mso-list:Ignore'>&gt;<span style='font:7.0pt "Times New Roman"'></span></span></span><span lang=vi style='font-size:12.0pt;
line-height:180%'><span style='letter-spacing:-.4pt'>Phí giao hàng 30.000 VNĐ tới các khu vực Long Biên, Thanh Trì, Gia Lâm. Đông Anh, Hoài Đức, Đan Phượng</span><span style='letter-spacing:
-.3pt'>
<o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:0in;margin-left:14.2pt;margin-bottom:.0001pt;mso-add-space:auto;
text-align:justify;text-indent:0in;line-height:180%;mso-pagination:widow-orphan;
mso-list:l3 level1 lfo7;text-autospace:ideograph-numeric ideograph-other'><![if !supportLists]><span
lang=vi style='font-size:12.0pt;line-height:180%;font-family:Wingdings;
mso-fareast-font-family:Wingdings;mso-bidi-font-family:Wingdings;letter-spacing:
-.4pt'><span style='mso-list:Ignore'>&gt;<span style='font:7.0pt "Times New Roman"'>&nbsp;</span></span></span><span lang=vi style='font-size:12.0pt;
line-height:180%;letter-spacing:-.4pt'>Phí giao hàng 3.000 VNĐ/1km tới các khu vực ngoại thành Thanh Oai, Thường Tín, Chương Mỹ, Ứng Hào, Phú Xuyên, Mỹ Đức, Mê Linh, Quốc Oai, Thạch Thất, Sơn Tây, Ba Vì
    <o:p></o:p></span> <span style='letter-spacing:-.4pt'>
</span></span>


</p>
<?php
	}
}
?>
