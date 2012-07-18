
function gotoWin(vURL){
        helpWindow = window.open(vURL,'','height=500 width=700 toolbar=no scrollbars=yes');
}// showHelp

function showHelp(vURL){
        helpWindow = window.open(vURL,'','height=500 width=448 toolbar=no scrollbars=yes');
}// showHelp

function showImage(vURL){
        helpWindow = window.open(vURL,'','height=200 width=190 toolbar=no scrollbars=no');
}// Image Viewer

function showField(jsProdName,jsProdPrice,currency) {
  setDivHtml(jsProdName,symbols[currency]+formatValue(jsProdPrice * convRates[currency],"###,###,###,###.##")+" "+currencies[currency]);
} 

function Seconds(Count,Units) {
	if (Units=="Seconds") { return Count; }
	if (Units=="Minutes") { return Count * 60; }
	if (Units=="Hours") { return Count * 3600; }
	if (Units=="Days") { return Count * 86400; }
	if (Units=="Months") { return Count * 86400 * 30; }
	if (Units=="Years") { return Count * 86400 * 365; }
}

function getDivHtml(fieldToGet) {
 if (document.all||document.layers||document.getElementById){
  if (document.all) {
   vItem = document.all[fieldToGet].innerHTML;
  } else if (document.layers){
   vItem = document.fieldToGet.innerHTML;
  } else if (document.getElementById){
   vItem = document.getElementById(fieldToGet).innerHTML;
  }
  return vItem;
 }
}
function setDivHtml(fieldToSet,vItem) { 
 if (document.all||document.layers||document.getElementById){
  if (document.all) {
   document.all[fieldToSet].innerHTML = vItem;
  } else if (document.layers){
   document.fieldToSet.innerHTML = vItem;
  } else if (document.getElementById){
   document.getElementById(fieldToSet).innerHTML = vItem;
  }
 }
}
function addDivHtml(fieldToSet,vItem) {
 if (document.all||document.layers||document.getElementById){
  if (document.all) {
   document.all[fieldToSet].innerHTML += vItem;
  } else if (document.layers){
   document.fieldToSet.innerHTML += vItem;
  } else if (document.getElementById){
   document.getElementById(fieldToSet).innerHTML += vItem;
  }
 }
}
function setFieldValue(fieldToSet,vItem) {
 if (document.all||document.layers||document.getElementById){
  if (document.all) {
   document.all[fieldToSet].value = vItem;
  } else if (document.layers){
   document.fieldToSet.value = vItem;
  } else if (document.getElementById){
   document.getElementById(fieldToSet).value = vItem;
  }
 }
}

function getFieldValue(fieldToGet) {
 if (document.all) {
  vField = document.all[fieldToGet].value;
 } else if (document.getElementById) {
  vField = document.getElementById(fieldToGet).value;
 } else vField=0;
 return vField;
}

function isChecked(fieldToGet) {
 if (document.all) {
  vField = document.all[fieldToGet].checked;
 } else if (document.getElementById) {
  vField = document.getElementById(fieldToGet).checked;
 } else vField=0;
 return vField;
}

function showTotalPrice(){
 Price = GetPrice() * getFieldValue("qty") * (100 - GetDiscount()) / 100;
 //showField("totalPrice",Price,selectedCurrency);
 //showField("ListPrice",GetPrice(),selectedCurrency);
 setDivHtml("totalPrice","$"+formatValue(Price,"###,###,###,###.##"));
 //setDivHtml("discount",String(GetDiscount())+"%");
}


function formatDecimal(argvalue, addzero, decimaln) {
  var numOfDecimal = (decimaln == null) ? 2 : decimaln;
  var number = 1;
  number = Math.pow(10, numOfDecimal);
  argvalue = Math.round(parseFloat(argvalue) * number) / number;
  argvalue = "" + argvalue;
  if (argvalue.indexOf(".") == 0)
    argvalue = "0" + argvalue;
  if (addzero == true) {
    if (argvalue.indexOf(".") == -1)
      argvalue = argvalue + ".";
    while ((argvalue.indexOf(".") + 1) > (argvalue.length - numOfDecimal))
      argvalue = argvalue + "0";
  }
  return argvalue;
}

