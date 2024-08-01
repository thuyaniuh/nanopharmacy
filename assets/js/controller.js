/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var ROOT_URL = '';
var loading = false;
var img_loading = '';

var last_id = "";
var last_url = "";
var last_folder_id ="";
var last_folder_name = "";
var last_icon = "";
var cached = 0;
var LOAD_TYPE = "webapp";
var StyleApp = "PANEL";
var NAV_ENABLE = true;

function GetXmlHttpObject() {
	var objXMLHttp = null;
	if (window.XMLHttpRequest) {
		objXMLHttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
            objXMLHttp = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
	}
	return objXMLHttp;
}
function showpage(_id, _url){
    if(document.getElementById(_id)== null){
        return;
    }
  
    if(loading && last_url == _url){
        return;
    }
    var xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
            alert("Browser does not support HTTP Request");
            return;
    }
    last_url = _url;
    last_id = _id;
	
    if(_url.indexOf("?")==-1)
    {
            _url = _url + "?cache=" + cached;
    }else
    {
            _url = _url + "&cache=" + cached;
    }
    _url = _url + "&cview=" + _id;
	
	var index = _url.indexOf("?");
    var params = _url.substring(index + 1);
    _url = _url.substring(0, index);
    cached = cached + 1;
    
	var loading_content = document.getElementById('T_HISTORY');
  
	xmlHttp.open("POST", _url, true);
	
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
	//xmlHttp.setRequestHeader("Content-length", params.length);
	
	xmlHttp.onreadystatechange = function() {
		if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) {
			loading = false;
			if(loading_content != null)
			{
				loading_content.innerHTML = "";
			}
			if (xmlHttp.status == 200) {
				
				  var str = xmlHttp.responseText;
				
				  var tmp_id = "#" + _id;
				  $(tmp_id).empty();
				  $(tmp_id).append(str);
			} else {
				document.getElementById(_id).innerHTML="";
			}
		}
	}
	xmlHttp.send(params);
	if(loading_content != null)
	{

		loading_content.innerHTML = img_loading;
	}
	loading = true;
}
function reLoadPage()
{
	showpage(last_id, last_url);
}
function loadPage(_id, _url, complete, response){

    if(response == false)
	{
		if(document.getElementById(_id)== null){
			return;
		}
	}
    var xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
            alert("Browser does not support HTTP Request");
            return;
    }
    
    if(_url.indexOf("?")==-1)
    {
            _url = _url + "?cache=" + cached;
    }else
    {
            _url = _url + "&cache=" + cached;
    }
	_url = _url + "&cview=" + _id;
    var index = _url.indexOf("?");
    var params ="";
	if(index != -1)
	{
		params = _url.substring(index + 1);
		_url = _url.substring(0, index);
	}
	cached = cached + 1;
	var loading_content = document.getElementById('T_HISTORY');
	
    xmlHttp.open("POST", _url, true);
	
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
	
 
	
	xmlHttp.onreadystatechange = function() 
	{
		if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) 
		{
			loading = false;
			if(loading_content != null)
			{
				loading_content.innerHTML = "";
			}
			var responseMsg = '';
			if (xmlHttp.status == 200) 
			{
				if(response == true)
				{
					responseMsg = xmlHttp.responseText;
				}else
				{
					var str = xmlHttp.responseText;
					var tmp_id = "#" + _id;
					$(tmp_id).empty();
					$(tmp_id).append(str);
				}
				complete(0, responseMsg);	
			} 
			else 
			{
				if(response == true)
				{
					responseMsg = xmlHttp.responseText;
				}else
				{
					document.getElementById(_id).innerHTML= xmlHttp.responseText;
				}
				complete(-1, responseMsg);
				
			}
		}
	}
	
	xmlHttp.send(params);
	loading = true;
	
	if(_id != "header_notification_bar" && loading_content != null)
	{
		loading_content.innerHTML = img_loading;
	}
}

