var gdCtrl = new Object();
var goSelectTag = new Array();
var gcGray = "#cccccc";
var gcToggle = "#ffff00";
var gcBG = "#ffffff";
var gcBorder = "#009900";

var gdCurDate = new Date();
var giYear = gdCurDate.getFullYear();
var giMonth = gdCurDate.getMonth()+1;
var giDay = gdCurDate.getDate();

function fPopCalendar(popCtrl, dateCtrl){
  if (VicPopCal.style.visibility == "hidden")
  {
  event.cancelBubble=true;
  gdCtrl = dateCtrl;
  fSetYearMon(giYear, giMonth);
  var point = fGetXY(popCtrl);
  with (VicPopCal.style) {
  	left = point.x;
	top  = point.y+popCtrl.offsetHeight+1;
	width = VicPopCal.offsetWidth;
	height = VicPopCal.offsetHeight;
	fToggleTags(point);
	visibility = 'visible';
  }
  VicPopCal.focus();
  }
  else
  {fHideCalendar();}

}

function fSetDate(iYear, iMonth, iDay){

  var intHours, intMinutes, intSeconds;
  var today;
  today = new Date();

  intHours = today.getHours();
  intMinutes = today.getMinutes();
 	intSeconds = today.getSeconds();
	var txtMonth, txtDay;
	if (iMonth<10)
		txtMonth = "0" + iMonth;
	else 
		txtMonth = iMonth;
	if (iDay<10)
		txtDay = "0" + iDay;
	else
		txtDay = iDay;
		 
  gdCtrl.value = txtDay+"/"+txtMonth+"/"+iYear; //Here, you could modify the locale as you need !!!!
  fHideCalendar();
}

function fHideCalendar(){
  VicPopCal.style.visibility = "hidden";
  for (i in goSelectTag)
  	goSelectTag[i].style.visibility = "visible";
  goSelectTag.length = 0;
}

function fSetSelected(aCell){
  var iOffset = 0;
  var iYear = parseInt(tbSelYear.value);
  var iMonth = parseInt(tbSelMonth.value);

  aCell.bgColor = gcBG;
  with (aCell.children["cellText"]){
  	var iDay = parseInt(innerText);
  	if (color==gcGray)
		iOffset = (Victor<10)?-1:1;
	iMonth += iOffset;
	if (iMonth<1) {
		iYear--;
		iMonth = 12;
	}else if (iMonth>12){
		iYear++;
		iMonth = 1;
	}
  }
  fSetDate(iYear, iMonth, iDay);
}

function Point(iX, iY){
	this.x = iX;
	this.y = iY;
}

function fBuildCal(iYear, iMonth) {
  var aMonth=new Array();
  for(i=1;i<7;i++)
  	aMonth[i]=new Array(i);

  var dCalDate=new Date(iYear, iMonth-1, 1);
  var iDayOfFirst=dCalDate.getDay();
  var iDaysInMonth=new Date(iYear, iMonth, 0).getDate();
  var iOffsetLast=new Date(iYear, iMonth-1, 0).getDate()-iDayOfFirst+1;
  var iDate = 1;
  var iNext = 1;

  for (d = 0; d < 7; d++)
	aMonth[1][d] = (d<iDayOfFirst)?-(iOffsetLast+d):iDate++;
  for (w = 2; w < 7; w++)
  	for (d = 0; d < 7; d++)
		aMonth[w][d] = (iDate<=iDaysInMonth)?iDate++:-(iNext++);
  return aMonth;
}

function fDrawCal(iYear, iMonth, iCellHeight, iDateTextSize) {
  var WeekDay = new Array("CN","T 2","T 3","T 4","T 5","T 6","T 7");
  var styleTD = " bgcolor='"+gcBG+"' bordercolor='"+gcBorder+"' valign='middle' align='center' height='"+iCellHeight+"' style='font:bold "+iDateTextSize+" ËÎÌå;";            //Coded by Liming Weng(Victor Won) email:victorwon@netease.com

  with (document) {
	write("<tr>");
	for(i=0; i<7; i++)
		write("<td "+styleTD+"color:#990099' >" + WeekDay[i] + "</td>");
	write("</tr>");

  	for (w = 1; w < 7; w++) {
		write("<tr>");
		for (d = 0; d < 7; d++) {
			write("<td id=calCell "+styleTD+"cursor:hand;' onMouseOver='this.bgColor=gcToggle' onMouseOut='this.bgColor=gcBG' onclick='fSetSelected(this)'>");
			write("<font id=cellText Victor='Liming Weng'></font>");
			write("</td>")
		}
		write("</tr>");
	}
  }
}

