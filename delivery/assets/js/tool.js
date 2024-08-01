var Base64 = {
 
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
}
function encode64(input) {
	return Base64.encode(input);
}
function decode64(input) {
	return Base64.decode(input);
}
function filterHttp(str)
{
	str = str.replace(/=/g, "{");
	str = replaceAll(str, "+", "}");
	return str;
}
function validate_email(str) {

	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){

		return false
	}

	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){

		return false
	}

	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){

		return false
	}

	if (str.indexOf(at,(lat+1))!=-1){

		return false
	}

	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){

		return false
	}

	if (str.indexOf(dot,(lat+2))==-1){

		return false
	}

	if (str.indexOf(" ")!=-1){

		return false
	}

 	return true
}
function decimalCurr(txt) {
	regExp =  /^-{0,1}\d*\.{0,1}\d+$/;
	return regExp.test(txt);
}
function numeric(num) {
	return (typeof num == 'string' || typeof num == 'number') && !isNaN(num - 0) && num !== '';

}

function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}
function checkHourMinute(time)
{
	
	time = trim(time);
	var arrayData;
	var h = "";
	var m = "";
	while(true)
	{
		if(time.indexOf(":")!=-1)
		{
			arrayData = time.split(":");
			if(arrayData.length == 1)
			{
				h = trim(arrayData[0].toString());
				m = "00";
			}
			if(arrayData.length == 2)
			{
				h = trim(arrayData[0].toString());
				m = trim(arrayData[1].toString());
			}
			if(h.length == 1)
				 h= "0" + h;
			if(m.length == 0)
				m = m + "0";
			time=h + ":" + m;
		}else
		{
			if(time.length == 0){
				time = "0000";
			}else if(time.length == 1)
			{
				time = "0" + time + "00";
			}else if(time.length == 2)
			{
				if(new Number(time)>23)
				{
					time = "0" + time + "0";
				}else time = time + "00";
			
			}else if(time.length == 3)
			{
				if(parseInt(time.substring(0, 2))>23)
				{
					time = "0" + time;
				}else time = time + "0";
			}else if(time.length>4)
			{
				time = time.substring(0, 4);
			}
			time=time.substring(0, 2) + ":" + time.substring(2); 
			continue;
			
		}
		break;
	}
	arrayData = time.split(":");
	if(arrayData.length == 2)
	{
		var hour =parseInt(arrayData[0]);
		var minute = parseInt(arrayData[1]);
		if(hour>23)
			arrayData[0]="23";
		if(minute>59)
			arrayData[1]="59";
		return arrayData[0] + ":" + arrayData[1];
	}
	return ":";
}
replaceAll = function(string, omit, place, prevstring) {
	if(omit == "")
	{
		return "";
	}
  if (prevstring && string === prevstring)
    return string;
  prevstring = string.replace(omit, place);
  return replaceAll(prevstring, omit, place, string)
}

