<?php


$ac = "";
if(isset($_REQUEST['ac']))
{
	$ac = $_REQUEST['ac'];
}


if($ac == "view"){
	echo "1. Chính sách bán hàng;2. Cam kết hỗ trợ nông dân tiêu thụ sản phẩm;3. Chính sách đổi trả hàng;4. Cam kết phát triển nền nông nghiệp sạch Việt Nam;5. Chính sách giao hàng khu vực nội thành Hà Nội;6. Hình thức và chính sách thanh toán";
}else if($ac == "report")
{
	$id = "";
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
	}
	?>
	<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Điều khoản sử dung</title>
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
	<h4><b style='mso-bidi-font-weight:
normal'><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Arial&quot;,sans-serif;
color:black;mso-themecolor:text1">Chính sách bán hàng &#7913;ng d&#7909;ng FOSACHA
FOOD:</span></b></h4>
                        <p>
						
1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>Khách hàng m&#7899;i, l&#7847;n &#273;&#7847;u
cài app:<o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>1.1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Khi nhân viên bán hàng c&#7911;a Vifotec tr&#7921;c ti&#7871;p h&#432;&#7899;ng
d&#7851;n khách hàng (online ho&#7863;c offline) cài app Fosacha và mua hàng th&#7917;
trên app, </span><span style='font-size:12.0pt;line-height:107%;font-family:
"Arial",sans-serif;color:black;mso-themecolor:text1'>Fosacha s&#7869; cung c&#7845;p
01 Voucher tr&#7883; giá 50.000 VND &#273;&#7875; h&#432;&#7899;ng d&#7851;n
khách hàng n&#7841;p ti&#7873;n vào “ví &#273;i&#7879;n t&#7917; VF” và &#273;&#7863;t
hàng, thanh toán &#273;&#417;n hàng. T&#7841;i th&#7901;i &#273;i&#7875;m này,
khách hàng &#273;&#432;&#7907;c mua hàng hóa trên k&#7879; hàng c&#7911;a Fosacha.
Giá tr&#7883; &#273;&#417;n hàng không quá 50.000 VND. <span style='mso-bidi-font-weight:
bold'>N&#7871;u mua không h&#7871;t 50.000 VND thì s&#7889; ti&#7873;n còn l&#7841;i
s&#7869; &#273;&#432;&#7907;c b&#7843;o l&#432;u trong ví “VF” dùng &#273;&#7875;
thanh toán cho các &#273;&#417;n hàng ti&#7871;p theo;</span><b
style='mso-bidi-font-weight:normal'><o:p></o:p></b></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>1.2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Khách hàng t&#7921;
cài app không &#273;&#432;&#7907;c h&#432;&#7903;ng chính sách này;<b
style='mso-bidi-font-weight:normal'><o:p></o:p></b></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>1.3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>M&#7895;i
khách hàng, khi t&#7843;i app Fosacha s&#7869; có 1 mã s&#7889; duy nh&#7845;t
theo s&#7889; &#273;i&#7879;n tho&#7841;i &#273;&#432;&#7907;c g&#7885;i là “mã
&#273;&#7883;nh danh VF”.<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style='margin-top:2.0pt;margin-right:0in;margin-bottom:
2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;text-indent:-.25in;
mso-list:l2 level1 lfo5'><![if !supportLists]><b style='mso-bidi-font-weight:
normal'><span style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1'><span
style='mso-list:Ignore'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>Chi&#7871;t kh&#7845;u tr&#7921;c ti&#7871;p
trên &#273;&#417;n hàng:<o:p></o:p></span></b></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'><span
style='mso-spacerun:yes'>    </span>Vifotec có các chính sách chi&#7871;t kh&#7845;u
trên &#273;&#417;n hàng nh&#432; sau:<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>2.1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Chi&#7871;t kh&#7845;u tr&#7921;c ti&#7871;p trên t&#7893;ng giá tr&#7883;
&#273;&#417;n hàng &#273;&#7863;t, không gi&#7899;i h&#7841;n s&#7889; l&#432;&#7907;ng
&#273;&#417;n hàng &#273;&#7863;t, không gi&#7899;i h&#7841;n giá tr&#7883;
&#273;&#417;n hàng &#273;&#7863;t - theo t&#7915;ng th&#7901;i &#273;i&#7875;m
khuy&#7871;n m&#7841;i &#273;&#432;&#7907;c thông báo công khai trên trang web
c&#7911;a Vifotec;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>2.2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Chi&#7871;t kh&#7845;u theo h&#7841;ng khách hàng (xem thêm &#273;i&#7873;u
3);<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>2.3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Chi&#7871;t kh&#7845;u cho t&#7915;ng m&#7863;t hàng trong m&#7895;i
&#273;&#417;n hàng theo t&#7915;ng th&#7901;i &#273;i&#7875;m khuy&#7871;n m&#7841;i
&#273;&#432;&#7907;c thông báo công khai trên trang web c&#7911;a Vifotec;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>2.4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Chi&#7871;t kh&#7845;u
2% trên t&#7893;ng giá tr&#7883; &#273;&#417;n hàng &#273;&#7889;i v&#7899;i nh&#7919;ng
khách hàng s&#7917; d&#7909;ng “ví &#273;i&#7879;n t&#7917; VF” &#273;&#7875;
thanh toán, không gi&#7899;i h&#7841;n s&#7889; l&#7847;n s&#7917; d&#7909;ng
ví và h&#7841;ng khách hàng;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>2.5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Chi&#7871;t kh&#7845;u
2% trên &#273;&#417;n hàng v&#7899;i nh&#7919;ng &#273;&#417;n hàng &#273;&#7863;t
tr&#432;&#7899;c 24 gi&#7901; <span class=GramE>( 24</span> gi&#7901; tính t&#7915;
khi &#273;&#7863;t hàng &#273;&#7871;n th&#7901;i &#273;i&#7875;m mu&#7889;n nh&#7853;n
hàng) Ho&#7863;c chi&#7871;t kh&#7845;u 3% trên &#273;&#417;n hàng v&#7899;i nh&#7919;ng
&#273;&#417;n hàng &#273;&#7863;t tr&#432;&#7899;c 48 gi&#7901; (48 gi&#7901;
tính t&#7915; khi &#273;&#7863;t hàng &#273;&#7871;n th&#7901;i &#273;i&#7875;m
mu&#7889;n nh&#7853;n hàng).<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'><o:p>&nbsp;</o:p></span></b></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l2 level1 lfo5'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;mso-fareast-font-family:Arial;color:black;
mso-themecolor:text1'><span style='mso-list:Ignore'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>H&#7841;ng khách hàng:<o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>H&#7841;ng &#273;&#7891;ng: Là khách hàng tr&#7921;c ti&#7871;p mua t</span><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>rên 1 tri&#7879;u/ tháng thì &#273;&#432;&#7907;c
chi&#7871;t kh&#7845;u 2.0 % trên m&#7895;i &#273;&#417;n hàng khi mua hàng &#7903;
tháng k&#7871; ti&#7871;p;<span style='mso-spacerun:yes'>  </span><span
style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>H&#7841;ng B&#7841;c:
<span style='mso-bidi-font-weight:bold'>Là khách hàng tr&#7921;c</span> ti&#7871;p
mua trên 3 tri&#7879;u/ tháng thì &#273;&#432;&#7907;c chi&#7871;t kh&#7845;u
3.0% trên m&#7895;i &#273;&#417;n hàng khi mua hàng &#7903; tháng k&#7871; ti&#7871;p;<span
style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>H&#7841;ng
Vàng: <span style='mso-bidi-font-weight:bold'>Là khách hàng tr&#7921;c </span>ti&#7871;p
mua trên 6 tri&#7879;u/ tháng thì &#273;&#432;&#7907;c chi&#7871;t kh&#7845;u 4.0%
trên m&#7895;i &#273;&#417;n hàng khi mua hàng &#7903; tháng k&#7871; ti&#7871;p,
quà t&#7863;ng nhân d&#7883;p sinh nh&#7853;t, tham gia ch&#432;&#417;ng trình
farm tour mi&#7877;n phí;<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>H&#7841;ng Kim
c&#432;&#417;ng: <span style='mso-bidi-font-weight:bold'>Là khách hàng tr&#7921;c
</span>ti&#7871;p mua trên 10 tri&#7879;u/ tháng thì &#273;&#432;&#7907;c chi&#7871;t
kh&#7845;u 5.0% trên m&#7895;i &#273;&#417;n hàng khi mua hàng &#7903; tháng k&#7871;
ti&#7871;p, quà t&#7863;ng nhân d&#7883;p sinh nh&#7853;t, tham gia
ch&#432;&#417;ng trình farm tour mi&#7877;n phí;<span style='mso-bidi-font-weight:
bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Th&#7901;i
&#273;i&#7875;m &#273;&#7875; tính h&#7841;ng khách hàng &#273;&#432;&#7907;c
xét vào ngày làm vi&#7879;c cu&#7889;i cùng c&#7911;a tháng tr&#7915; khách
hàng thân thi&#7871;t &#273;&#432;&#7907;c xác &#273;&#7883;nh ngay sau khi
&#273;&#259;ng ký thành công;<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Khách hàng thân
thi&#7871;t: Là khách hàng &#273;&#259;ng ký thanh toán b&#7857;ng “ví &#273;i&#7879;n
t&#7917; VF”. Ch&#7845;p nh&#7853;n &#273;&#7875; s&#7889; d&#432; trong ví t&#7889;i
thi&#7875;u 300.000 VN&#272; thì &#273;&#432;&#7907;c chi&#7871;t kh&#7845;u thêm
3.0 % trên m&#7895;i &#273;&#417;n hàng (t&#7893;ng chi&#7871;t kh&#7845;u là
5%), quà t&#7863;ng nhân d&#7883;p sinh nh&#7853;t, tham gia ch&#432;&#417;ng
trình farm tour mi&#7877;n phí;<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.7.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Khách hàng thân
thi&#7871;t &#273;&#432;&#7907;c h&#432;&#7903;ng &#273;&#7847;y &#273;&#7911;
các &#432;u &#273;ãi t&#7915; chính sách nâng h&#7841;ng (m&#7909;c 3.1; 3.2;
3.3; 3.4), chính sách khuy&#7871;n khích s&#7917; d&#7909;ng ví “VF” (m&#7909;c
2.4);<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>3.8.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Các
ch&#432;&#417;ng trình farm tour s&#7869; công khai trên trang web c&#7911;a
Vifotec. Vifotec c&#7889; g&#7855;ng t&#7893; ch&#7913;c nhi&#7873;u farm tour
&#273;&#7875; khách hàng tr&#7843;i nghi&#7879;m. Khách hàng &#273;&#7911;
&#273;i&#7873;u ki&#7879;n tham gia farm tour n&#7871;u không tham gia l&#7847;n
g&#7847;n nh&#7845;t s&#7869; không &#273;&#432;&#7907;c b&#7843;o l&#432;u cho
nh&#7919;ng l&#7847;n sau.<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1;mso-bidi-font-weight:bold'><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l2 level1 lfo5'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;mso-fareast-font-family:Arial;color:black;