function fUpdateCal(iYear, iMonth) {
  myMonth = fBuildCal(iYear, iMonth);
  var i = 0;
  for (w = 0; w < 6; w++)
	for (d = 0; d < 7; d++)
		with (cellText[(7*w)+d]) {
			Victor = i++;
			if (myMonth[w+1][d]<0) {
				color = gcGray;
				innerText = -myMonth[w+1][d];
			}else{
				color = ((d==0))?"red":"black";
				innerText = myMonth[w+1][d];
			}
		}
}

function fSetYearMon(iYear, iMon){
  tbSelMonth.options[iMon-1].selected = true;
  for (i = 0; i < tbSelYear.length; i++)
	if (tbSelYear.options[i].value == iYear)
		tbSelYear.options[i].selected = true;
  fUpdateCal(iYear, iMon);
}

function fPrevMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (--iMon<1) {
	  iMon = 12;
	  iYear--;
  }

  fSetYearMon(iYear, iMon);
}

function fNextMonth(){
  var iMon = tbSelMonth.value;
  var iYear = tbSelYear.value;

  if (++iMon>12) {
	  iMon = 1;
	  iYear++;
  }

  fSetYearMon(iYear, iMon);
}

function fToggleTags(){
  with (document.all.tags("SELECT")){
 	for (i=0; i<length; i++)
 		if ((item(i).Victor!="Won")&&fTagInBound(item(i))){
 			item(i).style.visibility = "hidden";
 			goSelectTag[goSelectTag.length] = item(i);
 		}
  }
}

function fTagInBound(aTag){
  with (VicPopCal.style){
  	var l = parseInt(left);
  	var t = parseInt(top);
  	var r = l+parseInt(width);
  	var b = t+parseInt(height);
	var ptLT = fGetXY(aTag);
	return !((ptLT.x>r)||(ptLT.x+aTag.offsetWidth<l)||(ptLT.y>b)||(ptLT.y+aTag.offsetHeight<t));
  }
}

function fGetXY(aTag){
  var oTmp = aTag;
  var pt = new Point(0,0);
  do {
  	pt.x += oTmp.offsetLeft;
  	pt.y += oTmp.offsetTop;
  	oTmp = oTmp.offsetParent;
  } while(oTmp.tagName!="BODY");
  return pt;
}

var gMonths = new Array("&nbsp;th&#225;ng 1","&nbsp;th&#225;ng 2","&nbsp;th&#225;ng 3","&nbsp;th&#225;ng 4","&nbsp;th&#225;ng 5","&nbsp;th&#225;ng 6","&nbsp;th&#225;ng 7","&nbsp;th&#225;ng 8","&nbsp;th&#225;ng 9","&nbsp;th&#225;ng 10","&nbsp;th&#225;ng 11","&nbsp;th&#225;ng 12");

with (document) {
write("<Div id='VicPopCal' onclick='event.cancelBubble=true' style='POSITION:absolute;visibility:hidden;border:2px ridge;width:10;z-index:100;'>");
write("<table border='0' bgcolor='#ffffcc'>");
write("<TR>");
write("<td valign='middle' align='center'><input type='button' name='PrevMonth' value='<' style='height:20;width:20;FONT:bold' onClick='fPrevMonth()'>");
write("&nbsp;<SELECT name='tbSelYear' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won'>");
for(i=1880;i<2020;i++)
	write("<OPTION value='"+i+"'>"+i+"</OPTION>");
write("</SELECT>");
write("&nbsp;<select name='tbSelMonth' onChange='fUpdateCal(tbSelYear.value, tbSelMonth.value)' Victor='Won'>");
for (i=0; i<12; i++)
	write("<option value='"+(i+1)+"'>"+gMonths[i]+"</option>");
write("</SELECT>");
write("&nbsp;<input type='button' name='PrevMonth' value='>' style='height:20;width:20;FONT:bold' onclick='fNextMonth()'>&nbsp;<input type='button' name='PrevMonth' value='X' style='height:20;width:20;FONT:bold 4' onClick='fHideCalendar()'>");
write("</td>");
write("</TR><TR>");
write("<td align='center'>");
write("<DIV style='background-color:teal'><table width='100%' border='0' cellpadding='2'>");
fDrawCal(giYear, giMonth, 12, 12);
write("</table></DIV>");
write("</td>");
write("</TR><TR><TD align='center'>");
write("<B style='cursor:hand;font:bold ËÎÌå' onclick='fSetDate(giYear,giMonth,giDay)' onMouseOver='this.style.color=gcToggle' onMouseOut='this.style.color=0'>H&#244;m nay :</B> "+giMonth+"/"+giDay+"/"+giYear+" (mm/dd/yy)");
write("</TD></TR>");
write("</TABLE></Div>");
}
function fSetloile(nam,thang,ngay,gio,phut,giay){

  document.acv.dc.value = nam+"-"+thang+"-"+ngay+" -- "+gio+":"+phut+":"+giay;
//    document.acv.dc.value = iYear+"-"+iMonth+"-"+iDay+' '+gio+':'+phut+':'+giay; //Here, you could modify the locale as you need !!!!
}