function validInteger(theInput, thousands_point, decimal_point)
{
	var sValue = theInput.value;
	sValue = replaceAll(sValue, thousands_point, "_");
	sValue = replaceAll(sValue, decimal_point, ".");
	sValue = replaceAll(sValue, "_", "");
	if(numeric(sValue))
	{
		var i = parseInt(sValue);
		sValue = i.toLocaleString("en-US");
		sValue = replaceAll(sValue, ",", thousands_point);
	}else{
		sValue = "";
	}
	theInput.value = sValue;
}
function validDecimal(theInput, thousands_point, decimal_point)
{
	var sValue = theInput.value.trim();
	if(sValue == "")
	{
		theInput.value = "0";
		return;
	}
	
	sValue = replaceAll(sValue, thousands_point, "_");
	sValue = replaceAll(sValue, decimal_point, ".");
	sValue = replaceAll(sValue, "_", "");
	var sReturn = "";
	if(numeric(sValue))
	{
		var i = parseFloat(sValue);
		sValue = "" + i;
		var sDecimal = "";
		var index = sValue.indexOf(".");
		if(index != -1)
		{
			sDecimal = sValue.substring(index + 1);
			sValue = sValue.substring(0, index);
		}
		while(sValue.length>3)
		{
			 if(sReturn != "")
			{
				sReturn = thousands_point + sReturn;
			}
			sReturn = sValue.substring(sValue.length -3) + sReturn;
			sValue = sValue.substr(0, sValue.length -3);
		}
		if(sValue != ""   )
		{
			if(sReturn != "")
			{
				 sReturn = thousands_point + sReturn;
			}
			sReturn = sValue + sReturn;
		}
		if(sDecimal != "")
		{
			sReturn = sReturn + decimal_point + sDecimal;
		}
	}
	theInput.value = sReturn;
}
function validHMS(theInput)
{
	theInput.value = checkHourMinuteSecond(theInput.value)
}
function checkHourMinuteSecond(time)
{
	time = trim(time);
	var arrayData;
	var h = "";
	var m = "";
	var s = "";
	while(true)
	{
		if(time.indexOf(":")!=-1)
		{
			arrayData = time.split(":");
			
			if(arrayData.length == 1)
			{
				h = trim(arrayData[0].toString());
				m = "00";
				s = "00";
				if(h.length == 1)
					h = "0" + h;
			}
			if(arrayData.length == 2)
			{
				h = trim(arrayData[0].toString());
				m = trim(arrayData[1].toString());
				s = "00";
				if(h.length == 1)
					h = "0" + h;
				if(m.length == 0)
					m = m + "0";
			}
			if(arrayData.length == 3)
			{
				h = trim(arrayData[0].toString());
				m = trim(arrayData[1].toString());
				s = trim(arrayData[2].toString());
				if(h.length == 1)
					h = "0" + h;
				if(m.length == 0)
					m = m + "0";
				if(s.length == 0)
					s = s + "0";
			}
			
			time= h + ":" + m + ":" + s;
		}else
		{
			if(time.length == 0){
				time = "000000";
			}else if(time.length == 1)
			{
				time = "0" + time + "0000";
			}else if(time.length == 2)
			{
				if(new Number(time)>23)
				{
					time = "0" + time + "0";
				}else time = time + "0000";
			
			}else if(time.length == 3)
			{
				if(parseInt(time.substring(0, 2))>23)
				{
					time = "0" + time;
				}else time = time + "000";
			}else if(time.length == 4)
			{
				if(parseInt(time.substring(0, 2))>23)
				{
					time = "0" + time;
				}else if(parseInt(time.substring(2, 4))>59)
				{
					time = time.substring(0, 2) + "0" + time.substring(2) + "0";
				}else time = time + "00";
			}else if(time.length == 5)
			{
				if(parseInt(time.substring(0, 2))>23)
				{
					time = "0" + time;
				}
				if(parseInt(time.substring(2, 4))>59)
				{
					time = time.substring(0, 2) + "0" + time.substring(2);
				}
				else time = time + "00";
			}
			else if(time.length>6)
			{
				time = time.substring(0, 6);
			}
			
			time = time.substring(0, 2) + ":" + time.substring(2, 4) + ":" + time.substring(4, 6); 
			
			continue;
			
		}
		break;
	}
	arrayData = time.split(":");
	if(arrayData.length == 3)
	{
		var hour = parseInt(arrayData[0], 10);
		var minute = parseInt(arrayData[1], 10);
		var second = parseInt(arrayData[2], 10);
		if(hour>23)
			arrayData[0]="23";
		if(hour<10)
		{
		
			arrayData[0]="0" + hour;
		}
		if(minute>59)
			arrayData[1]= "59";
		if(minute<10)
		{
			arrayData[1]= "0" + minute;
		}
		if(second>59)
			arrayData[2]="59";
		if(second<10)
		{
			arrayData[2]= "0" + second;
		}
		return arrayData[0] + ":" + arrayData[1] + ":" + arrayData[2];
	}
	return ":";
}
function calHourVal(fromtime)
{
	
	if(fromtime.length==5 && fromtime.indexOf(":")!=-1){
		var arrayData = fromtime.split(":");
		return (parseFloat(arrayData[0]) * 60) + parseFloat(arrayData[1]);
	}
	return 0;
}
function calHour(fromtime, totime)
{
	if(fromtime.length==5 && totime.length==5 && fromtime.indexOf(":")!=-1 && totime.indexOf(":")!=-1){
		
		var arrayData = fromtime.split(":");
		var fromminute = (parseFloat(arrayData[0]) * 60) + parseFloat(arrayData[1]);
		arrayData = totime.split(":");
		var tominute = (parseFloat(arrayData[0]) * 60) + parseFloat(arrayData[1]);
		if(fromminute>tominute)
			tominute+= (24 * 60);
		
		return (tominute - fromminute)/60;
	}
	return 0;
}

