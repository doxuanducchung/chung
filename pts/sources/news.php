<?
if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
if ( !empty($input["newsid"]) && is_numeric($input["newsid"]) ) {
     $newsid =$input["newsid"];
}
$query="select * from ".$conf['perfix']."news where newsid=$newsid";
$newsresult = $DB->query($query);
$DB->query("update ".$conf['perfix']."news set viewnum=viewnum+1 where newsid=$newsid");
$code = $_SESSION['chf_secu_code']=rand(100000,999999);
?>

<?php
    if ($news=$DB->fetch_row($newsresult)){
        $cat_id =$news["catalogid"];
        $result=$DB->query ("select * from ".$conf['perfix']."catalog where catalogid=$cat_id and hienthi!=0");
        if ($cat=$DB->fetch_row($result)) 
		$pcat_id=$cat["parentid"];
		$pcat_type = $cat["type"];
		$pcat_parentid = $cat["parentid"];
        $cat_name =$cat["catalogname"] ;
		$ccat_name = mahoa($cat_name);
		if ($pcat_type == 1) {
		$resultparent=$DB->query ("select * from ".$conf['perfix']."catalog where catalogid=$pcat_parentid and hienthi!=0");
		if ($catparent=$DB->fetch_row($resultparent))
		$parentcatid = $catparent["catalogid"];
		$parentcatname = $catparent["catalogname"];
		$cat_link = "<A href=".$conf['rooturl']." class=link-home>Home</A> &raquo;<A href = ".$func->seolinkmain(mahoa($parentcatname), $parentcatid)." class=link-parent>&nbsp;".$parentcatname."</A> &raquo;<A href = ".$func->seolinkmain(mahoa($cat_name), $cat_id)." class=link-cat>&nbsp;".$cat_name."</A>  ";
		} else $cat_link = "<A href=".$conf['rooturl']." class=link-home>Home</A> &raquo;<A href = ".$func->seolinkmain(mahoa($cat_name), $cat_id)." class=link-cat>&nbsp;".$cat_name."</A>  ";
		$eventid = $news["eventid"];
		$linkcat = "".$func->seolinkmain($ccat_name,$cat_id)."";
		$newsid = $news["newsid"];
        $title = $news["title"];
		$ttitle = mahoa($title);
		$linkbanin = "".$func->seolinkbanin($ttitle, $newsid)."";
		$linkcomment = "".$func->seolinknews($ttitle,$newsid)."";
        $content = $news["content"];
        $content = str_replace('<img','<img class="imgbaiviet" alt="Bấm vào ảnh để xem kích thước thật" onclick="window.open(this.src)"',$content);
		$content = str_replace('<IMG','<IMG class="imgbaiviet" alt="Bấm và hình để xem kích thước thật" onclick="window.open(this.src)"',$content);
		$short = $func->HTML($news["short"]);
        $picture = $news["picture"];
		$picture1 = $news["picture"];
        $pic_des = $news["pic_des"];
        $eventid = $news["eventid"];
        $source = $func->HTML($news["source"]);
        $postime = $news["timepost"];
		$adddate = $news["adddate"];
        $datepost = "".gmdate("d/m/Y, h:i A",$news["timepost"] + 7*3600)."";
		if (!empty($news['picture'])) {
                     if ( (!strstr($news['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/";
                           $src = $path.$news['picture'];
                     } else
                           $src = $news['picture'];
						   }
        if (!empty($picture)){
            $tmpv1 = gmdate("Y", $postime + 7*3600);
            $tmpv2 = gmdate("m", $postime + 7*3600);
            $tmpv3 = gmdate("d", $postime + 7*3600);
            $pathv = $conf['rooturl']."images/news/".$tmpv1."/".$tmpv2."/".$tmpv3."/";
            $pathv_full = $conf['rooturl']."images/news/".$tmpv1."/".$tmpv2."/".$tmpv3."/";
            
        }
        if (empty($source)) $source = "<b>".$conf['source']."</b>";
        else $source = "<b>Mr Bean</b> (<i>Theo <b>".$source."</b></i>)";
		$cmresult=$DB->query ("select * from ".$conf['perfix']."comment where newsid=$newsid and cmactive=1 order by cmid DESC");
		if ($DB->num_rows($cmresult)) {
		$commentform_p = "display:none;"; 
		$commentlist_p = ""; 
		$comment_s = "&nbsp;&nbsp;&nbsp;<a class=Time href=\"javascript:void(0);\" class=\"commentbox\" onclick=\"javascript:showhide('commentform'); showhide('commentlist');\">[ <b>Show the Comment list</b> ]</a>";
		$comment_h = "<tr><td height=\"10\"></td><td align=\"right\" valign=\"middle\" style=\"padding-right: 10px\"><a href=\"javascript:void(0);\" class=Time onclick=\"javascript:showhide('commentform'); showhide('commentlist');\">[ <b>Show the Comment list</b> ]</a></td></tr>";
		} else {
		$commentform_p = ""; 
		$commentlist_p = "display: none;"; 
		$comment_s = ""; 
		$comment_h = "";
		}
		$resultevent=$DB->query ("select * from ".$conf['perfix']."news where eventid = $eventid and isdisplay!=0 order by newsid DESC LIMIT 0,4");
?>
<DIV id=content>
<DIV class="content-center fl">
<DIV class="thumuc"><?=$cat_link?></DIV>
<DIV class="content" style="WIDTH: 488px; MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; BORDER-TOP: 0px solid #ccc; BORDER-BOTTOM: 0px solid #ccc; BORDER-RIGHT: 1px solid #ccc; BORDER-LEFT: 1px solid #ccc; PADDING-TOP: 5px; PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px">
<P class=Title><?=$title?></P><BR><font color="#E94C37" size="2"><i>( Updated: <?=$datepost?> )</i></font>
<P class=Lead><CENTER><img src="<?=$src?>" style="width: 248px; height: 186px;  margin-bottom: 10px; border: 1px solid #ccc; padding: 2px" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"><br>
</CENTER>
<font color="#000000"><i><?=$conf['source']?></i></font> - <?=$short?><br><br>

<?
        while ($news_e=$DB->fetch_row($resultevent)) {
		$newsid_e = $news_e["newsid"];
		$title_e = $news_e["title"];
		$ttitle_e = mahoa($title_e);
		$linknews_e = $func->seolinknews($ttitle_e, $newsid_e);
?>
<a href ="<?=$linknews_e?>" class=link-home>&raquo;<?=$title_e?></a><br><br>
<?
		} 
?>
<?=$content?></P>
<P class=Normal align="right">
</P>
</DIV>
<DIV class="fr txtr" style="WIDTH: 488px; MARGIN-BOTTOM: 0px; PADDING-RIGHT: 10px; PADDING-TOP: 5px; 
PADDING-BOTTOM: 5px; BORDER-TOP: 0px solid #ccc; BORDER-BOTTOM: 0px solid #ccc; BORDER-RIGHT: 1px solid #ccc; BORDER-LEFT: 1px solid #ccc;">
<?=$source?>
</DIV>
<DIV class="fr txtr" style="WIDTH: 488px; MARGIN-BOTTOM: 10px; PADDING-RIGHT: 10px; PADDING-TOP: 5px; 
PADDING-BOTTOM: 5px; BORDER-TOP: 0px solid #ccc; BORDER-BOTTOM: 1px solid #ccc; BORDER-RIGHT: 1px solid #ccc; BORDER-LEFT: 1px solid #ccc;"><A class=Time 
href="<?=$linkbanin?>" target="_blank">[Print tutorial]</A>&nbsp;
<A onclick="javascript:showhide('send_f');" href="javascript:void(0);" class=Time>[Send to friends]</A>&nbsp;
<A onclick="javascript:history.go(-1);" class=Time 
href="#">[Prevous page]</A>&nbsp;
<A class=Time 
href="#">[Go to TOP]</A>

</DIV>

<DIV class=content id="send_f" style="display:none; border: 1px solid #ccc; width: 488px; padding-left: 10px; margin-bottom: 10px; background-color: #FFFFFF">

<TABLE width="465" style="background-color: #FFFFFF" style="margin-bottom: 10px">
  <TBODY>


	<TR>
    <TD align="center" valign="middle" style="border-bottom: 1px solid #ccc;">
	<font color="#006666" size=3><b>Copy the code below and send to your frients !&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<A onclick="javascript:showhide('send_f');" href="javascript:void(0);" class=Time>[<B> Hide this form! </B>]</A></font>
	</TD>
	</TR>
	<TR>
    <TD id="copytext" align="left" style="padding-top: 2px; padding-bottom: 3px; padding-left: 30px">

	<b>This is the good tutorial!.</b><br>
	<b>Title:</b> <?=$title?><br>
	<b>Link:</b>
	<?
	if ($conf['seo_link'] == 'yes') echo '',$conf['rooturl'],'news-detail/',$newsid,'/',$ttitle,'.html';
	if ($conf['seo_link'] == 'no') echo '',$conf['rooturl'],'?cmd=act:news|newsid:',$newsid,''
	?>
	
	<br>
	
	</TD>
	</TR>
	
	</TBODY>
</TABLE>
	
	
</DIV>

<?
if ($conf['display_commentbox'] =='yes') {
?>

<DIV class=content id="commentlist" style="<?=$commentlist_p?> border: 1px solid #ccc; width: 488px; padding-left: 10px; margin-bottom: 10px; background-color: #FFFFFF; margin-top: 5px">

<TABLE width="478" style="background-color: #FFFFFF" style="margin-bottom: 10px">
  <TBODY>
  <TR>
    <TD style="border-bottom: 1px solid #ccc; padding-top: 2px; padding-bottom: 3px">
	<font color="#006666" size="3"><b>.: Comment List :.</b></font> &nbsp;&nbsp;&nbsp;<a class=Time href="javascript:void(0);" class="commentbox" onclick="javascript:showhide('commentform'); showhide('commentlist');">[ <b>Show the Comment form</b> ]</a>
	</TD>
	</TR>
	<TR>
    <TD style="padding-bottom: 3px">
	<DIV style="overflow:auto; height: 230px; width: 478px; border-bottom: 1px solid #ccc"
<?

while ($rowcm = $DB->fetch_row($cmresult)){
		$nguoiviet = $rowcm["cmposter"];
		$disemail = $rowcm["cmdisemail"];
		$email = $rowcm["cmemail"];
		$noidung = $rowcm["cmcomment"];
		if ($disemail == 1) $emailp = "<font color=red><b>&raquo; Email:</b></font> <font color=\"006666\"><b>".$email."</b></font><br>";
		else $emailp = "";
		
?>
  

	
	<font color=red><b>&raquo; Poster:</b></font> <font color="006666"><b><?=$nguoiviet?></b></font><br>
	<?=$emailp?>
	<?=$noidung?><br><br>
	
	
	
	<?
	}
	?>
  </DIV></TD>
	</TR>
	</TBODY>
</TABLE>
	
	
</DIV>

<DIV class=content style="margin-top: 5px">

<script language="javascript">
          function checkform(f){
                   var poster = f.f_poster.value;
                   if (poster == '') {
                       alert('Bạn chưa điền tên của bạn.\nHãy điền tên của bạn vào!');
                       f.f_poster.focus();
                       return false;
                   }
                   
                   var comment = f.f_comment.value;
                   if (comment == '') {
                       alert('Bạn chưa nhập nội dung Comment.\nBạn vui lòng nhập nội dung Comment!');
                       f.f_comment.focus();
                       return false;
                   }
				   var commentt = f.f_comment.value;
                   if (commentt.length <90) {
                       alert('Nội dung Comment quá ngắn.\nBạn vui lòng nhập lại');
                       f.f_comment.focus();
                       return false;
                   }
				   var secode = f.f_secode.value;
                   if (secode != <?=$code?>) {
                       alert('Bạn chưa điền đúng (hoặc chưa điền) Mã xác nhận.\nBạn hãy kiểm tra và nhập lại đúng Mã xác nhận!');
                       f.f_secode.focus();
                       return false;
                   }
				   
				   
				   //Moi them vao
		
		var at="@"
        var dot="."
        var lat=f.f_email.value.indexOf(at)
        var lstr=f.f_email.value.length
        var ldot=f.f_email.value.indexOf(dot)
        if (f.f_email.value.indexOf(at)==-1 || f.f_email.value.indexOf(at)==0 ||
                f.f_email.value.indexOf(at)==lstr){
           alert("Your Email is invalid.\nPlease retype your email. Thanks!")
		   f.f_email.focus()
		   f.f_email.value=""
           return false
        }              
        if (f.f_email.value.indexOf(dot)==-1 || f.f_email.value.indexOf(dot)==0 ||
                f.f_email.value.indexOf(dot)==lstr){
            alert("Your Email is invalid.\nPlease retype your email. Thanks!")
			f.f_email.focus()
			f.f_email.value=""
            return false
        }       
        if (f.f_email.value.indexOf(at,(lat+1))!=-1){
            alert("Your Email is invalid.\nPlease retype your email. Thanks!")
			f.f_email.focus()
			f.f_email.value=""
            return false
         }
         if (f.f_email.value.substring(lat-1,lat)==dot || 
                 f.f_email.value.substring(lat+1,lat+2)==dot){
            alert("Your Email is invalid.\nPlease retype your email. Thanks!")
			f.f_email.focus()
			f.f_email.value=""
            return false
         }           
         if (f.f_email.value.indexOf(dot,(lat+2))==-1){
            alert("Your Email is invalid.\nPlease retype your email. Thanks!")
			f.f_email.focus()
			f.f_email.value=""
            return false
         }
         if (f.f_email.value.indexOf(" ")!=-1){
            alert("Your Email is invalid.\nPlease retype your email. Thanks!")
			f.f_email.focus()
			f.f_email.value=""
            return false
         }
				   //Het phan them vao
                   return true;
		}
          
  </script>


<table width="500" cellpadding="0" cellspacing="0" border="0" id="commentform" style="<?=$commentform_p?> border: 1px solid #ccc; margin-bottom: 10px; background-color: #FFFFFF; padding-bottom: 5px">
		 <tr>
		 <TD colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 2px; padding-bottom: 3px">
		 <font color="#006666" size="3"><b>&nbsp;&nbsp;&nbsp;&nbsp;.: Comment :.</b></font><?=$comment_s?>
		 </td></tr>
         <form action="<? if ($conf['seo_link'] == 'yes') echo ''.$conf['thumucroot'].'comment/';
		 if ($conf['seo_link'] == 'no') echo 'comment.php';?>
		 " method="post" onSubmit="return checkform(this);">
         <tr><td colspan="2" height="10"></td></tr>
         <tr><td width="120" height="22" align="right" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"><b>Your name:</b> </td><td align="left"><input type="text" name="f_poster" id="f_poster" maxlenght="150" style="width:300px;" tooltipText="Bạn hãy điền tên hoặc nickname của bạn.<br>Bạn nên điền tên bằng Tiếng Việt có dấu, nếu là nickname thì có thể không cần!"></td></tr>
         <tr><td height="22" align="right" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"><b>Your Email:</b> </td><td align="left"><input type="text" name="f_email" id="f_email" maxlenght="150" style="width:300px;" tooltipText="Bạn hãy điền Email của bạn.<br>Bạn nên điền Email bạn đang sử dụng, để nếu cần thì liên lạc với bạn sẽ dễ dàng hơn. Nếu bạn không muốn người khác biết Email của bạn, bạn hãy bỏ dấu tích V ở bên dưới!"></td></tr>
		 <tr><td></td><td height="22" align="left" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"><input type="checkbox" name="f_displayemail" id="f_displayemail" value="1" checked>Public your Email</td></tr>
		 <?
		 if ($conf['enable_smiles'] == 'yes') {
		 ?>
		 <tr><td width="120" height="22" align="right" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"></td><td align="left">
		 <DIV class=smiles style="float: left; width: 320px">
		 <SCRIPT type=text/javascript>
    function grin(tag) {
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('f_comment') && document.getElementById('f_comment').type == 'textarea') {
    		myField = document.getElementById('f_comment');
    	} else {
    		return false;
    	}
    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	}
    	else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos)
    					  + tag
    					  + myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	}
    	else {
    		myField.value += tag;
    		myField.focus();
    	}
    }
    
    function moreSmilies() {
    	document.getElementById('wp-smiley-more').style.display = 'inline';
    	document.getElementById('wp-smiley-toggle').innerHTML = '<br><a class="link-othernews" href="javascript:lessSmilies()">&laquo;&nbsp;Close</a></span>';
    }
    
    function lessSmilies() {
    	document.getElementById('wp-smiley-more').style.display = 'none';
    	document.getElementById('wp-smiley-toggle').innerHTML = '<br><a class="link-othernews" href="javascript:moreSmilies()">View All Smiles&nbsp;&raquo;</a>';
    }
    </SCRIPT>
<?
$i = 0;
while ($i<11) {
$i ++;
echo '<IMG onclick=\'grin("[sm](',$i,')[/sm]")\' src="',$conf['thumucroot'],'smiles/(',$i,').gif" style="margin-left: 5px; margin-right: 5px;">';
}
echo '<SPAN id=wp-smiley-more style="DISPLAY: none">';

$j = 11;
while ($j<47) {
$j ++;
echo '<IMG onclick=\'grin("[sm](',$j,')[/sm]")\' src="',$conf['thumucroot'],'smiles/(',$j,').gif" style="margin-left: 5px; margin-right: 5px;">';
}
?>
</SPAN>
<SPAN id=wp-smiley-toggle>
<A class="link-othernews" href="javascript:moreSmilies()"><br>View All Smiles&nbsp;&raquo;</A>
</SPAN>
</DIV></td></tr>
<?
}
?>

<tr><td align="right" valign="top" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"><b>Comment:</b></td><td align="left">
		 
		 
		 <textarea name="f_comment" id="f_comment" size="2" cols="50" rows="7" style="width: 300px; height: 85px; font-family: Arial; font-size: 12px;" tooltipText="Bạn hãy nhập nội dung Comment.<br><u>Lưu ý</u>: Comment phải gõ bằng Tiếng Việt có dấu. Mọi Comment không theo chuẩn Tiếng Việt, không phù hợp với thuần phong mỹ tục Việt Nam, dùng từ ngữ phản cách mạng là không hợp lệ và sẽ bị chặn (bị xoá) bởi Admin !"></textarea></td></tr>
         <tr><td height="22" align="right" style="padding-right:5px; padding-top: 2px; padding-bottom: 2px"><b>Code:</b></td><td><div style="background-image:url(<?php echo $conf['rooturl'];?>images/bgmain.gif);margin-right:5px;width:60px;height:20px;text-align:center;float:left; border: 1px solid #ccc"><font size="3" color="#006666"><b><?php echo $code;?></b></font></div><div style="float:left;">->Enter the code here!</div><div style="float:left;width:60px; margin-left: 5px"><input name="f_secode" id ="f_secode" type="text" size="10" maxlength="6" style="width:60px;" tooltipText="Bạn hãy nhập chính xác Mã xác nhận hiển thị ở bên cạnh!"></div></td></tr>
		 <tr><td colspan="2" height="5"></td></tr>
         <tr><td height="22"></td><td align="left"><input type="submit" name="sendCM" value="Post Comment"> <input type="reset" value="Reset"></td></tr>
         <tr><td colspan="2" height="10"></td></tr>
		 <input type="hidden" name="f_linkcomment" value="<?=$linkcomment?>">
		 <input type="hidden" name="f_newsid" value="<?=$newsid?>">
         </form>
     </table>
</DIV>
  <script type="text/javascript">
var tooltipObj = new DHTMLgoodies_formTooltip();
tooltipObj.setTooltipPosition('right');
tooltipObj.setPageBgColor('#EEEEEE');
tooltipObj.setTooltipCornerSize(15);
tooltipObj.initFormFieldTooltip();
</script>

<?

}