function tick() {
  var hours, minutes, seconds, ap;
  var intHours, intMinutes, intSeconds;
  var today;

  today = new Date();

  intHours = today.getHours();
  intMinutes = today.getMinutes();
  intSeconds = today.getSeconds();

  switch(intHours){
       case 0:
           intHours = 12;
           hours = intHours+":";
           ap = "A.M.";
           break;
       case 12:
           hours = intHours+":";
           ap = "P.M.";
           break;
       case 24:
           intHours = 12;
           hours = intHours + ":";
           ap = "A.M.";
           break;
       default:    
           if (intHours > 12)
           {
             intHours = intHours - 12;
             hours = intHours + ":";
             ap = "P.M.";
             break;
           }
           if(intHours < 12)
           {
             hours = intHours + ":";
             ap = "A.M.";
           }
    }       
  
          
  if (intMinutes < 10) {
     minutes = "0"+intMinutes+":";
  } else {
     minutes = intMinutes+":";
  }

  if (intSeconds < 10) {
     seconds = "0"+intSeconds+" ";
  } else {
     seconds = intSeconds+" ";
  } 

  timeString = hours+minutes+seconds+ap;

  Clock.innerHTML = timeString;

  window.setTimeout("tick();", 100);
}

function isDate(dateStr) 
{

    var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    var matchArray = dateStr.match(datePat); // is the format ok?

    if (matchArray == null) {
        alert("Vui long nhap dinh dang ngay la` thang/ngay/nam or thang-ngay-nam.");
        return false;
    }

    month = matchArray[1]; // parse date into variables
    day = matchArray[3];
    year = matchArray[5];

    if (month < 1 || month > 12) { // check month range
        alert("thang phai giua 1 va 12.");
        return false;
    }

    if (day < 1 || day > 31) {
        alert("ngay phai giua 1 va 31.");
        return false;
    }

    if ((month==4 || month==6 || month==9 || month==11) && day==31) {
        alert("thang "+month+" khong co' ngay 31!")
        return false;
    }

    if (month == 2) { // check for february 29th
        var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
        if (day > 29 || (day==29 && !isleap)) {
            alert("thang 2 " + year + " khong co'  " + day + " ngay!");
            return false;
        }
    }
    return true; // date is valid
}
function isDateVN(dateStr) 
{

    var datePat = /^(\d{1,2})(\/)(\d{1,2})(\/)(\d{4})$/;
    var matchArray = dateStr.match(datePat); // is the format ok?

    if (matchArray == null) {
        alert("Vui lÃ²ng nháº­p Ä‘á»‹nh dáº¡ng lÃ  NgÃ y/ThÃ¡ng/NÄƒm.");
        return false;
    }

    month = matchArray[3]; // parse date into variables
    day = matchArray[1];
    year = matchArray[5];

    if (month < 1 || month > 12) { // check month range
        alert("thang phai giua 1 va 12.");
        return false;
    }

    if (day < 1 || day > 31) {
        alert("ngay phai giua 1 va 31.");
        return false;
    }

    if ((month==4 || month==6 || month==9 || month==11) && day==31) {
        alert("thang "+month+" khong co' ngay 31!")
        return false;
    }

    if (month == 2) { // check for february 29th
        var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
        if (day > 29 || (day==29 && !isleap)) {
            alert("thang 2 " + year + " khong co'  " + day + " ngay!");
            return false;
        }
    }
    return true; // date is valid
}