function replaceAll(str, f, r)
{
	while(str.indexOf(f) !=-1)
	{
		str = str.replace(f, r);
	}
	return str;
}
function submitForm(theform,_id, _url, params, complete) {
	if(loading){
        return;
    }
	var xmlHttp = GetXmlHttpObject();
	if (xmlHttp == null) {
		alert("Browser does not support HTTP Request");
		return;
	}
	
    for (var i=0; i<theform.elements.length; i++){
        if (theform.elements[i].type=="text" || theform.elements[i].type=="textarea" || theform.elements[i].type=="hidden" || theform.elements[i].type=="color" || theform.elements[i].type=="select-one" || theform.elements[i].type=="checkbox" || theform.elements[i].type=="password"){
            if(params != ''){
                params = params + "&";
            }
            
            if(theform.elements[i].type=="checkbox")
            {
				var v = "0";
				 if(theform.elements[i].checked)
				 {
					v = "1";
				 }
				var id = theform.elements[i].id;
				if(id != null && id != "")
				{
					v = encodeURIComponent(v);
				}
				params = params + id + "=" + v;
            }else if(theform.elements[i].type=="color")
			{
				
				v= theform.elements[i].value;
				v = "color{" + v + "}";
				
				var id = theform.elements[i].id;
				if(id != null && id != "")
				{
					v = encodeURIComponent(v);
					
				}
				 params = params + id + "=" + v;
            }
			else
            {
				v= theform.elements[i].value;
				if(theform.elements[i].getAttribute('key') != null)
				{
					v = theform.elements[i].getAttribute('key');
					
				}
				var id = theform.elements[i].id;
				if(id != null && id != "")
				{
					v = encodeURIComponent(v);
					
				}
				params = params + id + "=" + v;
            }
        }
    }
	
	params = params + "&cview=" + _id;
	
	
    var loading_content = document.getElementById('T_HISTORY');
	xmlHttp.open("POST", _url, true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
	xmlHttp.onreadystatechange = function() {
		if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) {
			loading = false;
            if (xmlHttp.status == 200) {
				var str = xmlHttp.responseText;
				var tmp_id = "#" + _id;
				$(tmp_id).empty();
				$(tmp_id).append(str);
	
				loading = false;
				if(loading_content != null){
					loading_content.innerHTML = "";
				}  
				if(complete != undefined)
				{
					complete();
				}
            }else
            {
                if(loading_content != null){
                    loading_content.innerHTML = "";
                }
             }
		}
	}
	xmlHttp.send(params);
	loading = true;
	if(loading_content != null)
	{
		loading_content.innerHTML = img_loading;
	}
}

function pageSizeAllNav(cview, _url, num)
{
	if(num==0)
	{
		num = 1;
	}
	_url = _url + "&ps=" + num;
	
	showpage(cview, _url);
}

function doHandleCheckAll(theCheckBox)
{
	var checkboxes = document.getElementsByName(theCheckBox.name);
	for (var i= 0; i<checkboxes.length; i++) {
			checkboxes[i].checked=theCheckBox.checked;
	}
}
function selectedItemChange(_n, _i, _v)
{

    if(document.getElementById(_n).value ==''){
        document.getElementById(_i).value = (_v);
    }
}
function listChanged(theField, _i, _v)
{
	
    if(theField.value ==''){
        theField.form[_i].value = unescape(_v);
    }
}
function selectedItemChangeOnPage(_n, _i, _v)
{
	if(document.getElementById(_n).value =="")
	{
		document.getElementById(_i).value = unescape(_v);
	}
}



