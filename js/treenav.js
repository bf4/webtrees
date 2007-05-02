function sizeLines() {
	vlines = document.getElementsByName("vertline");
	for(i=0; i<vlines.length; i++) {
		var id = vlines[i].id.substr(vlines[i].id.indexOf("_")+1);
		var outerParent = document.getElementById("ch_"+id);
		var children = outerParent.childNodes;
		var tables = new Array();
		k=0;
		for(j=0; j<children.length; j++) {
		//alert(children[j].tagName);
			if (children[j].tagName=='TABLE') {
				tables[k] = children[j];
				k++;
			}
		}
		if (tables.length>0) {
			var toptable = tables[0];
			var bottable = tables[tables.length-1];
			
			y1 = findPosY(toptable);
			y1 = y1 + (toptable.offsetHeight/2);
			y2 = findPosY(bottable);
			y2 = y2 + (bottable.offsetHeight/2);
			
			vlines[i].style.top = y1+'px';
			vlines[i].style.height = (y2-y1)+'px';
			
		}
	}
	//-- parent lines
	vlines = document.getElementsByName("pvertline");
	for(i=0; i<vlines.length; i++) {
		var ids = vlines[i].id.split("_");
		
		if (ids.length>1) {
			var toptable = document.getElementById('box_'+ids[1]);
			var bottable = document.getElementById('box_'+ids[2]);
			if (toptable) {
				y1 = findPosY(toptable);
				y1 = y1 + (toptable.offsetHeight/2);
				if (!bottable) {
					y2 = y1 + (toptable.offsetHeight/2);
				}
			}
			if (bottable) {
				y2 = findPosY(bottable);
				y2 = y2 + (bottable.offsetHeight/2);
				if (!toptable) {
					y1 = y2 + (bottable.offsetHeight/2);
				}
			}
			
			vlines[i].style.top = y1+'px';
			vlines[i].style.height = (y2-y1)+'px';
			
		}
	}
	// -- resize innerport
	if (rootTable) {
		innerPort.style.width = rootTable.offsetWidth + 'px';
		innerPort.style.height = rootTable.offsetHeight + 'px';
	}
}

function tempObj(target, oXmlHttp) {
	this.processFunc = function()
	{
 			if (oXmlHttp.readyState==4)
 			{
  				evalAjaxJavascript(oXmlHttp.responseText, target);
  				sizeLines();
  			}
 		};
}

function loadChildren(target, xref) {
	var oXmlHttp = createXMLHttp();
	link = "treenav.php?navAjax=1&rootid="+xref+"&zoom="+zoom;
	oXmlHttp.open("get", link, true);
	temp = new tempObj(target, oXmlHttp);
	oXmlHttp.onreadystatechange=temp.processFunc;
 		oXmlHttp.send(null);
 		target.onclick=null;
 		target.name=null;
}

function loadParent(target, xref, ptype) {
	var oXmlHttp = createXMLHttp();
	link = "treenav.php?navAjax=1&rootid="+xref+"&parent="+ptype+"&zoom="+zoom;
	oXmlHttp.open("get", link, true);
	temp = new tempObj(target, oXmlHttp);
	oXmlHttp.onreadystatechange=temp.processFunc;
 		oXmlHttp.send(null);
 		target.onclick=null;
 		target.name=null;
}

var oldText = new Array();
var oldWidth = new Array();
function expandBox(target, xref) {
	if (oldText[xref]) {
		temp = target.innerHTML;
		target.innerHTML = oldText[xref];
		oldText[xref] = temp;
		
		temp = target.style.width;
		target.style.width=oldWidth[xref];
		oldWidth[xref] = temp;
		sizeLines();
		return;
	}
			
	oldText[xref] = target.innerHTML;
	oldWidth[xref] = target.style.width;
	
	var oXmlHttp = createXMLHttp();
	link = "treenav.php?navAjax=1&rootid="+xref+"&details=1&zoom="+zoom;
	oXmlHttp.open("get", link, true);
	temp = new tempObj(target, oXmlHttp);
	oXmlHttp.onreadystatechange=temp.processFunc;
 		oXmlHttp.send(null);
 		target.style.width='200px';
}

function newRoot(xref) {
	var oXmlHttp = createXMLHttp();
	link = "treenav.php?navAjax=1&rootid="+xref+"&newroot=1&zoom="+zoom;
	oXmlHttp.open("get", link, true);
	temp = new tempObj(innerPort, oXmlHttp);
	oXmlHttp.onreadystatechange=temp.processFunc;
 		oXmlHttp.send(null);
 		return false;
}
	
  function findPosX(obj)
  {
    var curleft = 0;
    if(obj.offsetParent)
        while(1) 
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
  }

  function findPosY(obj)
  {
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
        	if (obj.style.position=="relative") break;
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
  }
	
// Browser check
function Browser() {

  var ua, s, i;

  this.isIE    = false;
  this.isNS    = false;
  this.version = null;

  ua = navigator.userAgent;

  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isIE = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  s = "Netscape6/";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  // Treat any other "Gecko" browser as NS 6.1.

  s = "Gecko";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = 6.1;
    return;
  }
}

