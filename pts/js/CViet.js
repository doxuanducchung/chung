/* CViet 8/20/03: mViet10
* by SPham [mviet@socal.rr.com] 
* Copyright (c) 1999, 2000-2003 MDSS. Inc  All Rights Reserved.
* This code is fee on noncommercial. 
* Please DO NOT modify the code and then rename it or sell it.
* This copyright notice must remain intact within the srcipt.
*/	
var BoDauMV="sS|fF|rR|xX|jJ|aAeEoO|wW|wW|dD|"; 
var BDMV=BoDauMV.split('|');  
var MVOff = false; var eT=0; var bt=0;
var amdauStr = "|dz|ch|c|b|d|đ|gh|gi|g|h|kh|k|l|m|ngh|ng|nh|n|ph|qu|r|s|th|tr|t|v|x";
var amcuoiStr = "|ng|nh|ch|c|o|u|i|y|m|n|p|t";
var dauStr = "|sắc|huyền|hỏi|ngã|nặng";
var nguyenamAllStr = "oa|oá|oà|oả|oã|oạ*oă|oắ|oằ|oẳ|oẵ|oặ*uâ|uấ|uầ|uẩ|uẫ|uậ*oe|oé|oè|oẻ|oẽ|oẹ*uyê|uyế|uyề|uyể|uyễ|uyệ*uye|uyé|uyè|uyẻ|uyẽ|uyẹ*uy|uý|uỳ|uỷ|uỹ|uỵ*uê|uế|uề|uể|uễ|uệ*ue|ué|uè|uẻ|uẽ|uẹ*ia|ía|ìa|ỉa|ĩa|ịa*iê|iế|iề|iể|iễ|iệ*ie|ié|iè|iẻ|iẽ|iẹ*yê|yế|yề|yể|yễ|yệ*ye|yé|yè|yẻ|yẽ|yẹ*ua|úa|ùa|ủa|ũa|ụa*uô|uố|uồ|uổ|uỗ|uộ*ưa|ứa|ừa|ửa|ữa|ựa*ươ|ướ|ườ|ưở|ưỡ|ượ*ưo|ứo|ừo|ửo|ữo|ựo*uơ|uớ|uờ|uở|uỡ|uợ*uo|uó|uò|uỏ|uõ|uọ*ă|ắ|ằ|ẳ|ẵ|ặ*â|ấ|ầ|ẩ|ẫ|ậ*a|á|à|ả|ã|ạ*ê|ế|ề|ể|ễ|ệ*iu|íu|ìu|ỉu|ĩu|ịu*ư|ứ|ừ|ử|ữ|ự*u|ú|ù|ủ|ũ|ụ*ô|ố|ồ|ổ|ỗ|ộ*ơ|ớ|ờ|ở|ỡ|ợ*e|é|è|ẻ|ẽ|ẹ*o|ó|ò|ỏ|õ|ọ*i|í|ì|ỉ|ĩ|ị*y|ý|ỳ|ỷ|ỹ|ỵ*ơ|ớ|ờ|ở|ỡ|ợ";
var nguyenamAll=nguyenamAllStr.split('*');

for (var i =0; i< nguyenamAll.length; i++) nguyenamAll[i]=nguyenamAll[i].split('|');
var nguyenamStr=nguyenamAll[0][0]; for (var i=1; i< nguyenamAll.length; i++) nguyenamStr += "|"+nguyenamAll[i][0];
	
function VanDisplay (n, value, ord){
var src = document.selection.createRange();
var src3 = src.duplicate(); src3.moveStart("character", -1);
if (src3.text.length==0 ||" ,.;-='\"?<>/\\{}()*&1234567890".indexOf(src3.text)>-1 ) eT = 1;
else {
 var src2 = src.duplicate();  src2.moveStart("word", -1); 
 var textIn=src2.text; 
 if (n==4) {textIn=textIn+key;}  
  if (textIn.length > 0){
    var s = Parsing(textIn.toLowerCase());
    if (ck(s,n,ord)==0){ 
       var textOut=ModifyWord(s, n, value, ord);
       if (textOut != null) {textOut=match(textIn, textOut); 
       if (n==4){ eT=0; src2.text=textOut;}
       else if (textIn!=textOut)  src2.text=textOut;}  
    }}}}