$subcat="";
       $resultSCat = $DB->query("select * from ".$conf['perfix']."catalog where parentid=$cat_id");
       while ($rowSCat = $DB->fetch_row($resultSCat)){
              $subcat.= $rowSCat["catalogid"].",";
       }
       if (!empty($conf['cat_link'])) $n=intval($conf['cat_link']);
       else $n=10;
       if (!empty ($subcat)){
           $subcat1 = substr($subcat,0,-1);
           $subcat1 = str_replace(",","','",$subcat1);
           $query1 = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and (catalogid=$cat_id or catalogid in ('".$subcat1."')) and  newsid > $newsid order by newsid DESC LIMIT 0,$n";
       } else
           $query1 = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and catalogid=$cat_id  and  newsid > $newsid order by newsid DESC LIMIT 0,$n";
       $news_order = $DB->query($query1);
       if ($DB->num_rows($news_order)){
  ?>
<DIV class=folder-news style="MARGIN-BOTTOM: 10px">
<DIV class=othernews-header>
<DIV class="othernews-title fl">.: Newer tutorials :.</DIV>
</DIV>
<DIV class=othernews>
<UL>
<?php
           while ($row1 =$DB->fetch_row($news_order)){
					$newsida1 = $row1["newsid"];
					$titlea1 = $row1["title"];
					$ttitlea1 = mahoa($titlea1);
					$linknewsa1 = "".$func->seolinknews($ttitlea1, $newsida1)."";
                  $date1 = "(".gmdate("d/m/Y",$row1["timepost"] + 7*3600).")";
  ?>
  <LI><A class=link-othernews href="<?=$linknewsa1?>"><?=$titlea1?></A>
  <SPAN style="COLOR: #909090">&nbsp;<?=$date1?></SPAN> 
  
  <?
  }
  ?>
  </UL></DIV></DIV>
  <?
  }
  ?>
  <?