var uid = 0;
function getId()
{
	if(uid>100000)
	{
		uid =0;
	}
	uid += 1;
	return uid;
}

var VietnameseSigns = ["aAeEoOuUiIdDyY", "áàạảãâấầậẩẫăắằặẳẵ", "ÁÀẠẢÃÂẤẦẬẨẪĂẮẰẶẲẴ", "éèẹẻẽêếềệểễ", "ÉÈẸẺẼÊẾỀỆỂỄ", "óòọỏõôốồộổỗơớờợởỡ", "ÓÒỌỎÕÔỐỒỘỔỖƠỚỜỢỞỠ", "úùụủũưứừựửữ", "ÚÙỤỦŨƯỨỪỰỬỮ", "íìịỉĩ", "ÍÌỊỈĨ", "đ", "Đ", "ýỳỵỷỹ", "ÝỲỴỶỸ"];
function validURL(str)
{
	str = trim(str);
	str = replaceAll(str, "\"", "");
	str = replaceAll(str, "'", "");
	str = replaceAll(str, "\\", "");
	str = replaceAll(str, "%", "");
	str = replaceAll(str, "!", "");
	str = replaceAll(str, "&", "");
	str = replaceAll(str, "?", "");
	str = replaceAll(str, "$", "");
	str = replaceAll(str, ",", "");
	str = replaceAll(str,".", "");
	str = replaceAll(str,"/", "");
	str = replaceAll(str, "\"", "");
	str = replaceAll(str, "~", "");
	for (var i = 1; i < VietnameseSigns.length; i++)
	{
		for (var j = 0; j < VietnameseSigns[i].length; j++){
			str = replaceAll(str, VietnameseSigns[i][j], VietnameseSigns[0][i - 1]);
		}
	}
	str = replaceAll(str, " ", "-");
	return str.toLowerCase();
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function validdate(m, d, y)
{
	if (! (1582<= y )  )//comment these 2 lines out if it bothers you
        return false;
      if (! (1<= m && m<=12) )
         return false;
      if (! (1<= d && d<=31) )
         return false;
      if ( (d==31) && (m==2 || m==4 || m==6 || m==9 || m==11) )
         return false;
      if ( (d==30) && (m==2) )
         return false;
      if ( (m==2) && (d==29) && (y%4!=0) )
         return false;
      if ( (m==2) && (d==29) && (y%400==0) )
         return true;
      if ( (m==2) && (d==29) && (y%100==0) )
         return false;
      if ( (m==2) && (d==29) && (y%4==0)  )
         return true;

      return true;
}
function filterTable(input_id, table_id)
{
	var filter, table, tr, td, i;
	filter = document.getElementById(input_id).value.toUpperCase();
	table = document.getElementById(table_id);
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td");
		var has = false;
		for(var j=0; j<td.length; j++)
		{
			if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1)
			{
				has = true;
				break;
			}
		}
		if (has) {
			tr[i].style.display = "";
		
		} else {
			tr[i].style.display = "none";
		}
	}
}
function getRandomColor() {
	var letters = '0123456789ABCDEF'.split('');
	var color = '#';
	for (var i = 0; i < 6; i++ ) {
		color += letters[Math.floor(Math.random() * 16)];
	}
	return color;
}

var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
 
