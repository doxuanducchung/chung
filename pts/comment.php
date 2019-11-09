<?
require_once("libs/_config.php");
require_once("libs/_mysql.php");
$DB = new DB;
$DB->connect();
require_once("libs/_functions.php");
$func = new func;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>Mr Bean Comment</TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<LINK 
rel=stylesheet type=text/css href="/demo/css/forumstyle.css">
<META name=GENERATOR content="MSHTML 8.00.6001.18702"></HEAD>

<?php
if (isset($_POST["f_poster"])) $cmposter=$_POST["f_poster"]; else $cmposter="";
if (isset($_POST["f_email"])) $cmemail=$_POST["f_email"]; else $cmemail="";
if (isset($_POST["f_displayemail"])) $cmdisplayemail=$_POST["f_displayemail"]; else $cmdisplayemail="";
if (isset($_POST["f_newsid"])) $cmnewsid=$_POST["f_newsid"]; else $cmnewsid="";
if (isset($_POST["f_linkcomment"])) $linkcomment=$_POST["f_linkcomment"]; else $linkcomment="";
if (isset($_POST["f_comment"])) $cmcomment=$func->smHTML($_POST["f_comment"]); else $cmcomment="";
if (!empty($cmdisplayemail)) $cmdisplayemail = 1; else $cmdisplayemail = 0;
if (isset($_POST['sendCM'])){
	if ($conf['display_comment'] == 'yes') {
	$DB->query("update ".$conf['perfix']."news set commentnum=commentnum+1 where newsid=$cmnewsid");
	$query="INSERT INTO ".$conf['perfix']."comment VALUES ('','{$cmnewsid}','{$cmposter}','{$cmemail}','{$cmdisplayemail}','{$cmcomment}','1')";
	$insert_q = $DB->query($query);}
	else {
	$query="INSERT INTO ".$conf['perfix']."comment VALUES ('','{$cmnewsid}','{$cmposter}','{$cmemail}','{$cmdisplayemail}','{$cmcomment}','0')";
	$insert_q = $DB->query($query);
	}
	
echo "<meta http-equiv='refresh' content='2; url=".$linkcomment."'>";	
}
if ($conf['display_comment'] == 'no') $thongbao = "Your Comment need time to public.";
?>


<BODY 
style="BACKGROUND: url(/demo/images/half_body.jpg) no-repeat" 
oncontextmenu="return false;" bgColor=#c2dd7e>
<TABLE border=0 width="100%" height=500>
  <TBODY>
  <TR>
    <TD height=500 vAlign=center width=800 align=middle>
      <DIV style="POSITION: absolute; TOP: 200px; LEFT: 300px" id=shacker>
      <TABLE class=tborder_gray border=0 cellSpacing=2 cellPadding=2 width=508 
      align=center>
        <TBODY>
        <TR>
          <TD class=tcat_orange align=left><IMG style="VERTICAL-ALIGN: middle" 
            src="/demo/images/lswitch.gif"><STRONG>Post Comment Succesfully!</STRONG></TD></TD></TR>
        <TR>
          <TD id=Des class=alt2 height=25 align=middle><STRONG 
            style="COLOR: #3399ff">&nbsp;</STRONG><FONT style="COLOR: green" 
            size=2>Thank for postting Comment. <?=$thongbao?> The site will be rederect within 02 seconds.<br>OR <a href="<?=$linkcomment?>">click here</a> if you don't want to wait !</FONT><br><br>
			<center><img src="<?=$conf['thumucroot']?>images/working.gif" border="0"></center></TD>
        <TR>
          <TD style="FILTER: Alpha(Opacity:90); CURSOR: default" bgColor=#f7f7f7 
          align=middle><IMG style="DISPLAY: none" id=Hinh class=style1 
            title="" border=0 name=HinhA> </TD></TR>
        <TR>
          <TD 
          style="BACKGROUND: url(/demo/images/cellpic1.gif) repeat-x left top" 
          class=thead height=25 align=middle></TD></TR></FORM>
        </TBODY></TABLE></DIV></TD>
    <TD vAlign=center rowSpan=6 align=right>


    </TD></TR></TBODY></TABLE></BODY></HTML>
	<?
	$DB->close();
	?>