if (!empty ($subcat)){
           $subcat2 = substr($subcat,0,-1);
           $subcat2 = str_replace(",","','",$subcat2);
           $query2 = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and (catalogid=$cat_id or catalogid in ('".$subcat2."')) and  newsid < $newsid order by newsid DESC LIMIT 0,$n";
       } else
           $query2 = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and catalogid=$cat_id  and  newsid < $newsid order by newsid DESC LIMIT 0,$n";
       $news_order1 = $DB->query($query2);
       if ($DB->num_rows($news_order1)){
  ?>
  <DIV class=folder-news style="MARGIN-BOTTOM: 10px">
<DIV class=othernews-header>
<DIV class="othernews-title fl">.: Others tutorials :.</DIV>
</DIV>
<DIV class=othernews>
<UL>
<?php
           while ($row2 =$DB->fetch_row($news_order1)){
					$newsida2 = $row2["newsid"];
					$titlea2 = $row2["title"];
					$ttitlea2 = mahoa($titlea2);
					$linknewsa2 = "".$func->seolinknews($ttitlea2, $newsida2)."";
                  $date2 = "(".gmdate("d/m/Y",$row2["timepost"] + 7*3600).")";
  ?>
  <LI><A class=link-othernews href="<?=$linknewsa2?>"><?=$titlea2?></A>
  <SPAN style="COLOR: #909090">&nbsp;<?=$date2?></SPAN> 
  
  <?
  }
  ?>
  </UL></DIV></DIV>


<?
}