function formatValue(argvalue, format) {
// use this format: formatValue(1223.434, "$##,###.##")  will return "$1,223.43"
  var numOfDecimal = 0;
  if (format.indexOf(".") != -1) {
    numOfDecimal = format.substring(format.indexOf(".") + 1, format.length).length;
  }
  argvalue = formatDecimal(argvalue, true, numOfDecimal);
  argvalueBeforeDot = argvalue.substring(0, argvalue.indexOf("."));
  retValue = argvalue.substring(argvalue.indexOf("."), argvalue.length);
  strBeforeDot = format.substring(0, format.indexOf("."));
  for (var n = strBeforeDot.length - 1; n >= 0; n--) {
    oneformatchar = strBeforeDot.substring(n, n + 1);
    if (oneformatchar == "#") {
      if (argvalueBeforeDot.length > 0) {
        argvalueonechar = argvalueBeforeDot.substring(argvalueBeforeDot.length - 1, argvalueBeforeDot.length);
        retValue = argvalueonechar + retValue;
        argvalueBeforeDot = argvalueBeforeDot.substring(0, argvalueBeforeDot.length - 1);
      }
    }
    else {
      if (argvalueBeforeDot.length > 0 || n == 0)
        retValue = oneformatchar + retValue;
    }
  }
  return retValue;
}

function setpos(obj,target) {
        var curleft = 50;
        var curtop = -50;
        if (obj.offsetParent) {
                do {
                        curleft += obj.offsetLeft;
                        curtop += obj.offsetTop;
                } while (obj = obj.offsetParent);
                target.style.top = curtop + "px";
                target.style.left = curleft + "px";
        }
}
function hide(id) {
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
        el.style.display='none';
}
function show(id) {
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
        el.style.display='block';
        el.style.visibility='visible';
}
function hidepopup() {
	hide('popup');
}