var nav_array = new Array();
function addNav(id, name, icon, _url, _id)
{
	var i = findNav(id);
	if(id =="0")
	{
		icon = "home.gif";
	}
	if(i == -1)
	{
		var ni = new NavItem(id, name, icon, _url, _id);
		nav_array.push(ni);
	}else
	{
		if(i !=0)
		{
			nav_array = nav_array.slice(0, i);

		}else
		{
			nav_array = new Array();
		}

		var ni = new NavItem(id, name, icon, _url, _id);
		nav_array.push(ni);

	}
}
function NavItem(id, name, icon, _url, _id)
{
	this.id = id;
    this.name = name;
	this.icon = icon;
	this._url = _url;
        this._id = _id;
}
function findNav(id)
{
	var i=0;
	for(i=0; i<nav_array.length; i++)
	{
		var ni = nav_array[i];
		if(ni.id == id)
		{
			return i;
		}
	}
	return -1;
}
function printNav()
{
    if(document.getElementById('T_HISTORY')== null || document.getElementById('T_HISTORY')== 'undefined'){
        return;
    }
	var i=0;
	var str = "";
	var start = 0;
	if(nav_array.length>6)
	{
		start = nav_array.length - 6
	}
	for(i=start; i<nav_array.length; i++)
	{
		var ni = nav_array[i];
		if(str != "")
		{
			str = str + "&nbsp;&nbsp;<img src='" + ROOT_URL + "assets/_images/arrow.gif' width='14' height='14'/>";
		}
		str = str + "&nbsp;&nbsp;<img src='" + ROOT_URL + "assets/_images/" + ni.icon + "' width='16' height='16' border='0'/>&nbsp;&nbsp;<a href=\"javascript:showpage('" + ni._id +"', '" + ni._url +"', '" + ni.id + "', '" + escape(ni.name) + "', '" + ni.icon + "');\">" + unescape(ni.name) + "</a>";
	}

	document.getElementById('T_HISTORY').innerHTML=str;
	//document.getElementById('B_HISTORY').innerHTML=str;
}

var currentForm;
function openform(editparams){
	currentForm = editparams.form;
	var x = screen.width/2 - (screen.width-50)/2;
    var y = 0;
    window.open('selected?p=' +  editparams.getAttribute("param"), '', 'addressbar=0,directories=no,titlebar=0,menubar=0,status=0,toolbar=0,resizable=1,scrollbars =1,height=' + (screen.height -100) + ',width='+ (screen.width-50) +',left='+ x +',top='+y);
}
var currentForm;
function openformLink(theFrom, params){
	currentForm = theFrom;
	var x = screen.width/2 - (screen.width-50)/2;
    var y = 0;
   
    window.open('selected?p=' + params, '', 'directories=no,titlebar=no,menubar=no,status=0,toolbar=0,resizable=1,scrollbars =1,height=' + (screen.height -100) + ',width='+ (screen.width-50) +',left='+ x +',top='+y);
}
function selecteditem(colid, id, coln, v)
{
	
	var ctr = window.opener.currentForm[colid];
	if(ctr != null)
	{
		ctr.value = id;
	}
	ctr = window.opener.currentForm[coln];
	if(ctr != null)
	{
		ctr.value = unescape(v);
	}
	
	window.close();
}


var FORMAT_DATE = "yyyy-mm-dd";
var MOMENT_FORMAT_DATE = "yyyy-MM-dd";


var cached_complete = 1;


function openWin(_url, att)
{
	if(att == null)
	{
		var x = screen.width/2 - (screen.width-50)/2;
    	var y = 0;
		att = 'status=0,toolbar=0,resizable=1,scrollbars =1,height=' + (screen.height -100) + ',width='+ (screen.width-50) +',left='+ x +',top='+y;
	}
	window.open(_url , '', att);
}
function barCodeView(code, label, att)
{
	if(att == null)
	{
		var x = screen.width/2 - (screen.width-50)/2;
    	var y = 0;
		att = 'status=0,toolbar=0,resizable=1,scrollbars =1,height=' + (screen.height -100) + ',width='+ (screen.width-50) +',left='+ x +',top='+y;
	}
	var _url = 'barcode?code=' + encodeURIComponent(code) + '&label=' + encodeURIComponent(label);
	window.open(_url , '', att);
}
function reportClick(cmd, folder_name, properties, column_id)
{
	
	if(cmd =='REPORT_CUOSTOMIZE')
	{
		
		window.open(ROOT_URL + 'index/reportdesign?folder_name=' + folder_name);
		return;
	}else
	{
		
		var _url = ROOT_URL + 'index/reportview?folder_name=' + folder_name + '&id=' + cmd + '&column_id=' + column_id + '&p=' + properties;
		window.open(_url, '', '');
		return;
	}
}
function reportParamClick(cmd, folder_name, properties, column_id)
{
	
	var _url = ROOT_URL + 'index/reportviewparam?folder_name=' + folder_name + '&report_id=' + cmd + '&column_id=' + column_id + '&p=' + properties;

	window.open(_url, '', '');
}


function removeParentElement(theElement)
{
	var el = theElement.parentNode;
	var elp = el.parentNode;
	elp.removeChild( el );
}