?>



</DIV>

<DIV style="float: left">
<DIV class="linksite1 fl">
<DIV class="toplist-content fl" id=toplist>
<DIV class=baivietmoi>Bài viết mới nhất</DIV>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by newsid DESC LIMIT 0,7";
	   
       $result1 = $DB->query ($sql1) ;
       while ($row1 = $DB->fetch_row($result1)){
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
			  $solanxem=$news1["viewnum"];
			  $newsid1 = $news1["newsid"];
			  $tieude1 = $news1["title"];
			  $short1 = $news1["short"];
			  $ttieude1 = mahoa($tieude1);
			  $linknews1 = "".$func->seolinknews($ttieude1, $newsid1)."";
			  $adddate1 = $news1["adddate"];
			 
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 
				
              }
  ?>


  <LI style="BACKGROUND-IMAGE: url(<?=$conf['thumucroot']?>images/background/gray-square.gif)">
  <A class=link-toplist href="<?=$linknews1?>"><?=$tieude1?></A> 
  
  <?
  }
  ?>
  
  </UL></DIV>
</DIV>
<DIV class="linksite fl" style="float: right;">
<DIV class=adv-header>
<DIV class="adv-title fl"><IMG alt="" src="<?=$conf['thumucroot']?>images/adv-title.gif"> 
</DIV></DIV>
<!--Quang cao 1-->
<?
get_trangchu1($catid);
?>
<!-- Het quang cao 1-->