function Parsing (word){
if (word=="gi" ) return "g|32,0,i||"; else if (word == "gin") return "g|32,0,i|n|";	
var L0=""; var L1=""; var L2="";
var indexAm=-1;
var amArr=amdauStr.split('|');
for (var i=1; i< amArr.length; i++)if (word.indexOf(amArr[i])==0) {indexAm= i; break;}
if (indexAm != -1) { L0 = amArr[indexAm]; word = word.substring(amArr[indexAm].length);}
if (word.length!=0) {
var iInd=-1; var jInd=-1; 
for (var i=0; i< nguyenamAll.length; i++){
	for (var j=0; j< nguyenamAll[i].length; j++){
	   var iT=word.indexOf(nguyenamAll[i][j]); 
	   if (iT==0){
	      iInd = i; jInd=j; 
	      if (iT>0) {if (L0=="") L0 = word.substring(0,iT); word=word.substring(iT);} 
	    break;}
	}  
if (iInd!=-1) break;
}
 
if (iInd != -1) { 
   var base = nguyenamAll[iInd][0]; 
   L1= iInd+","+ jInd+","+ base; word = word.substring(base.length);
 } //c1
	 	
if (word.length!=0){indexAm=-1;
var amArr=amcuoiStr.split('|');
for (var i=1; i< amArr.length; i++)if (word.indexOf(amArr[i])==0) {indexAm= i; break;}
if (indexAm != -1) { 
	L2=word.substring(0,amArr[indexAm].length); 
	word = word.substring(L2.length);}
}
}
return L0+"|"+L1+"|"+L2+"|"+word;	
}
//
function ModifyWord (s, n, value, ord){
eT=0; if (n==4) eT=1;

var newWord="";	
s=s.split('|');
var c=s[1].split(',');
switch(n){	
case 0: s[0]=value; break;
case 1: 
  if (s[1]=="") s[1]=""+ord+",0"+","+value;
  else {var c=s[1].split(','); s[1]=""+ord+","+c[1]+","+value;}
  break; 
case 2: s[2]=value; break;	  
case 3: 
var id2="aeiouy".indexOf(value)
if (id2>-1)
  if (c[2]==null){ ord =-1; eT=1;}
  else if (c[2].indexOf(value)==-1 )
        if ((id2==0 && c[2].indexOf('ă')==-1 && c[2].indexOf('â')==-1 )
            || (id2==1 && c[2].indexOf('ê')==-1 )|| (id2==3 && c[2].indexOf('ơ')==-1 && c[2].indexOf('ô')==-1 )
            || (id2==4 && c[2].indexOf('ư')==-1 )|| (id2==2 || id2==5) ){ ord =-1; eT=1;} 
if (ord==8) { 
  if (c[0]==0 || c[0]==1 ||c[0]==2 ||c[0]==21 ||c[0]==22 ||c[0]==23) ord = 8;
  else if (c[0]==15 ||c[0]==16 ||c[0]==17 ||c[0]==18  ||c[0]==19 ||c[0]==20 ||c[0]==26 ||c[0]==27 ||c[0]==28 ||c[0]==29 ||c[0]==31 ||c[0]==33 ) ord = 7; 
  else { ord =-1; eT=1;}
} 
  switch(ord){  
    case 1: case 2: case 3: case 4: case 5:
       if (ord==c[1]) { c[1]=0; eT=1;} else c[1]=ord; if (ord==2 & c[0]== 19) c[0]=17; break;
    case 9:
    	if (s[0]=="d") s[0] = 'đ'; else if (s[0] == 'đ') {s[0] = 'd', eT=1;} else eT=1; break;  
    case 6: 
      if (c[0]==2) {c[0]=14; eT = 1;} 
        else if (c[0]==5) c[0]=4;
      else if (c[0]==7) {c[0]=8; eT = 1;} else if (c[0]==8) c[0]=7; 
      else if (c[0]==10){c[0]=11;eT = 1;} else if (c[0]==11) c[0]=10; 
      else if (c[0]==12) {c[0]=13; eT = 1;} else if (c[0]==13) c[0]=12;else if (c[0]==14) c[0]=2; 
      else if (c[0]==15) {c[0]=20; eT = 1;}else if (c[0]==17) c[0]=15;
      else if (c[0]==20) c[0]=15; else if (c[0]==21) c[0]=22;
      else if (c[0]==22) {c[0]=23; eT = 1;} 
      else if (c[0]==23) c[0]=22; else if (c[0]==24) {c[0]=30; eT = 1;} 
      else if (c[0]==28) {c[0]=31; eT = 1;}else if (c[0]==29) {c[0]=28; eT = 1;} 
      else if (c[0]==30) {c[0]=24;}else if (c[0]==31) {c[0]=28;}else if (c[0]==34) {c[0]=28; eT = 1;}
      else eT=1;
      break 
      case 7: 
      if (c[0]==14) c[0]=16; else if (c[0]==15) c[0]=19;else if (c[0]==17) c[0]=20;
      else if (c[0]==18 ||c[0]==19 ) c[0]=17;else if (c[0]==16) {c[0]=14; eT = 1;}
      else if (c[0]==20 ) { if((s[0]=="th" || s[0]=="qu" || s[0]=="kh") && s[2].length==0 && (c[1]==3||c[1]==4||c[1]==0)) c[0]=19; else c[0]=17;  }      
      else if (c[0]==26) c[0]=27; else if (c[0]==27) c[0]=26; else if (c[0]==28) c[0]=29;
      else if (c[0]==29) {c[0]=31; eT = 1;} else if (c[0]==31) c[0]=29; 
      else if (c[0]==34) {c[0]=31; eT = 1;}
      else eT=1;
      break     	 
     case 8: 
      if (c[0]==0) c[0]=1; else if (c[0]==1) {c[0]=0; eT = 1;} else if (c[0]==21)  {c[0]=23; eT = 1;} 
      else if (c[0]==22) c[0]=21;else if (c[0]==23) {c[0]=21;} else eT=1;
      break 	 	
  }//ord
  break 
}
//
if (s[0]!='c' && c[2]=='o' && s[2]=='o') return null;
if (n==4 && s[2].length>0 && (c[0]== 18||c[0]== 19)) {c[0]=17;}     
if (s[0]!="" && s[1]!="" && s[3]==''){
  var c0=parseInt(c[0]);
  if (s[0]=="ng"  && (c0==9 ||c0==10 ||c0==24 ||c0==25 ||c0==30||c0==32))  s[0]="ngh";
  if (s[0]=="ngh" && (c0!=9 && c0!=10 && c0!=24 && c0!=25 && c0!=30 && c0!=32)) s[0]="ng";
  if (s[0]=="g"   && (c0==10 ||c0==24 ||c0==30) )  s[0]="gh";
  if (s[0]=="g"   && c0==32 && s[2].length>0 && s[2]!="n"  )  s[0]="gh";	 
}
//	
for (var i=0; i<3; i++){
if(s[i]!="")if (i!= 1) newWord += s[i]; 
else { 
var iInd=parseInt(c[0]);var jInd=parseInt(c[1]); 
if (newWord=="qu" && nguyenamAll[iInd][jInd].substring(0,1)=="u") newWord="q";

if (newWord=="c"  && (nguyenamAll[iInd][0]=="oa" ||nguyenamAll[iInd][0]=="oă" ||nguyenamAll[iInd][0]=="uâ" ||nguyenamAll[iInd][0]=="oe")) newWord="qu"+nguyenamAll[iInd][jInd].substring(1);
else if (newWord=="r"  && (nguyenamAll[iInd][0]=="oa" ||nguyenamAll[iInd][0]=="oă" ||nguyenamAll[iInd][0]=="uâ" ||nguyenamAll[iInd][0]=="oe")) newWord+=nguyenamAll[iInd][jInd].substring(1);
else if (newWord=="r"  && (nguyenamAll[iInd][0]=="uyê" || nguyenamAll[iInd][0]=="uy" || nguyenamAll[iInd][0]=="uê")) newWord = "d" + nguyenamAll[iInd][jInd];
else if ((newWord=="th" ||newWord=="q" ||newWord=="kh")&& s[2]=="" && nguyenamAll[iInd][0]=="ưo") newWord+= nguyenamAll[iInd+1][jInd];     
//else if (s[2]!="" && nguyenamAll[iInd][0]=="uơ") newWord+= nguyenamAll[iInd-1][jInd];     
else newWord += nguyenamAll[iInd][jInd];}
}
return newWord;}

