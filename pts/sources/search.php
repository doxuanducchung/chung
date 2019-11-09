<div id="content">	
	<div class="content-center fl" style="border-top: 1px solid #ccc">
<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

if (empty($input["page"])) {
    $page = 0;
}
$ok =0;
$where="";
if (!empty($input["keyword"])) {
    $keyword = $input["keyword"];
    $where= "where ((title like '%$keyword%') or (short like '$keyword'))";
    $news = $DB->query("select * from ".$conf['perfix']."news {$where} and isdisplay!=0 order by newsid DESC ");
    $ok =1;
} elseif (!empty($_POST["title"]) || !empty($_POST["content"]) || !empty($_POST["txtDate"]) ) {
          if (!empty($_POST["title"])) $title =$_POST["title"] ;else $title= "";
              $where .= " where  title like '%$title%' ";
          if (!empty($_POST["content"])) $content =$_POST["content"] ;else $content= "";
              $where .= " and  short like '%$content%' ";
    
          $sql= "select * from ".$conf['perfix']."news {$where} and isdisplay!=0 order by newsid DESC ";
          $ok =1;
} else    $ok =0;
if ($ok==1){
    $sql= "select * from ".$conf['perfix']."news {$where} and isdisplay=1 order by newsid DESC ";
    $searchresult = $DB->query($sql);
    $total =$DB->num_rows($searchresult );

    $msg= "<b>Search results: &quot;<b class='maintitle'>".$keyword."</b>&quot;</b> <i><b class='maintitle'>: ".$total." results.</b></i>";
}
?>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
<SCRIPT language=javascript src="js/Datetime.js" type=text/javascript></SCRIPT>
<?php
if ($ok==0){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <form name="form1" method="post" action="">
  <tr>
    <td colspan="2"><table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td width="11" height="27"><img src="images/h_arrow.gif" width="11" height="26"></td>
        <td background="images/bg_title.gif">&nbsp;<a href="?cmd=act:search"><b class="maintitle">T&#236;m Ki&#7871;m Th&#244;ng Tin</b></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" width="120"><strong>Ti&ecirc;u &#273;&#7873; :</strong></td>
    <td><input type="text" name="title" style="width:220px"></td>
  </tr>
  <tr>
    <td align="right"><strong>N&#7897;i dung : </strong></td>
    <td><textarea name="content" cols="40" rows="5" style="width:220px"></textarea></td>
  </tr>
  <tr>
    <td align="right"><strong>Theo ng&#224;y : </strong></td>
    <td><input type="text" name="txtDate" onClick="javascript:fPopCalendar(txtDate,txtDate)" maxlength="10" size="10">
        <span class="style1">(dd/mm/yy)</span></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value=" T&#236;m Ki&#7871;m " class="button"></td>
  </tr>
  </form>
</table><br><br>




<?php
 } else {
?>

  <?php
      if ($DB->num_rows($searchresult)){
		  $i = 0;
          $stt=1;
		  ?>
		  <DIV class=folder-header style="BORDER-TOP: 0px">
<?=$msg?>
</DIV>
<?
          while ( $row=$DB->fetch_row($searchresult) ){
				  $i ++;
                  $cat_id = $row["catalogid"];
                  $newsid = $row["newsid"];
                  $title = $func->HTML($row["title"]);
				  $title1 = str_replace($keyword,'<span style="background-color:#f1ee05;color:#111">'.$keyword.'</span>',$title);
                  $ttitle = mahoa($title);
				 $linknews = "".$func->seolinknews($ttitle, $newsid)."";
                 $short = $func->HTML($row["short"]);
				 $short = str_replace($keyword,'<span style="background-color:#f1ee05;color:#111">'.$keyword.'</span>',$short);
                 $adddate = $row["adddate"];
				 $postime = $row["timepost"];
				 $adddate = $row["adddate"];
				 $datepost = "<i>(Updated: ".gmdate("d/m/Y, H:i", $postime + 7*3600)." GMT+7).</i>";
                 if (!empty($row['picture'])) {
                     if ( (!strstr($row['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$row['picture'];
                     } else
                           $src = $row['picture'];

				 if ($i % 2 != 0) $bg1 = "#f5f5f7";
                    else $bg1 = "#FFFFFF";
				  
				  
				  
  ?>
  <div class="folder-news" style="BORDER: 0px; background-color: <?=$bg1?>; BORDER-BOTTOM: 1px solid #ccc; BORDER-RIGHT: 1px solid #ccc">
<a href="<?=$linknews?>" target="_blank"><img class="img-subject fl" src="<?=$src?>" alt=""></a>
<p><a class="link-title" href="<?=$linknews?>" target="_blank"><?=$title1?></a>
<br><label class="item-time"><?=$datepost?></label></p>
<p><?=$short?></p><br>

</div>



<?
}

    } 
?>



</div>
		
	<div class="content-left fl">		
		<div class="linksite-box" style="margin-bottom: 5px; padding-top: 0px;">
	<p><a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite2 fr" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a></p>
	<p><a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite2 fr" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a></p>	
</div>

<div class="box-item2">
</div>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Top Most View ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by viewnum DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $newsid11 = $news1["newsid"];
				 $title11 = $news1["title"];
				 $short11 = $news1["short"];
				 $ttitle11 = mahoa($title11);
				 $linknews11 = "".$func->seolinknews($ttitle11,$newsid11)."";
				 $adddate1 = $news1["adddate"];
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $paththumb = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
						   $pathicon = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/icon_";
                           $srcthumb = $paththumb.$news1['picture'];
						   $srcicon = $pathicon.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	<a  
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt=""></a>		<a 
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   
   href="<?=$linknews11?>"><?=$title11?></a></div>
   <?
   }
   ?>
   
	  
	  
</div>

</div>	
	
	
	<div class="folder-bottom listitem-bottom fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-bottomleft.gif" alt=""></div>
		<div class="listitem-bottomcenter fl">&nbsp;</div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-bottomright.gif" alt=""></div>
	</div>
</div>


<?
get_newsmain1($catid);
?>







<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Random Tutorials ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by RAND() DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $newsid11 = $news1["newsid"];
				 $title11 = $news1["title"];
				 $short11 = $news1["short"];
				 $ttitle11 = mahoa($title11);
				 $linknews11 = "".$func->seolinknews($ttitle11,$newsid11)."";
				 $adddate1 = $news1["adddate"];
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $paththumb = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
						   $pathicon = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/icon_";
                           $srcthumb = $paththumb.$news1['picture'];
						   $srcicon = $pathicon.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	<a  
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt=""></a>		<a 
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   
   href="<?=$linknews11?>"><?=$title11?></a></div>
   <?
   }
   ?>
   
	  
	  
</div>

</div>	
	
	
	<div class="folder-bottom listitem-bottom fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-bottomleft.gif" alt=""></div>
		<div class="listitem-bottomcenter fl">&nbsp;</div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-bottomright.gif" alt=""></div>
	</div>
</div>
</div>
<?
}
 else  {
 
 ?>
 
 		  <DIV class=folder-header style="BORDER-TOP: 0px">
<b>No results.</b>
</DIV>

  <div class="folder-news" style="BORDER: 0px; background-color: <?=$bg1?>; BORDER-BOTTOM: 1px solid #ccc; BORDER-RIGHT: 1px solid #ccc">
<TABLE  cellSpacing=0>
										<TBODY>
										<TR>
          <TD valign="middle"><font color="#006666" size="3"><b>- :</b></font></TD>
          
          
          </TR>
											
        <TR>
          <TD valign="middle" height="25px"><b>+ .</b></TD>
          
          
          </TR>
		  <TR>
          <TD valign="middle" height="25px"><b>+ .</b></TD>
          
          
          </TR>
		  <TR>
          <TD valign="middle" height="25px"><b>+ .</b></TD>
          
          
          </TR>
		  <TR>
          <TD valign="middle" height="25px"><b>+ .</b></TD>
          
          
          </TR>
		  </TBODY></TABLE>

</div>





</div>







		
	<div class="content-left fl">		
		<div class="linksite-box" style="margin-bottom: 5px; padding-top: 0px;">
	<p><a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite2 fr" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a></p>
	<p><a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite fl" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a>
	<a href="#" target="_blank"><img class="img-linksite2 fr" src="<?=$conf['thumucroot']?>images/link.jpg" alt=""></a></p>	
</div>

<div class="box-item2">
</div>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Top Most View ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by viewnum DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $newsid11 = $news1["newsid"];
				 $title11 = $news1["title"];
				 $short11 = $news1["short"];
				 $ttitle11 = mahoa($title11);
				 $linknews11 = "".$func->seolinknews($ttitle11,$newsid11)."";
				 $adddate1 = $news1["adddate"];
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $paththumb = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
						   $pathicon = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/icon_";
                           $srcthumb = $paththumb.$news1['picture'];
						   $srcicon = $pathicon.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	<a  
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt=""></a>		<a 
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   
   href="<?=$linknews11?>"><?=$title11?></a></div>
   <?
   }
   ?>
   
	  
	  
</div>

</div>	
	
	
	<div class="folder-bottom listitem-bottom fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-bottomleft.gif" alt=""></div>
		<div class="listitem-bottomcenter fl">&nbsp;</div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-bottomright.gif" alt=""></div>
	</div>
</div>


<?
get_newsmain1($catid);
?>







<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Random Tutorials ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by RAND() DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $newsid11 = $news1["newsid"];
				 $title11 = $news1["title"];
				 $short11 = $news1["short"];
				 $ttitle11 = mahoa($title11);
				 $linknews11 = "".$func->seolinknews($ttitle11,$newsid11)."";
				 $adddate1 = $news1["adddate"];
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $paththumb = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
						   $pathicon = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/icon_";
                           $srcthumb = $paththumb.$news1['picture'];
						   $srcicon = $pathicon.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	<a  
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt=""></a>		<a 
   onmouseover='showtip("<h3><?=$title11?></h3><img src=\"<?=$srcthumb?>\"><div><?=$short11?></div>");' 
  onmouseout=hidetip();
   
   href="<?=$linknews11?>"><?=$title11?></a></div>
   <?
   }
   ?>
   
	  
	  
</div>

</div>	
	
	
	<div class="folder-bottom listitem-bottom fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-bottomleft.gif" alt=""></div>
		<div class="listitem-bottomcenter fl">&nbsp;</div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-bottomright.gif" alt=""></div>
	</div>
</div>
</div>
 
 
<? 
 
 }
  ?>
  
<?php }?>