</DIV>
</DIV>	
<div class="content-left fl">		

<?
echo get_list_focus($cat_id);
if ($pcat_id ==0) {
$result11 = $DB->query("select * from ".$conf['perfix']."catalog where parentid=$cat_id and hienthi!=0");}
else { 
$w = $DB->query("select * from ".$conf['perfix']."catalog where catalogid=$pcat_id and hienthi!=0");
if ($cat11=$DB->fetch_row($w)) 
$cat_id11 = $cat11["catalogid"];
$result11 = $DB->query("select * from ".$conf['perfix']."catalog where (parentid=$cat_id11 and catalogid<>$cat_id) and hienthi!=0");

} 
          while ($row11 = $DB->fetch_row($result11)){
		  $cat_id12 = $row11["catalogid"];
		  $cat_name12 = $row11["catalogname"];
		  $ccat_name12 = mahoa($cat_name12);
		  $linkcat12 = "".$func->seolinkmain($ccat_name12, $cat_id12)."";
?>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			<a class="link-folder" href="<?=$linkcat12?>">.:: <?=$cat_name12?> ::.
</a>
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
	<?
$sqlnews1 ="select * from ".$conf['perfix']."news where isdisplay!=0 and catalogid=$cat_id12 order by newsid DESC LIMIT 0,1";

          $resultnews1=$DB->query ($sqlnews1);
		if ($newscat=$DB->fetch_row($resultnews1)){
                 $news_idcat = $newscat["newsid"];
                 $catidcat= $newscat["catalogid"];
                 $titlemcat = $newscat["title"];
				 $ttitlemcat = mahoa($titlemcat);
				 $linknewsm = "".$func->seolinknews($ttitlemcat, $news_idcat)."";
                 $shortcat = $func->HTML($newscat["short"]);
                 $sourcecat = $func->HTML($newscat["source"]);
                 $adddatecat = $newscat["adddate"];
                 if (!empty($newscat['picture'])) {
                     if ( (!strstr($newscat['picture'],"http://"))){
                           $foldercat = explode("-",$adddatecat);
                           $pathcat = $conf['rooturl']."images/news/".$foldercat[0]."/".$foldercat[1]."/".$foldercat[2]."/icon_";
                           $srccat = $pathcat.$newscat['picture'];
                     } else
                           $srccat = $newscat['picture'];
                     
                 }else $imgcat ="";
		  ?>
	<div class="box-middle1 list-item1 fl" id="ListItem19">
	<div class="list-item1-content fl">	<a href="<?=$linknewsm?>"><img class="fl" style="width:80px; height: 60px; padding: 3px; border: 1px solid #ccc; margin: 3px" src="<?=$srccat?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>	
	<P><a class="link-listitem1-title" href="<?=$linknewsm?>"><?=$titlemcat?></a></P>	
	<P><?=$shortcat?></P></div>

<div class="list-item1-content fl">	
<ul>

<?
$sqlnews12 ="select * from ".$conf['perfix']."news where (isdisplay!=0 and catalogid=$cat_id12) and newsid < $news_idcat order by newsid DESC LIMIT 0,4";
$resultnews12=$DB->query ($sqlnews12);
		while ($newscat12=$DB->fetch_row($resultnews12)){
                 $news_idcat12 = $newscat12["newsid"];
                 $catidcat12= $newscat12["catalogid"];
                 $titlemcat12 = $newscat12["title"];
				 $ttitlemcat12 = mahoa($titlemcat12);
				 $linknewsm12 = "".$func->seolinknews($ttitlemcat12, $news_idcat12)."";
                 $shortcat12 = $func->HTML($newscat["short"]);
                 $sourcecat12 = $func->HTML($newscat["source"]);
                 $adddatecat12 = $newscat12["adddate"];
                 if (!empty($newscat12['picture'])) {
                     if ( (!strstr($newscat12['picture'],"http://"))){
                           $foldercat12 = explode("-",$adddatecat12);
                           $pathcat12 = $conf['rooturl']."images/news/".$foldercat12[0]."/".$foldercat12[1]."/".$foldercat12[2]."/thumb_";
                           $srccat12 = $pathcat12.$newscat12['picture'];
                     } else
                           $srccat12 = $newscat12['picture'];
                     $imgcat = "<a href=\"?cmd=act:news|newsid:{$news_idcat}\" onMouseOver=\"window.status='".$titlemcat."'; return true\"><img src='$srccat' width='100' height='100' align='left' border='0' style='margin:5 5 0 0'></a>";
                 }else $imgcat ="";

?>	
<li style="background-image: url(<?=$conf['thumucroot']?>images/background/blue-square.gif); BACKGROUND-REPEAT: no-repeat; POSITION: relative">
<a class="link-listitem1-othernews" href="<?=$linknewsm12?>"><?=$titlemcat12?></a></li>		
<?
}
?>

</ul>
</div>
</div>
<?
}
?>
<div class="fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/box-bottomleft1.gif" alt=""></div>
		<div class="box-bottomcenter1 fl">&nbsp;</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/box-bottomright1.gif" alt=""></div>
	</div>
</div>

<?
}

