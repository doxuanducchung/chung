<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?=$sitetitle;?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<META content=INDEX,FOLLOW name=robots>
<META http-equiv=REFRESH content=5400>
<META content="photoshop online, photoshop tutorials,photo effects,photo retouch,photo drawing,photo designing,photo free,Google Adsense,AdSense for Content,AdSense for Feeds,AdSense for Search,Onsite Advertiser Sign-Up,Referrals,Google AdSense Help,AdSense Blog,Payments Guide Google AdSense,
conference calling,cheap auto insurance,consolidate student loan,life insurance quote,cheap car insurance, refinance home,
car insurance quote, refinancing, online car insurance, online insurance quotes, criminal defense lawyer, debt consolidation, refinance home mortgage,


" name=description>
<META content="photoshop online, photoshop tutorials,photo effects,photo retouch,photo drawing,photo designing,photo free,Google Adsense,AdSense for Content,AdSense for Feeds,AdSense for Search,Onsite Advertiser Sign-Up,Referrals,Google AdSense Help,AdSense Blog,Payments Guide Google AdSense,
conference calling,cheap auto insurance,consolidate student loan,life insurance quote,cheap car insurance, refinance home,
car insurance quote, refinancing, online car insurance, online insurance quotes, criminal defense lawyer, debt consolidation, refinance home mortgage," name=keywords>
<LINK href="<?=$conf['thumucroot']?>css/default.css" type=text/css rel=stylesheet>
<LINK media=screen href="<?=$conf['thumucroot']?>css/style.css" type=text/css rel=stylesheet>
<link rel="stylesheet" type="text/css" href="<?=$conf['thumucroot']?>css/menufooter.css" />
<link rel="stylesheet" type="text/css" href="<?=$conf['thumucroot']?>css/tooltip.css" />
<link rel="stylesheet" type="text/css" href="<?=$conf['thumucroot']?>css/menu_style.css" />
	<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="/pts/css/ie6.css" media="screen"/>
	<![endif]-->
<SCRIPT src="<?=$conf['thumucroot']?>js/tooltip.js" type=text/javascript></SCRIPT>
<SCRIPT src="<?=$conf['thumucroot']?>js/comment.js" type=text/javascript></SCRIPT>
<script type="text/javascript" src="<?=$conf['thumucroot']?>js/rounded-corners.js"></script>
<script type="text/javascript" src="<?=$conf['thumucroot']?>js/form-field-tooltip.js"></script>
<script type="text/javascript" src="<?=$conf['thumucroot']?>js/menu_click.js"></script>
<script type="text/javascript" src="<?=$conf['thumucroot']?>js/noimg.js"></script>
<style type="text/css">

/*Example CSS for the two demo scrollers*/

#pscroller1{
width: 272px;
height: 260px;
border: 0px solid black;
padding: 5px;
background-color: #FFFFFF;
}

#
.someclass{ //class to apply to your scroller(s) if desired
}

</style>
<?
require_once("libs/scroll.php");
?>
<script type="text/javascript">