mso-themecolor:text1'><span style='mso-list:Ignore'>4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>“Ví VF” – &#272;i ch&#7907; <span
class=GramE>An</span> toàn – Ti&#7879;n l&#7907;i – Nhanh chóng<o:p></o:p></span></b></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:.25in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Vifotec
khuy&#7871;n khích khách hàng s&#7917; d&#7909;ng ví di&#7879;n t&#7917; “VF”
nh&#7857;m giúp khách hàng có th&#7875; th&#7921;c hi&#7879;n giao d&#7883;ch
thanh toán tr&#7921;c tuy&#7871;n d&#7877; dàng b&#7903;i các ti&#7879;n ích
sau:<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>4.1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Thanh toán ti&#7879;n
l&#7907;i, an toàn, nhanh chóng;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>4.2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Chia s&#7867; ví
&#273;i&#7879;n t&#7917; VF cho các thành viên trong gia &#273;ình cùng s&#7917;
d&#7909;ng, giúp qu&#7843;n lý chi tiêu, t&#7889;i &#432;u nh&#7845;t hi&#7879;u
su&#7845;t mua s&#7855;m c&#7911;a các thành viên trong gia &#273;ình (ví d&#7909;
khi m&#7897;t ng&#432;&#7901;i s&#7917; d&#7909;ng “ví &#273;i&#7879;n t&#7917;
VF” thì m&#7885;i thành viên khác trong gia &#273;ình có th&#7875; s&#7917; d&#7909;ng
“ví &#273;i&#7879;n t&#7917; VF” thanh toán &#273;&#7875; mua th&#7921;c ph&#7849;m
cho b&#7919;a c&#417;m gia &#273;ình mà không c&#7847;n dùng ti&#7873;n m&#7863;t).
<span style='mso-bidi-font-weight:bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>4.3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Vi&#7879;c
chia s&#7867; ví ch&#7881; th&#7921;c hi&#7879;n &#273;&#432;&#7907;c khi các
thành viên mu&#7889;n s&#7917; d&#7909;ng chung “ví <span class=GramE>VF“</span>c&#361;ng
ph&#7843;i &#273;&#259;ng ký là khách hàng c&#7911;a Vifotec; M&#7885;i
&#273;&#417;n hàng do các khách hàng &#273;&#432;&#7907;c chia s&#7867; ví mua
và thanh toán s&#7869; &#273;&#432;&#7907;c tính cho ng&#432;&#7901;i chia s&#7867;
ví &#273;&#7875; tính h&#7841;ng (m&#7909;c 3)<span style='mso-bidi-font-weight:
bold'><o:p></o:p></span></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>4.4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;&#432;&#7907;c
h&#432;&#7903;ng chính sách khuy&#7871;n khích s&#7917; d&#7909;ng “ví VF” c&#7911;a
Vifotec (2.4 và 3.5);<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>4.5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;&#7921;&#7907;c
nh&#7853;n ti&#7873;n m&#7863;t trong chính sách “tr&#7891;ng rau trên bàn phím”
(5.8);<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;
mso-bidi-font-weight:bold'><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraphCxSpFirst style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l2 level1 lfo5'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;mso-fareast-font-family:Arial;color:black;
mso-themecolor:text1'><span style='mso-list:Ignore'>5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>Chính sách “Tr&#7891;ng rau trên bàn phím” <span
style='mso-spacerun:yes'> </span><span style='mso-spacerun:yes'> </span><o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Fosacha th&#7921;c
hi&#7879;n chính sách “Tr&#7891;ng rau trên bàn bàn phím”: &#273;&#7875; c&#7843;m
&#417;n các khách hàng gi&#7899;i thi&#7879;u ng&#432;&#7901;i khác mua s&#7843;n
ph&#7849;m c&#7911;a Vifotec sau khi &#273;ã tr&#7843;i nghi&#7879;m s&#7843;n
ph&#7849;m c&#7911;a Vifotec;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Khi khách hàng
gi&#7899;i thi&#7879;u b&#7841;n bè, ng&#432;&#7901;i thân c&#7911;a mình t&#7843;i
app Fosacha thì cung c&#7845;p “Mã VF” c&#7911;a mình cho ng&#432;&#7901;i
&#273;ó &#273;&#7875; Vifotec ghi nh&#7853;n khách hàng m&#7899;i này
&#273;&#432;&#7907;c ai gi&#7899;i thi&#7879;u. Có v&#7853;y, Vifotec m&#7899;i
có th&#7875; nh&#7853;n di&#7879;n ng&#432;&#7901;i &#273;ã gi&#7899;i thi&#7879;u
&#273;&#7875; c&#7843;m &#417;n;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Ng&#432;&#7901;i
nh&#7853;n gi&#7899;i thi&#7879;u v&#7851;n &#273;&#432;&#7907;c h&#432;&#7903;ng
các chính sách &#432;u &#273;ãi c&#7911;a khách hàng m&#7899;i khi
&#273;&#259;ng ký thành viên thành công và h&#432;&#7903;ng &#273;&#7847;y
&#273;&#7911; các chính sách chi&#7871;t kh&#7845;u tr&#7921;c ti&#7871;p trên
&#273;&#417;n &#273;&#7863;t hàng theo quy &#273;&#7883;nh &#7903; m&#7909;c
2,3,4;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Ng&#432;&#7901;i
gi&#7899;i thi&#7879;u tr&#7921;c ti&#7871;p h&#432;&#7903;ng 60% c&#7911;a “hoa
h&#7891;ng bàn phím” trên &#273;&#417;n &#273;&#7863;t hàng c&#7911;a ng&#432;&#7901;i
nh&#7853;n gi&#7899;i thi&#7879;u khi ng&#432;&#7901;i nh&#7853;n gi&#7899;i
thi&#7879;u thanh toán thành công. 40% c&#7911;a “hoa h&#7891;ng bàn phím” &#273;&#432;&#7907;c
phân b&#7893; theo t&#7927; l&#7879; 60%-40% &#273;&#7871;n nh&#7919;ng
ng&#432;&#7901;i gi&#7899;i thi&#7879;u tr&#432;&#7899;c &#273;ó;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>S&#7889; “hoa
h&#7891;ng bàn phím” &#273;&#432;&#7907;c quy &#273;&#7883;nh thay &#273;&#7893;i
t&#7841;i t&#7915;ng th&#7901;i &#273;i&#7875;m nh&#432;ng không th&#7845;p
h&#417;n 5% giá tr&#7883; &#273;&#417;n hàng;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Vifotec không
gi&#7899;i h&#7841;n s&#7889; l&#7847;n gi&#7899;i thi&#7879;u và s&#7889; l&#7847;n
s&#7917; d&#7909;ng mã gi&#7899;i thi&#7879;u. <o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.7.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Giá tr&#7883; “hoa
h&#7891;ng bàn phím” &#273;&#432;&#7907;c th&#7889;ng kê vào ngày cu&#7889;i
cùng c&#7911;a tháng và t&#7921; &#273;&#7897;ng hoàn ti&#7873;n v&#7873; ví
&#273;i&#7879;n t&#7917; “VF” c&#7911;a ng&#432;&#7901;i gi&#7899;i thi&#7879;u.
Ng&#432;&#7901;i gi&#7899;i thi&#7879;u &#273;&#432;&#7907;c toàn quy&#7873;n s&#7917;
d&#7909;ng s&#7889; ti&#7873;n này &#273;&#7875; thanh toán các &#273;&#417;n
hàng ti&#7871;p theo.<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.8.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Ng&#432;&#7901;i gi&#7899;i thi&#7879;u mu&#7889;n nh&#7853;n ti&#7873;n “Hoa
h&#7891;ng bàn phím” b&#7855;t bu&#7897;c ph&#7843;i &#273;&#259;ng ký là khách
hàng thân thi&#7871;t c&#7911;a Vifotec và ch&#7881; &#273;&#432;&#7907;c rút
ti&#7873;n v&#7899;i s&#7889; ti&#7873;n l&#7899;n h&#417;n 3 tri&#7879;u trong
ví (ví “VF” gi&#7919; t&#7889;i thi&#7875;u 3 tri&#7879;u, tr&#432;&#7901;ng h&#7907;p
ví “VF” có s&#7889; d&#432; 10 tri&#7879;u ng&#432;&#7901;i gi&#7899;i thi&#7879;u
s&#7869; &#273;&#432;&#7907;c rút ti&#7873;n t&#7889;i &#273;a 7 tri&#7879;u và
3 tri&#7879;u s&#7869; &#273;&#432;&#7907;c gi&#7919; trong ví tiêu dùng trên
app Fosacha);<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.9.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Cung c&#7845;p
&#273;&#7847;y &#273;&#7911; thông tin liên l&#7841;c bao g&#7891;m: S&#272;T,
CCCD, Tài kho&#7843;n ngân hàng, ch&#7911; tài kho&#7843;n, chi nhánh. Các
thông tin &#273;&#259;ng ký ph&#7843;i trùng kh&#7899;p, thông tin &#273;&#432;&#7907;c
s&#7917; d&#7909;ng &#273;&#7875; nh&#7853;n hoa h&#7891;ng chi&#7871;t kh&#7845;u
tích l&#361;y, Vifotec không ch&#7883;u trách nhi&#7879;m v&#7873; các tr&#432;&#7901;ng
h&#7907;p sai thông tin &#273;&#259;ng ký. (Vifotec cam k&#7871;t b&#7843;o m&#7853;t
thông tin khách hàng theo cam k&#7871;t b&#7843;o m&#7853;t nêu trên).<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:49.65pt;mso-add-space:auto;text-align:justify;
text-indent:-28.35pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>5.10.<span style='font:7.0pt "Times New Roman"'>
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;i&#7873;u
ki&#7879;n chuy&#7875;n &#273;&#7893;i t&#7915; ng&#432;&#7901;i nh&#7853;n gi&#7899;i
thi&#7879;u thành ng&#432;&#7901;i gi&#7899;i thi&#7879;u &#273;&#7847;u tiên:<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l0 level1 lfo9'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1'><span
style='mso-list:Ignore'>a)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'>Khi khách hàng mu&#7889;n tr&#7903; thành ng&#432;&#7901;i gi&#7899;i thi&#7879;u
&#273;&#7847;u tiên thì ph&#7843;i &#273;&#432;&#7907;c s&#7921; &#273;&#7891;ng
ý c&#7911;a ng&#432;&#7901;i gi&#7899;i thi&#7879;u mình và có xác nh&#7853;n
gi&#7919;a 02 bên th&#7889;ng nh&#7845;t vi&#7879;c chuy&#7875;n &#273;&#7893;i.
Hình th&#7913;c thanh toán cho ng&#432;&#7901;i gi&#7899;i thi&#7879;u mình
&#273;&#7875; tách ra kh&#7887;i nhánh hi&#7879;n th&#7901;i do s&#7921; th&#7887;a
thu&#7853;n gi&#7919;a ng&#432;&#7901;i gi&#7899;i thi&#7879;u và ng&#432;&#7901;i
&#273;&#432;&#7907;c gi&#7899;i thi&#7879;u (Vifotec không ch&#7883;u trách nhi&#7879;m
v&#7873; s&#7921; th&#7887;a thu&#7853;n gi&#7919;a 02 bên).</span><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'> Khi tách kh&#7887;i nhánh, toàn b&#7897; nh&#7919;ng
ng&#432;&#7901;i &#273;&#432;&#7907;c gi&#7899;i thi&#7879;u b&#7903;i ng&#432;&#7901;i
mu&#7889;n tách nhánh s&#7869; &#273;&#432;&#7907;c tách theo;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l0 level1 lfo9'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1'><span
style='mso-list:Ignore'>b)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Ng&#432;&#7901;i
nh&#7853;n gi&#7899;i thi&#7879;u s&#7917; d&#7909;ng mã gi&#7899;i thi&#7879;u
cá nhân gi&#7899;i thi&#7879;u tr&#7921;c ti&#7871;p &#7913;ng d&#7909;ng Fosacha
thành công cho t&#7889;i thi&#7875;u 30 ng&#432;&#7901;i;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l0 level1 lfo9'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1'><span
style='mso-list:Ignore'>c)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>Không gi&#7899;i
h&#7841;n s&#7889; khách hàng tách nhánh.<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:.5in;mso-add-space:auto;text-align:justify;
text-indent:-.25in;mso-list:l2 level1 lfo5'><![if !supportLists]><b
style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;mso-fareast-font-family:Arial;color:black;
mso-themecolor:text1'><span style='mso-list:Ignore'>6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
</span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>Tích l&#361;y &#273;i&#7875;m - &#272;&#7893;i
th&#432;&#7903;ng: <o:p></o:p></span></b></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:35.45pt;mso-add-space:auto;text-align:justify;
text-indent:-21.25pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>6.1.<span style='font:7.0pt "Times New Roman"'>
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>C&#7913; 1.000
VN&#272; tiêu dùng trên app Fosacha, khách hàng tích l&#361;y &#273;&#432;&#7907;c
01 &#273;i&#7875;m “VF”. &#272;i&#7875;m “VF” s&#7869; &#273;&#432;&#7907;c tính
khi thanh toán &#273;&#417;n hàng thành công. Khi &#273;&#417;n hàng b&#7883; h&#7911;y
ho&#7863;c tr&#7843; l&#7841;i thì &#273;i&#7875;m “VF” s&#7869; không
&#273;&#432;&#7907;c tính;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:35.45pt;mso-add-space:auto;text-align:justify;
text-indent:-21.25pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>6.2.<span style='font:7.0pt "Times New Roman"'>
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;i&#7875;m
“VF” có th&#7875; &#273;&#432;&#7907;c chuy&#7875;n &#273;&#7893;i thành quà,
nâng h&#7841;ng khách <span class=GramE>hàng..</span>vv. Các chính sách tr&#7843;
th&#432;&#7903;ng &#273;&#432;&#7907;c công b&#7889; riêng trên trang web c&#7911;a
Vifotec;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpMiddle style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:35.45pt;mso-add-space:auto;text-align:justify;
text-indent:-21.25pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>6.3.<span style='font:7.0pt "Times New Roman"'>
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;i&#7875;m
“VF” &#273;&#432;&#7907;c tính t&#7915; ngày khách hàng kích ho&#7841;t app Fosacha
và có giá tr&#7883; trong 24 tháng. Sau 24 tháng, n&#7871;u khách hàng không s&#7917;
d&#7909;ng &#273;i&#7875;m “VF” &#273;&#7875; &#273;&#7893;i th&#432;&#7903;ng
thì h&#7879; th&#7889;ng s&#7869; t&#7921; h&#7911;y s&#7889; &#273;i&#7875;m “VF”
&#273;ã tích l&#361;y tr&#432;&#7899;c 24 tháng;<o:p></o:p></span></p>