function ajax(url,el)
{
var xmlHttp;

try
  // Firefox, Opera 8.0+, Safari
  { xmlHttp=new XMLHttpRequest(); }
catch (e)
  {
  // Internet Explorer
  try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
  catch (e)
    {
    try { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
    catch (e)
      { alert("Your browser does not support AJAX!"); return false; }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
	if (document.all) {
		document.all['popup'].innerHTML=xmlHttp.responseText;
        	document.all['popup'].style.display='block';
        	document.all['popup'].style.visibility='visible';
	} else {
        	popup = document.getElementById('popup');
		popup.innerHTML=xmlHttp.responseText;
        	popup.style.display='block';
        	popup.style.visibility='visible';
	}
        setpos(el,popup);
      }
    }
  xmlHttp.open("GET",url+el.value,true);
  xmlHttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
  xmlHttp.send(null);
}

function ajaxSaveCell(element,index,table,key)
{
var xmlHttp;

try
  // Firefox, Opera 8.0+, Safari
  { xmlHttp=new XMLHttpRequest(); }
catch (e)
  {
  // Internet Explorer
  try { xmlHttp=new ActiveXObject('Msxml2.XMLHTTP'); }
  catch (e)
    {
    try { xmlHttp=new ActiveXObject('Microsoft.XMLHTTP'); }
    catch (e)
      { alert('Your browser does not support AJAX!'); return false; }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
                element.value=xmlHttp.responseText;
		show_debug("ajax answer: "+xmlHttp.responseText);
      }
    }
  url = 'ipe.php?table='+table+'&col='+element.name+'&val='+escape(element.value)+'&key='+key+'&index='+escape(index);
  show_debug("ajax: "+url);
  xmlHttp.open('GET',url,true);
  xmlHttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
  xmlHttp.send(null);
}

function enableSave(elem) {
  f = elem.form;
  for (e=0;e<f.elements.length;e++) {
    el = f.elements[e];
    if (el.name == 'submit') {
      el.className='ipeb';   /* in place editing - block class */
    }
  }
}

function ajaxreport(url,target)
{
var xmlHttp;

t = document.getElementById(target+"report");
t.innerHTML = "<div class='loading'>loading...</div>";
try
  // Firefox, Opera 8.0+, Safari
  { xmlHttp=new XMLHttpRequest(); }
catch (e)
  {
  // Internet Explorer
  try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
  catch (e)
    {
    try { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
    catch (e)
      { alert("Your browser does not support AJAX!"); return false; }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
	t.innerHTML=xmlHttp.responseText;
      }
    }
  xmlHttp.open("GET",url+"&report="+target,true);
  xmlHttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
  xmlHttp.send(null);
}

function hidereport(id,user,rep) {
	document.getElementById(rep+'report').innerHTML = "<a href=javascript:ajaxreport('userreports.php?id="+id+"&username="+user+"','"+rep+"');>show</a>";
}

function ajax_update_element(e,url)
{
var xmlHttp;
try
  // Firefox, Opera 8.0+, Safari
  { xmlHttp=new XMLHttpRequest(); }
catch (e)
  {
  // Internet Explorer
  try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
  catch (e)
    {
    try { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
    catch (e)
      { alert("Your browser does not support AJAX!"); return false; }
    }
  }
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4)
      {
        e.value=xmlHttp.responseText;
      }
    }
  xmlHttp.open("GET",url+e.value,true);
  xmlHttp.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
  xmlHttp.send(null);
}

function dollarformat(thisone){
  if (thisone.value.charAt(0)=='$') { s=1; prefix=''; } else { s=0; prefix='$'; }
  wd='w'; count=0; tempnum=thisone.value;
  for (i=s;i<tempnum.length;i++) if (wd=='d') count++; else if (tempnum.charAt(i)=='.') wd='d';
  if (wd=='w') extra = '.00'; else if (count==0) extra = '00'; else if (count==1) extra = '0'; else {
        extra = '';
        tempnum = tempnum.substr(0,i-count+2);
  }
  thisone.value=prefix+tempnum+extra;
  if (prefix=='$') return(Number(tempnum+extra));
  else return(Number(tempnum.substring(1)));
}

function dollarvalue(fieldToGet){
  if (document.all) {
   vField = document.all[fieldToGet].value;
  } else if (document.getElementById) {
   vField = document.getElementById(fieldToGet).value;
  } else vField=0;
  if (vField.charAt(0)=='$') return Number(vField.substring(1,vField.length));
  else return Number(vField);
}

var currentField;
var enum_set_popup;
function enum_set_popup_hide() {
        document.getElementById("enum_set_popup").style.display='none';
}
function enum_set_move(obj,target) {
        var curleft = 50;
        var curtop = -50;
        if (obj.offsetParent) {
                do {
                        curleft += obj.offsetLeft;
                        curtop += obj.offsetTop;
                } while (obj = obj.offsetParent);
                target.style.top = curtop + "px";
                target.style.left = curleft + "px";
        }
}
function enum_set_chooser(elem,set) {
        var e,f,i,p;
	if (document.all) {
		enum_set_popup = document.all['enum_set_popup'];
	} else {
		enum_set_popup = document.getElementById("enum_set_popup");
	}
	enum_set_inner_HTML = '<a onclick="this.offsetParent.style.display=\'none\';" id="close">x</a><ul>';
	for ( o in enum_set[set] ) {
		v = enum_set[set][o];
		enum_set_inner_HTML += " <li><input type=checkbox name='enum_sets[]' value='"+v+"' onclick=enum_set_update(); >"+v+"</li>\n";
	}
	enum_set_inner_HTML += '</ul>';
	enum_set_popup.innerHTML = enum_set_inner_HTML;
	enum_set_popup.style.display='block';
	enum_set_popup.style.visibility='visible';
        enum_set_values = elem.value.split(',');
        f = document.forms["enum_set_chooser_form"];
        for (p in enum_set_values) {
                for (i=0; i<f.elements.length; i++) {
                        e = f.elements[i];
                        if ((e.name=='enum_sets[]') && (e.value==enum_set_values[p])) {
                                e.checked='checked';
                        } 
                }
        }
        enum_set_move(elem,enum_set_popup);
        currentField = elem;
	show_debug("chooser: "+elem.value);
}
function enum_set_update() {
        var str = "";
        f = document.forms["enum_set_chooser_form"];
        for (i=0; i<f.elements.length; i++) {
                e = f.elements[i];
                if ((e.name=='enum_sets[]') && (e.checked)) {
                        if (str.length>0) {str += ",";}
                        str += e.value;
                }
        }
        currentField.value = str;
	currentField.onblur();
}
function show_debug(str) {
	if (true) return;
	if (document.all) {
		popup = document.all["popup"];
	} else {
		popup = document.getElementById("popup");
	}
	str += "<br>";
	popup.style.display='block';
	popup.style.visibility='visible';
	popup.innerHTML += str;
	popup.style.position = 'fixed';
	popup.style.padding = '10px';
	popup.style.top = '100px';
	popup.style.left = '800px';
}

function nextPage() {
	id = 'ips_starting_with';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
	el.value = String(Number(el.value)+Number(getFieldValue('ips_row_count')));
	el.form.submit.click();
}

function prevPage() {
	id = 'ips_starting_with';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
	v = Number(el.value)-Number(getFieldValue('ips_row_count'));
	if (v<0) v = 0;
	el.value = v;
	el.form.submit.click();
}

function Jump10Pages() {
	id = 'ips_starting_with';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
	el.value = String(Number(el.value)+(10*Number(getFieldValue('ips_row_count'))));
	el.form.submit.click();
}

function Back10Pages() {
	id = 'ips_starting_with';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
	v = Number(el.value)-(10*Number(getFieldValue('ips_row_count')));
	if (v<0) v = 0;
	el.value = v;
	el.form.submit.click();
}

function showSortedBy(colname) {
	id = 'ips_sort_order';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
	el.value = colname
	el.form.submit.click();
}
function custom_query(q) {
        id = 'ips_custom_query';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
        el.value = q
}
function export_results(q) {
        id = 'ips_export_results';
        if (document.all) el=document.all[id];
        else el = document.getElementById(id);
        el.value = q
}

function checkall(t) {
  for (i=0; i<document.forms[t].elements.length; i++) {
	e = document.forms[t].elements[i];
        if (e.type=='checkbox') e.checked='checked';
  }
}
function uncheckall(t) {
  for (i=0; i<document.forms[t].elements.length; i++) {
	e = document.forms[t].elements[i];
        if (e.type=='checkbox') e.checked=false;
  }
}
function invert(t) {
  for (i=0; i<document.forms[t].elements.length; i++) {
	e = document.forms[t].elements[i];
        if (e.type=='checkbox') {
                if (e.checked) e.checked=false;
                else e.checked='checked';
        }
  }
}
function confirmsubmit(e,t,n) {
  if (e.selectedIndex==0) return false;
  count = 0;
  action = e.options[e.selectedIndex].value;
  for (i=0; i<document.forms[t].elements.length; i++) {
	el = document.forms[t].elements[i];
        if (el.type=='checkbox') {
                if (el.checked) count++;
        }
  }
  if (count<1) {
        e.selectedIndex=0;
        return false;
  }
  if (action=='Delete') {
        if (!confirm("OK to Delete "+count+" "+n)) {
                e.selectedIndex=0;
                return false;
        }
  }
  e.form.submit();
}

function setloc(tab,col,loc) {
        document.forms[tab].elements[col].value = "="+loc.options[loc.selectedIndex].value;
}