document.onkeydown= function(e){
if  (document.all){
elm= event.srcElement;
ready = elm.type=='textarea' || (elm.type=='text' && elm.name!="BoDau"
&& elm.name!="Email" && elm.name!="Email2" && elm.name!="Email3"); 
key = event.keyCode; // Cap let

} else if (e && e.which && e.target){ 
elm= e.target;ready = elm.type=='textarea' || (elm.type=='text' && elm.name!="BoDau" && elm.name!="Email" && elm.name!="Email2" && elm.name!="Email3");
key=e.which; }
//if (ready)if (key==35) {if (MVOff) {MVietOnOffButton();} else {MVietOnOffButton(); } return;}
}//down
	
document.onclick= function(e){
if  (document.all){elm= event.srcElement;ready = elm.type=='textarea' || (elm.type=='text' && elm.name!="BoDau" && elm.name!="Email" && elm.name!="Email2" && elm.name!="Email3"); 
} else if (e && e.which && e.target){ elm= e.target; ready = elm.type=='textarea' || (elm.type=='text' && elm.name!="BoDau" && elm.name!="Email" && elm.name!="Email2" && elm.name!="Email3");}
if (ready) currElm = elm;
}
	
document.onkeypress= function(e){
if (ready){ currElm = elm; 
if  (document.all) key = String.fromCharCode(event.keyCode); else key = String.fromCharCode(e.which);  
  if(document.all){
   var src = document.selection.createRange(); 
     if (src.text.length == 0 && BoDauMV !="" && !MVOff) processLet(elm, event); 
  }  
}
}
function  processLet(txtArea, mvevent){  
var strOri = String.fromCharCode(mvevent.keyCode); 
var src = document.selection.createRange();
var src3 = src.duplicate();  src3.moveStart("word", -1);
var iDau=-1;
textIn=src3.text;

var nD = BoDauMV.indexOf(key);
if (nD>-1){var iT=0;
	for (var i=0; i<12; i++){if (BoDauMV.indexOf(key,iT)>-1){ iT=BoDauMV.indexOf('|',iT+1);  } else { iDau= i; break;}}
	if (iDau==8 && BoDauMV.indexOf(key,nD+1)>-1) bt= 1; else bt=0;
}
if (textIn.charAt(textIn.length-1) == ' ' )
   {if (key=='.'|| key=='?'){src.moveStart("character", -1); src.text=""; return} else return;}
if (textIn.length>0){ 
	if (iDau>-1 && iDau <12) { VanDisplay (3, key, iDau); if (eT==0) mvevent.returnValue=false;   return;}
    else if (key.toLowerCase()=='o' ||key.toLowerCase()=='n') {VanDisplay (4, '', -1); if (eT==0) mvevent.returnValue=false;}
}	
}