<p class=MsoListParagraphCxSpLast style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:35.45pt;mso-add-space:auto;text-align:justify;
text-indent:-21.25pt;mso-list:l2 level2 lfo5'><![if !supportLists]><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
mso-fareast-font-family:Arial;color:black;mso-themecolor:text1;mso-bidi-font-weight:
bold'><span style='mso-list:Ignore'>6.4.<span style='font:7.0pt "Times New Roman"'>
</span></span></span><![endif]><span style='font-size:12.0pt;line-height:107%;
font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>&#272;i&#7875;m
“VF” không có giá tr&#7883; quy &#273;&#7893;i thành ti&#7873;n m&#7863;t và b&#7845;t
k&#7923; hình th&#7913;c khuy&#7871;n m&#7841;i khác, không &#273;&#432;&#7907;c
chuy&#7875;n nh&#432;&#7907;ng d&#432;&#7899;i m&#7885;i hình th&#7913;c.<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:14.2pt;text-align:justify'><span style='font-size:12.0pt;
line-height:107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:
text1'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
margin-bottom:2.0pt;margin-left:14.2pt;text-align:center'><span
style='font-size:12.0pt;line-height:107%;font-family:"Arial",sans-serif;
color:black;mso-themecolor:text1'>-------<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>7.<span
style='mso-tab-count:1'>         </span>Chính sách ship t&#7915; 8/1/2023
nh&#432; sau:<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>- Khách
hàng thu&#7897;c Qu&#7853;n Hoàng Mai, Hai Bà Tr&#432;ng, &#272;&#7889;ng
&#272;a, B&#7855;c T&#7915; Liêm, Nam T&#7915; Liêm, C&#7847;u Gi&#7845;y, Ba
&#272;ình, Tây H&#7891; mi&#7877;n ship t&#7845;t c&#7843; các &#273;&#417;n
hàng không phân bi&#7879;t giá tr&#7883;.<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>- Các Qu&#7853;n:
Hoàn Ki&#7871;m, Thanh Xuân và huy&#7879;n Thanh Trì mi&#7877;n ship v&#7899;i
&#273;&#417;n hàng có giá tr&#7883; trên 130.000 vn&#273;.<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>- Các Qu&#7853;n
Long Biên, Hà &#272;ông mi&#7877;n ship v&#7899;i &#273;&#417;n hàng có giá tr&#7883;
trên 150.000 vn&#273;.<o:p></o:p></span></p>