/***********************************************
* Pausing up-down scroller- ?Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function pausescroller(content, divId, divClass, delay){
this.content=content //message array content
this.tickerid=divId //ID of ticker div to display information
this.delay=delay //Delay between msg change, in miliseconds.
this.mouseoverBol=0 //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
this.hiddendivpointer=1 //index of message array for hidden div
document.write('<div id="'+divId+'" class="'+divClass+'" style="position: relative; overflow: hidden"><div class="innerDiv" style="position: absolute; width: 100%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 100%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div></div>')
var scrollerinstance=this
if (window.addEventListener) //run onload in DOM2 browsers
window.addEventListener("load", function(){scrollerinstance.initialize()}, false)
else if (window.attachEvent) //run onload in IE5.5+
window.attachEvent("onload", function(){scrollerinstance.initialize()})
else if (document.getElementById) //if legacy DOM browsers, just start scroller after 0.5 sec
setTimeout(function(){scrollerinstance.initialize()}, 500)
}

// -------------------------------------------------------------------
// initialize()- Initialize scroller method.
// -Get div objects, set initial positions, start up down animation
// -------------------------------------------------------------------

pausescroller.prototype.initialize=function(){
this.tickerdiv=document.getElementById(this.tickerid)
this.visiblediv=document.getElementById(this.tickerid+"1")
this.hiddendiv=document.getElementById(this.tickerid+"2")
this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv))
//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px"
this.getinline(this.visiblediv, this.hiddendiv)
this.hiddendiv.style.visibility="visible"
var scrollerinstance=this
document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1}
document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0}
if (window.attachEvent) //Clean up loose references in IE
window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null})
setTimeout(function(){scrollerinstance.animateup()}, this.delay)
}


// -------------------------------------------------------------------
// animateup()- Move the two inner divs of the scroller up and in sync
// -------------------------------------------------------------------

pausescroller.prototype.animateup=function(){
var scrollerinstance=this
if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+5)){
this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-5+"px"
this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-5+"px"
setTimeout(function(){scrollerinstance.animateup()}, 50)
}
else{
this.getinline(this.hiddendiv, this.visiblediv)
this.swapdivs()
setTimeout(function(){scrollerinstance.setmessage()}, this.delay)
}
}

// -------------------------------------------------------------------
// swapdivs()- Swap between which is the visible and which is the hidden div
// -------------------------------------------------------------------

pausescroller.prototype.swapdivs=function(){
var tempcontainer=this.visiblediv
this.visiblediv=this.hiddendiv
this.hiddendiv=tempcontainer
}

pausescroller.prototype.getinline=function(div1, div2){
div1.style.top=this.visibledivtop+"px"
div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px"
}

// -------------------------------------------------------------------
// setmessage()- Populate the hidden div with the next message before it's visible
// -------------------------------------------------------------------

pausescroller.prototype.setmessage=function(){
var scrollerinstance=this
if (this.mouseoverBol==1) //if mouse is currently over scoller, do nothing (pause it)
setTimeout(function(){scrollerinstance.setmessage()}, 100)
else{
var i=this.hiddendivpointer
var ceiling=this.content.length
this.hiddendivpointer=(i+1>ceiling-1)? 0 : i+1
this.hiddendiv.innerHTML=this.content[this.hiddendivpointer]
this.animateup()
}
}

pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
if (tickerobj.currentStyle)
return tickerobj.currentStyle["paddingTop"]
else if (window.getComputedStyle) //if DOM2
return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top")
else
return 0
}

</script>

<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
</HEAD>

<BODY>
<DIV id=outter>
			<div id="header">
				<div class="logo fl">
					<a href="<?=$conf['rooturl']?>"><img class="img-logo" src="<?=$conf['thumucroot']?>images/logo.jpg" alt="" /></a>					
				</div>
				<div class="topbanner fr">
									
					<table border="0" cellpadding="0" cellspacing="0" style="height:83px; width:100%;">
						<tr>
							<td style="vertical-align:middle">							
								<?=get_header1();?>
								<?=get_header2();?>
							</td>                           
						</tr>
					</table>
					
				</div>
			</div>

	
	
<DIV id=topmenu1>

                            
		<TABLE cellpadding="0" cellspacing="0" width=100% valign="middle" >
        <TBODY>
        <TR>
          <TD valign="middle" align="left">
          <?
		  require_once("libs/menu.php");
		  ?>
		  </TD>
		</TR>
		</TBODY>
		</TABLE>
			
				</DIV>
				<DIV id=topmenu>
							<table align="middle" align="right" border="0" cellpadding="0" cellspacing="0" width="100%" height="30px"  style="background-image: url(<?=$conf['thumucroot']?>images/menu/menu2.gif)">
                                <tr>
								<td width="800px" valign="middle" style="padding-left: 25px">
								<?
								require_once("libs/theloai.php");
								?>
								
								</td>
								<td width="198px" style="display: none">
								
									<TABLE  cellSpacing=0>
										<TBODY>
		<FORM action=<?
		if ($conf['seo_link'] =='yes') echo '',$conf['thumucroot'],'search/';
		if ($conf['seo_link'] =='no') echo '?cmd=act:search';
		?>

		method=post>									
        <TR>
          <TD valign="middle"><INPUT size=20 value="Keywords" onblur="if (this.value=='') this.value='Keywords'" 
                        onfocus="this.value=''" name=keyword></TD>
          
          <TD valign="middle"><INPUT class=button type=submit value="Search" name=do_search></TD>
          </TR>
		  </FORM>
		  </TBODY></TABLE>
								
								</td>

                                </tr>
                            </table>
                        

</DIV>
<DIV class=xuongdong></DIV>