var browser = new Browser();

// Global object to hold drag information.

var dragObj = new Object();
dragObj.zIndex = 0;
var curLeft = 0;

// Start dragging the chart
function dragStart(event, id) {
curLeft = 0;
  var el;
  var x, y;

  // If an element id was given, find it. Otherwise use the element being
  // clicked on.
  if (id)
    dragObj.elNode = document.getElementById(id);
  else {
    if (browser.isIE)
      dragObj.elNode = window.event.srcElement;
    if (browser.isNS)
      dragObj.elNode = event.target;
    // If this is a text node, use its parent element.

    if (dragObj.elNode.nodeType == 3)
      dragObj.elNode = dragObj.elNode.parentNode;
  }

  // Get cursor position with respect to the page.

  if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }

  // Save starting positions of cursor and element.

  dragObj.cursorStartX = x;
  dragObj.cursorStartY = y;
  dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
  dragObj.elStartTop   = parseInt(dragObj.elNode.style.top,  10);

  if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
  if (isNaN(dragObj.elStartTop))  dragObj.elStartTop  = 0;

  // Update element's z-index.

  dragObj.elNode.style.zIndex = ++dragObj.zIndex;

  // Capture mousemove and mouseup events on the page.

  if (browser.isIE) {
    document.attachEvent("onmousemove", dragGo);
    document.attachEvent("onmouseup",   dragStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS) {
    document.addEventListener("mousemove", dragGo,   true);
    document.addEventListener("mouseup",   dragStop, true);
    event.preventDefault();
  }
}

// The actual movement of the chart happens here
function dragGo(event) {

  var x, y;

  // Get cursor position with respect to the page.

  if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }

  // Move drag element by the same amount the cursor has moved.
  dragObj.elNode.style.left = (dragObj.elStartLeft + x - dragObj.cursorStartX) + "px";
  dragObj.elNode.style.top  = (dragObj.elStartTop  + y - dragObj.cursorStartY) + "px";
  
  if (browser.isIE) {
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS)
    event.preventDefault();
  
  //-- load children by ajax  
  if (dragObj.elNode.offsetLeft > -10) {
  	children = document.getElementsByName('cload');
  	if (children.length>0) {
	  	for(i=0; i<children.length; i++) {
	  		if (children[i] && children[i].onclick) {
	  			cell = children[i];
	  			x = findPosX(cell);
	  			y = findPosY(cell);
	  			if (x > -10 && y < outerPort.offsetHeight+outerPort.offsetTop) {
	  				if (cell.onclick) {
	  					cell.onclick();
	  				}
	  			}
	  		}
	  	}
  	}
  }
  
  //-- load parents by ajax
  if (rootTable.offsetWidth + dragObj.elNode.offsetLeft < outerPort.offsetWidth) {
  	children = document.getElementsByName('pload');
  	if (children.length>0) {
	  	for(i=0; i<children.length; i++) {
	  		if (children[i] && children[i].onclick) {
	  			cell = children[i];
	  			y = findPosY(cell);
	  			if (y < outerPort.offsetHeight+outerPort.offsetTop) {
	  				if (cell.onclick) {
	  					cell.onclick();
	  				}
	  			}
	  		}
	  	}
  	}
  }
}

// Stop dragging the chart
function dragStop(event) {

  // Stop capturing mousemove and mouseup events.
	
  if (browser.isIE) {
    document.detachEvent("onmousemove", dragGo);
    document.detachEvent("onmouseup",   dragStop);
  }
  if (browser.isNS) {
    document.removeEventListener("mousemove", dragGo,   true);
    document.removeEventListener("mouseup",   dragStop, true);
  }

  sizeLines();
}

var zoom = 0;
function zoomIn() {
	var boxes = innerPort.getElementsByTagName("div");
	for(i=0; i<boxes.length; i++) {
		var child = boxes[i];
		child.style.width = (parseInt(child.style.width) + 18)+'px';
		child.style.fontSize = (parseInt(child.style.fontSize)+1)+'px';
		zoomImgs(child);
	}
	zoom++;
	sizeLines();
}

function zoomOut() {
	var boxes = innerPort.getElementsByTagName("div");
	for(i=0; i<boxes.length; i++) {
		var child = boxes[i];
		child.style.width = (parseInt(child.style.width) - 18)+'px';
		child.style.fontSize = (parseInt(child.style.fontSize)-1)+'px'; 
		zoomImgs(child);
	}
	zoom--;
	sizeLines();
}

function zoomImgs(child) {
	var imgs = child.getElementsByTagName("img");
	for(j=0; j<imgs.length; j++) {
		if (zoom<-1) imgs[j].style.display = 'none';
		else {
			imgs[j].style.display = 'inline';
			imgs[j].style.width = (10+zoom)+'px';
			imgs[j].style.height = (10+zoom)+'px';
		}
	}
}