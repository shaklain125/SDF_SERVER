/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var j;

for (j = 0; j < dropdown.length; j++) {
  dropdown[j].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block")
    {
      dropdownContent.style.display = "none";
    }else{
      dropdownContent.style.display = "block";
    }
  });
}

SetCaretAtEnd(element('searchBar'));

window.addEventListener('mouseup',function(e){
  var s = element("mySidebar");
  if(e.target != s && e.target.parentNode != s)
  {
    closeNav();
  }
  var s = element("searchBar");
  if(element("searchBarDiv").style.display == "block")
  {
    if(e.target != s  && e.target != element("searchBarDiv") && e.target != element("liveSearchDiv") && e.target.parentNode != element("liveSearchDiv") &&  e.target != element("searchCollapsebtn"))
    {
      element("searchBarDiv").style.display = "none";
    }
  }
});


function openNav() {
  element("menuBtn").style.display = "none";
  element("mySidebar").style.width = "50%";
}

function closeNav() {
  element("menuBtn").style.display = "block";
  element("mySidebar").style.width = "0";
}

function ToggleSearchBar() {
  element("searchBarDiv").style.display = element("searchBarDiv").style.display == 'none'? 'block' : 'none';
  if(element("searchBarDiv").style.display == 'block')
  {
    element("searchBar").focus();
  }else {
    element("searchBar").blur();
  }
  element("searchBarDiv").blur();
}

function element(id)
{
  return document.getElementById(id);
}
function jumpTo(h)
{
  var top = element(h).offsetTop;
  window.scrollTo(0,top);
}
function removeE(elementid) {
  var e = element(elementid);
  e.parentNode.removeChild(e);
}

function ShowSessionDivMsg(msg)
{
	var e = document.createElement('div');
	e.id = "message";
	e.innerHTML = msg;
	e.style.display = "block";
	document.body.appendChild(e);
}

function HideSessionDivMsg()
{
	var e = element("message");
	e.innerHTML = "";
	e.style.display = "none";
	document.body.removeChild(e);
}

function AddXandYScrollToForm(formid) {
  var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
      scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  var i = document.createElement("input")
  i.type="hidden";
  i.name="scrolly";
  i.value = scrollTop;
  var j = document.createElement("input")
  j.type="hidden";
  j.name="scrollx";
  j.value = scrollLeft;
  element(formid).appendChild(i);
  element(formid).appendChild(j);
}
function SetCaretAtEnd(elem) {
  var elemLen = elem.value.length;
  // For IE Only
  if (document.selection) {
      elem.focus();
      // Use IE Ranges
      var oSel = document.selection.createRange();
      // Reset position to 0 & then set at end
      oSel.moveStart('character', -elemLen);
      oSel.moveStart('character', elemLen);
      oSel.moveEnd('character', 0);
      oSel.select();
  }
  else if (elem.selectionStart || elem.selectionStart == '0') {
      // Firefox/Chrome
      elem.selectionStart = elemLen;
      elem.selectionEnd = elemLen;
      elem.focus();
  }
}
function searchForm() {
  var val = element('searchBar').value;
  if(val)
  {
    var emptyCheck = val.replace(/ /g, '');
    val = val.trimStart();
    val = val.trimEnd();
    element('searchBar').value = val;
    if(emptyCheck.length == 0)
    {
      return false
    }else {
      return true;
    }
  }else {
    return false;
  }
}

function ValidSearchQuery() {
  var val = element('searchBar').value;
  if(val)
  {
    var emptyCheck = val.replace(/ /g, '');
    val = val.trimStart();
    val = val.trimEnd();
    if(emptyCheck.length == 0)
    {
      return null
    }else {
      return val;
    }
  }else {
    return null;
  }
}

function changeClassStyle(classname, styleval) {
  var e = document.getElementsByClassName(classname)
  for(var x = 0; x < e.length; x++)
  {
    e[x].setAttribute('style', styleval)
  }
}