<p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;
margin-left:0in;text-align:justify'><span style='font-size:12.0pt;line-height:
107%;font-family:"Arial",sans-serif;color:black;mso-themecolor:text1'>- Các
&#273;&#417;n hàng không thu&#7897;c ph&#7841;m vi áp d&#7909;ng s&#7869; tính
phí 30.000 vn&#273;/&#273;&#417;n hàng.<o:p></o:p></span><br>
						</p>
	<?php
	}else if($id == "2")
	{
	?>
	<h4>Cam kết hỗ trợ nông dân tiêu thụ sản phẩm</h4>
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
	}else if($id == "3")
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
	}else if($id == "4")
	{
	?>
	<h4>Cam kết phát triển nền nông nghiệp sạch Việt Nam</h4>
                        <p>
						
FOSACHA FOOD không chỉ cung cấp thực phẩm tươi ngon, đảm bảo sức khỏe và dinh dưỡng mà còn góp phần phát triển nền nông nghiệp bền vững, cải thiện môi trường canh tác, tạo công ăn việc làm, thu nhập ổn định cho nông dân. Nông nghiệp sạch, thực phẩm sạch là một sự thay đổi cần thiết nhằm cải thiện sức khỏe người Việt và thúc đẩy phát triển nền nông nghiệp Việt Nam.



						</p>
	<?php
	}else if($id == "5")
	{
	?>
	<h4>Chính sách ship từ 8/1/2023 như sau</h4>
                        <p>
						

- Khách hàng thuộc Quận Hoàng Mai, Hai Bà Trưng, Đống Đa, Bắc Từ Liêm, Nam Từ Liêm, Cầu Giấy, Ba Đình, Tây Hồ miễn ship tất cả các đơn hàng không phân biệt giá trị.
- Các Quận: Hoàn Kiếm, Thanh Xuân và huyện Thanh Trì miễn ship với đơn hàng có giá trị trên 130.000 vnđ.
- Các Quận Long Biên, Hà Đông miễn ship với đơn hàng có giá trị trên 150.000 vnđ.
- Các đơn hàng không thuộc phạm vi áp dụng sẽ tính phí 30.000 vnđ/đơn hàng.


						</p>
	<?php
	}else if($id == "6")
	{
	?>
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
	}
	?>
	</body>
	</html>
	<?php
}
?>
