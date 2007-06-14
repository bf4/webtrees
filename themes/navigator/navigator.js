
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

var loadingMessage = "<p style=\"margin: 20px 20px 20px 20px\"><img src=\"images/loading.gif\" alt=\"\" title=\"\" /></p>";
var nav1Content = new Array();
var nav2Content = new Array();

function loadNav1(link) {
	content = '';
	target = document.getElementById('midcontent');
	document.getElementById('midcontent2').style.width='0px';
	if (!target) return;
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

var firstClick = false;
function navFirstClick() {
	firstClick = true;
	topnav.zoom = -5;
	topnav.newRoot('', topnav.innerPort, GEDCOM);
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

function loadSurnames(link) {
	target = document.getElementById('surnameList');
	if (!target) return;
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