function getMonths(month) {
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
      'August', 'September', 'October', 'November', 'December'];
    return month >= 0 ? months[month] : months;
  }

  function getShortMonth(month) {
    return months[parseInt(month) - 1];
  }

  function getMonthNumber(month) {
    var formatted = month.charAt(0).toUpperCase() + month.substr(1, month.length - 1).toLowerCase();
    return months.indexOf(formatted);
  }

  function getDaysInMonth(year, month) {
    return [31, isLeapYear(year) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
  }

  function getWeekDay(date, month, year) {
    return new Date(year, month, date).getDay();
  }

  // Check if current year is leap year
  function isLeapYear(year) {
    return year % 100 === 0 ? year % 400 === 0 ? true : false : year % 4 === 0;
  }
  

function parseDate(dateFormat, value) {
    var date, dateArray;
	var d = new Date();
    switch(dateFormat) {
      case 'dd-MM-yyyy':
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[1]) - 1, parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'dd-MMM-yyyy':
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[2]), getMonthNumber(dateArray[1]), parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'dd.MM.yyyy':
        dateArray = value.split('.');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[1]) - 1, parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'dd.MMM.yyyy':
        dateArray = value.split('.');
        date = new Date(parseInt(dateArray[2]), getMonthNumber(dateArray[1]), parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'dd/MM/yyyy':
        dateArray = value.split('/');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[1]) - 1, parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'dd/MMM/yyyy':
        dateArray = value.split('/');
        date = new Date(parseInt(dateArray[2]), getMonthNumber(dateArray[1]), parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'MM-dd-yyyy':
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[0]) - 1, parseInt(dateArray[1]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'MM.dd.yyyy':
        dateArray = value.split('.');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[0]) - 1, parseInt(dateArray[1]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'MM/dd/yyyy':
        dateArray = value.split('/');
        date = new Date(parseInt(dateArray[2]), parseInt(dateArray[0]) - 1, parseInt(dateArray[1]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy-MM-dd':
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[0]), parseInt(dateArray[1]) - 1, parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy-MMM-dd':
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[0]), getMonthNumber(dateArray[1]), parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy.MM.dd':
        dateArray = value.split('.');
        date = new Date(parseInt(dateArray[0]), parseInt(dateArray[1]) - 1, parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy.MMM.dd':
        dateArray = value.split('.');
        date = new Date(parseInt(dateArray[0]), getMonthNumber(dateArray[1]), parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy/MM/dd':
        dateArray = value.split('/');
        date = new Date(parseInt(dateArray[0]), parseInt(dateArray[1]) - 1, parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      case 'yyyy/MMM/dd':
        dateArray = value.split('/');
        date = new Date(parseInt(dateArray[0]), getMonthNumber(dateArray[1]), parseInt(dateArray[2]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
      default:
        dateArray = value.split('-');
        date = new Date(parseInt(dateArray[2]), getMonthNumber(dateArray[1]), parseInt(dateArray[0]), d.getHours(), d.getMinutes(), d.getSeconds());
        return date;
    }
  }
  function dateToString(d) 
  {
	  var mm = (d.getMonth() +1);
	  if(mm<10)
	  {
		  mm = '0' + mm;
	  }
	  var dd = d.getDate();
	  if(dd<10)
	  {
		  dd = '0' + dd;
	  }
	  var h = d.getHours();
	  if(h<10)
	  {
		  h = '0' + h;
	  }
	  var m = d.getMinutes();
	  if(m<10)
	  {
		  m = '0' + m;
	  }
	  var s = d.getSeconds();
	  if(s<10)
	  {
		  s = '0' + s;
	  }
	  return d.getFullYear()+ '-' + mm + '-' + dd + ' ' + h +':'+ m +':'+ s;
  }
  function dateToDateString(d) 
  {
	  var mm = (d.getMonth() +1);
	  if(mm<10)
	  {
		  mm = '0' + mm;
	  }
	  var dd = d.getDate();
	  if(dd<10)
	  {
		  dd = '0' + dd;
	  }
	  return d.getFullYear()+ '-' + mm + '-' + dd;
  }
  

