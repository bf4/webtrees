
function navObj(target, oXmlHttp, store, link, callback) {
	this.processFunc = function()
	{
 			if (oXmlHttp.readyState==4)
 			{
  				evalAjaxJavascript(oXmlHttp.responseText, target);
  				if (store==1) nav1Content[link] = oXmlHttp.responseText;
  				if (store==2) nav2Content[link] = oXmlHttp.responseText;
  				if (callback) callback();
  			}
 		};
}

var nav1Content = new Array();
var nav2Content = new Array();

function loadNav1(link) {
	content = '';
	target = document.getElementById('midcontent');
	document.getElementById('midcontent2').style.width='0px';
	if (!target) return;
	document.cookie = 'nav1link='+link;
	document.cookie = 'nav2link=';
	document.cookie = 'navSurnames=';
	document.cookie = 'navSurname=';
	if (nav1Content[link]) target.innerHTML = nav1Content[link];
	else {
		var oXmlHttp = createXMLHttp();
		oXmlHttp.open("get", link, true);
		target.innerHTML = loadingMessage;
		temp = new navObj(target, oXmlHttp, 1, link);
		oXmlHttp.onreadystatechange=temp.processFunc;
 		oXmlHttp.send(null);
	}
}

function loadNav2(link) {
	content = '';
	target = document.getElementById('midcontent2');
	if (!target) return;
	target.style.width = '155px';
	document.cookie = 'nav2link='+link;
	if (nav2Content[link]) target.innerHTML = nav2Content[link];
	else {
		var oXmlHttp = createXMLHttp();
		oXmlHttp.open("get", link, true);
		temp = new navObj(target, oXmlHttp, 2, link);
		target.innerHTML = loadingMessage;
		oXmlHttp.onreadystatechange=temp.processFunc;
	 	oXmlHttp.send(null);
 	}
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

var firstClick = false;
function navFirstClick() {
	firstClick = true;
	topnav.zoom = -7;
	topnav.newRoot('', topnav.innerPort, GEDCOM);
	//highlightMenu(document.getElementById('navlist'), this);
	var link1 = readCookie('nav1link'); 
	var link2 = readCookie('nav2link');
	var surnames = readCookie('navSurnames'); 
	var surname = readCookie('navSurname'); 
	if (link1==null || link1=='') link1 = 'themes/navigator/thememenu.php?navAjax=1&surnameContent=1';
	if (surnames!=null && surnames!='') link1 += '&loadSurnames='+surnames;
	loadNav1(link1); 
	if (link2!=null && link2!='') loadNav2(link2); 
	if (surname!=null && surname!='') loadSurnamePeople(surname); 
}

function highlightMenu(content, selected) {
	lis = content.childNodes;
	for(i=0; i<lis.length; i++) {
		if (lis[i].tagName=='LI') {
			lis[i].style.fontWeight = 'normal';
		}
	}
	selected.parentNode.style.fontWeight = 'bold';
}

function loadSurnames(letter) {
	target = document.getElementById('surnameList');
	if (!target) return;
	document.cookie = 'navSurnames='+letter;
	link = 'themes/navigator/thememenu.php?navAjax=1&loadSurnames='+letter;
	if (nav2Content[link]) target.innerHTML = nav2Content[link];
	else {
		var oXmlHttp = createXMLHttp();
		oXmlHttp.open("get", link, true);
		target.innerHTML = loadingMessage;
		temp = new navObj(target, oXmlHttp, 1, link);
		oXmlHttp.onreadystatechange=temp.processFunc;
		oXmlHttp.send(null);
	}
}

function loadSurnamePeople(surname) {
	var link = 'themes/navigator/thememenu.php?navAjax=1&loadSurnamePeople='+surname;
	target = document.getElementById('midcontent2');
	if (!target) return;
	target.style.width = '250px';
	document.cookie = 'navSurname='+surname;
	if (nav2Content[link]) target.innerHTML = nav2Content[link];
	else {
		var oXmlHttp = createXMLHttp();
		oXmlHttp.open("get", link, true);
		target.innerHTML = loadingMessage;
		temp = new navObj(target, oXmlHttp, 1, link);
		oXmlHttp.onreadystatechange=temp.processFunc;
		oXmlHttp.send(null);
	}
}

function filterSurnames() {
	value = document.getElementById("nameSearch").value;
	if (value.length>1) {
		
	}
}