function LettersOnly(e) {
  if(e.key.match(/[A-Za-z]/g))
  {
    return true
  }else {
    return false
  }
}

function NoSpaces(e) {
  if(e.which != 32)
  {
    return true
  }else {
    return false
  }
}

function EmailCharsOnly(e) {
  if(e.key.match(/[A-Za-z]/g) || e.key.match(/[1234567890]/g) || e.key.match(/[\.\_\-\@]/))
  {
    return true
  }else {
    return false
  }
}

function NumbersOnly(e) {
  if(e.key.match(/[1234567890]/g))
  {
    return true
  }else {
    return false
  }
}

class ConfirmDialog {
  constructor(msg, yesFunct, noFunct) {
    var container = document.createElement("div");
    container.id = "confirmD1";
    container.style.position = "fixed"
    container.style.display = "none";
    container.style.width = "100%"
    container.style.height = "100%"
    container.style.top = "0"
    container.style.left = "0"
    container.style.right = "0"
    container.style.bottom = "0"
    container.style.backgroundColor = "rgba(0,0,0,0.5)"
    container.style.zIndex = "2"
    container.style.cursor = "default"
    container.addEventListener("click", function (e) {
      if(e.target == document.getElementById("confirmD1")){
        document.body.removeChild(document.getElementById("confirmD1"));
        noFunct();
      }
    });
    var box = document.createElement("div");
    box.style.padding = "10px";
    box.style.borderStyle = "solid";
    box.style.backgroundColor = "white";
    box.style.color = "black";
    box.style.overflow = "hidden";
    box.style.borderRadius = "25px";
    box.style.border = "none";
    box.style.width = "auto";
    box.style.height = "auto";
    box.style.boxSizing = "border-box";
    box.style.webkitBoxShadow = "0px 5px 25px 5px rgba(0,0,0,0.54)";
    box.style.mozBoxShadow = "0px 5px 25px 5px rgba(0,0,0,0.54)";
    box.style.boxShadow = "0px 5px 25px 5px rgba(0,0,0,0.54)";
    box.style.margin = "20%";
    box.style.marginTop = "80px";
    var messageDiv = document.createElement("div");
    messageDiv.innerHTML = msg;
    messageDiv.style.height = "auto";
    messageDiv.style.padding = "20px";
    messageDiv.style.marginBottom = "20px";
    messageDiv.style.boxSizing = "border-box";
    messageDiv.style.backgroundColor = "white";
    messageDiv.style.color = "black";
    var yesNoDiv = document.createElement("div");
    var yes = this.createYesNoBtn(true, yesFunct);
    yes.style.float = "right";
    yes.style.marginRight = "20px";
    var no = this.createYesNoBtn(false, noFunct);
    no.style.float = "right";
    yesNoDiv.appendChild(no);
    yesNoDiv.appendChild(yes);
    box.appendChild(messageDiv);
    box.appendChild(yesNoDiv);
    container.appendChild(box);
    document.body.appendChild(container);
  }

  createYesNoBtn(val, funct1)
  {
    var a = document.createElement("button")
    if(val)
    {
      a.innerHTML = "Yes";
    }else {
      a.innerHTML = "No";
    }
    a.style.padding="10px";
    a.style.borderRadius ="10px";
    a.style.border = "none";
    a.style.cursor ="pointer";
    a.addEventListener("mouseover",this.hoverBtn)
    a.addEventListener("mouseout",this.mouseOut)
    a.addEventListener("click", function () {
      var c = document.getElementById("confirmD1");
      document.body.removeChild(c);
      funct1();
    });
    return a;
  }

  hoverBtn(e){
    e.target.style.backgroundColor = "black";
    e.target.style.color = "white";
  }
  mouseOut(e)
  {
    e.target.style.backgroundColor = "";
    e.target.style.color = "black";
  }
  show()
  {
    document.getElementById("confirmD1").style.display = "";
  }
}