function match( oldW, newW ){
var sRet=""; var n= oldW.length;
if (n==0) sRet=newW;
else if (n==1) {if (oldW==oldW.toUpperCase()) sRet=newW.toUpperCase(); else sRet=newW;}
else {var f=oldW.charAt(0); var fL=oldW.toLowerCase().charAt(0);var sec=oldW.charAt(1); var secL=oldW.toLowerCase().charAt(1);if (f==fL) sRet=newW;
			else  {if (sec==secL) sRet=newW.toUpperCase().charAt(0)+newW.substring(1);else sRet=newW.toUpperCase();}	
		}
		return sRet;
} 
function ck(s1,n1,o1){ var iR=0;s1=s1.split("|"); if (s1[3].length > 0 || (s1[1].length==0 && s1[0]!='d' && s1[0]!='đ') ) {iR=34;eT=1;}else if (n1==3 && s1[0].length > 0 && s1[2].length > 0 && "tcp".indexOf(s1[2].charAt(0))>-1 && (o1==2 || o1==3 || o1==4) ) {iR=34; eT=1;} return iR;}  

function view_control_MViet () { 
text = "<form name=Bo_go_MViet_By_NDM method=post>";
text = text + "<table width=\"320\"  border=\"1\" tyle=\"border-collapse: collapse\" bordercolor=\"#969696\" cellspacing=1 cellpadding=1 align=center><tr><td bgcolor=\"#EFEFF0\" width=\"100%\" align=center>";
text = text + "<b>B&#7897; g&#245; (MViet) :</b>";
text = text + "<input type=radio name=NDM_mv10js onClick=\"MVOff = true;\" >T&#7855;t&nbsp;";
text = text + "<input type=radio name=NDM_mv10js onClick=\"MVOff = false; BoDauMV='1|`2|?3|~4|.5|^6|+*7=|(8|dD9|';\" >VIQR+&nbsp";
text = text + "<input type=radio name=NDM_mv10js onClick=\"MVOff = false; BoDauMV='sS|fF|rR|xX|jJ|aAeEoO|wW|wW|dD|';\" checked >Telex&nbsp"; 
text = text + "<input type=radio name=NDM_mv10js onClick=\"MVOff = false; BoDauMV='1|2|3|4|5|6|7|8|9|';\" >VNI";
text = text + "</td></tr></table>";
text = text + "</form>";
document.write(text);
}