?>

<?
get_newsmain1($catid);

get_newsmain2($catid);
?>
<?
require_once("module/toptag.php");
?>
</div>
<?
} else {
?>

<DIV id=content>
<DIV class="content-center fl"><DIV class="khongtontai">Không tồn tại dữ liệu phù hợp</DIV></DIV>





<DIV style="float: left">
<DIV class="linksite1 fl">
<DIV class="toplist-content fl" id=toplist>
<DIV class=baivietmoi>Bài viết mới nhất</DIV>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by newsid DESC LIMIT 0,7";
	   
       $result1 = $DB->query ($sql1) ;
       while ($row1 = $DB->fetch_row($result1)){
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
			  $solanxem=$news1["viewnum"];
			  $newsid1 = $news1["newsid"];
			  $tieude1 = $news1["title"];
			  $short1 = $news1["short"];
			  $ttieude1 = mahoa($tieude1);
			  $linknews1 = "".$func->seolinknews($ttieude1, $newsid1)."";
			  $adddate1 = $news1["adddate"];
			 
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 
				
              }
  ?>


  <LI style="BACKGROUND-IMAGE: url(<?=$conf['thumucroot']?>images/background/gray-square.gif)">
  <A class=link-toplist href="<?=$linknews1?>"><?=$tieude1?></A> 
  
  <?
  }
  ?>
  
  </UL></DIV>
</DIV>
<DIV class="linksite fl" style="float: right;">
<DIV class=adv-header>
<DIV class="adv-title fl"><IMG alt="" src="<?=$conf['thumucroot']?>images/adv-title.gif"> 
</DIV></DIV>
<!--Quang cao 1-->
<?
get_trangchu1($catid);
?>
<!-- Het quang cao 1-->

</DIV>
</DIV>	

		
<div class="content-left fl">		
<?
require_once("module/topmostview.php");
get_newsmain1($catid);
require_once("module/topcomment.php");
?>
</div>
<?

}
;
?>


	