<?php
if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
if (!empty($conf['topicperpage'])) $page=intval($conf['topicperpage']);
    else $page=2;
if (!empty($conf['articeltopicperpage'])) $pagearticel=intval($conf['articeltopicperpage']);
    else $pagearticel=2;
if (!empty($input["eventid"]) && is_numeric($input["eventid"]) ){
    $eventid = $input["eventid"];
    $titevent = "Chi Ti&#7871;t S&#7921; Ki&#7879;n";
    $upview = $DB->query("update ".$conf['perfix']."events set eventview=eventview+1 where eventid=$eventid");
    $query = $DB->query("select * from ".$conf['perfix']."events where eventid=$eventid and active!=0");
}else{
    $numl = 2; 
}
?>
<div id="content">	
<div class="content-center fl">
<?
	 if (!empty($input["eventid"])){
	 $sqle = $DB->query("select * from ".$conf['perfix']."events where eventid=$eventid and active!=0");
	 if ($event = $DB->fetch_row($sqle)){
            $eventid = $event["eventid"];
            $eventt = $event["eventtitle"];
			$eeventt = mahoa($eventt);
			$eventc = $func->HTML($event["eventdes"]);
            $eventd = "".gmdate("d/m/Y, h:i A",$event["eventpost"] + 7*3600)."";
			if (!empty($event['eventpic'])) {
                if ( (!strstr($event['eventpic'],"http://"))){
                      $folder1 = gmdate("Y",$event["eventpost"] + 7*3600);
                      $folder2 = gmdate("m",$event["eventpost"] + 7*3600);
                      $path = $conf['rooturl']."images/event/".$folder1."/".$folder2."/";
                      $src = $path.$event['eventpic'];
                } else
                      $src = $event['eventpic'];
            } else $img ="";
	 

?>


	<div class="thumuc">
	<A class=link-home href="<?
		if ($conf['seo_link'] =='yes') echo '',$conf['thumucroot'],'topics/';
		if ($conf['seo_link'] =='no') echo '?cmd=act:event';
	?>">Chủ đề Bài viết&nbsp;&raquo;&nbsp;</A><P><?=$eventt?></P>
	</div>
<DIV class=content style="WIDTH: 488px; MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; BORDER-TOP: 0px solid #ccc; BORDER-BOTTOM: 1px solid #ccc; BORDER-RIGHT: 1px solid #ccc; BORDER-LEFT: 1px solid #ccc; PADDING-TOP: 5px; PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px">
<P class=Lead><CENTER><img src="<?=$src?>" style="width: 248px; height: 186px;  margin-bottom: 0px; border: 1px solid #ccc; padding: 5px" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></CENTER>
</P>
<P class=Normal>
<?=$eventc?></P>




</DIV>
<?
$sqlnews = $DB->query("select * from ".$conf['perfix']."news where isdisplay!=0 and eventid=$eventid order by newsid DESC");

	 $totals_news = $DB->num_rows($sqlnews);
	 if ($totals_news > 0) {
?>
<div class="folder-news" style="BACKGROUND: #DEF3FF; BORDER-TOP: 0px; PADDING-TOP: 2px; PADDING-BOTTOM: 2px"><font color="#006000" size="3"><b>.: The tutorials in this topic :.<b></font></div>

<?
	 }
     $n = $pagearticel;
     $num_pages = ceil($totals_news/$n) ;
     if ($p > $num_pages) $p=$num_pages;
     if ($p < 1 ) $p=1;
     $start = ($p-1) * $n;
     $nav = "<CENTER><DIV class=page_navi>".$func->paginatetopic($totals_news,$n,$p,"p",$eeventt, $eventid)."</DIV></CENTER>";
	 $sqln = $DB->query("select * from ".$conf['perfix']."news where isdisplay!=0 and eventid=$eventid order by newsid DESC LIMIT $start, $pagearticel");
     if ($DB->num_rows($sqln)){
	 $i = 0;
	 while ($rown = $DB->fetch_row($sqln)){
				 $i ++;
                 $newsid = $rown["newsid"];
                 $newst  = $rown["title"];
				 $nnewst = mahoa($newst);
				 $linknews = $func->seolinknews($nnewst, $newsid);
				 $newss = $rown["short"];
                 $newsv  = $rown["viewnum"];
				 $adddate = $rown["adddate"];
				 $postime = $rown["timepost"];
				 $datepost = "C&#7853;p nh&#7853;t ng&#224;y: ".gmdate("d/m/Y, H:i", $postime + 7*3600)."";
                 if (!empty($rown['picture'])) {
                     if ( (!strstr($rown['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$rown['picture'];
                     } else
                           $src = $rown['picture'];
                 }else $img ="";
				 if ($i % 2 != 0) $bg1 = "#f5f5f7";
                    else $bg1 = "#FFFFFF";

						
					?>
					
					<div class="folder-news" style="BORDER-TOP: 0px; background-color: <?=$bg1?>;">
<a  href="<?=$linknews?>"><img class="img-subject fl" src="<?=$src?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>
<p><a class="link-title" href="<?=$linknews?>"><?=$newst;?></a>
<br><label class="item-time"><font color="#E94C37"><i>( <?=$datepost?> )</i></font></label></p>
<p style="font-weight: normal"><?=$newss?></p>

</div>

<?
						
	 }
	 }
?>

<?
}
//Het  If Event
//Phan HTML
?>
<?
if ($totals_news > $pagearticel) {
?>
<div class="folder-newsgachngang">
	<div class="continuepage">
		<center>
		<?php
			print $nav;
		?>
		</center>
	</div>
</div>
<?
}
?>
</div>

<DIV style="float: left">
<DIV class="linksite1 fl">
<DIV class="toplist-content fl" id=toplist>
<DIV class=baivietmoi>Bài viết mới nhất</DIV>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by newsid DESC LIMIT 0,9";
	   
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
<div class="box-item2">
</div>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Top Topics ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."events where active!=0 order by eventview DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $eventid = $row1["eventid"];
              $sql1 ="select * from ".$conf['perfix']."events where eventid=$eventid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $eventid11 = $news1["eventid"];
				 $eventtitle11 = $news1["eventtitle"];
				 $eventdes11 = $news1["eventdes"];
				 $eeventtitle11 = mahoa($eventtitle11);
				 $linktipic11 = "".$func->seolinktopic($eeventtitle11,$eventid11)."";
                 if (!empty($news1['eventpic'])) {
					 if ( (!strstr($news1['eventpic'],"http://"))){
                      $folder1 = gmdate("Y",$news1["eventpost"] + 7*3600);
                      $folder2 = gmdate("m",$news1["eventpost"] + 7*3600);
                      $pathicon11 = $conf['rooturl']."images/event/".$folder1."/".$folder2."/icon_";
					  $paththumb11 = $conf['rooturl']."images/event/".$folder1."/".$folder2."/thumb_";
                      $srcicon11 = $pathicon11.$news1['eventpic'];
					  $srcthumb11 = $paththumb11.$news1['eventpic'];
                } else
                      $src = $news1['eventpic'];
            } else $img ="";
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	
<a href="<?=$linktipic11?>"><img src="<?=$srcicon11?>" class="fl" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linktipic11?>"><?=$eventtitle11?></a></div>
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








<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Random tutorials ::.
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
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	
<a href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linknews11?>"><?=$title11?></a></div>
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
 //Bat dau TOpic lon
 } else {
?>
<div class="thumuc">
	<A class=link-home href="<?
		if ($conf['seo_link'] =='yes') echo '',$conf['thumucroot'],'topics/';
		if ($conf['seo_link'] =='no') echo '?cmd=act:event';
	?>">Chủ đề Bài viết</A>
	</div>
<? 
$sqlevent = $DB->query("select * from ".$conf['perfix']."events where active!=0 order by eventid DESC");

	 $totals_event = $DB->num_rows($sqlevent);
     $n = $page;
     $num_pages = ceil($totals_event/$n) ;
     if ($p > $num_pages) $p=$num_pages;
     if ($p < 1 ) $p=1;
     $start = ($p-1) * $n;
     $nav = "<CENTER><DIV class=page_navi>".$func->paginatetopicl($totals_event,$n,$p,"p")."</DIV></CENTER>";
	 $sqln = $DB->query("select * from ".$conf['perfix']."events where active!=0 order by eventid DESC LIMIT $start, $page");
     if ($DB->num_rows($sqln)){
	 $i = 0;
	 while ($rown = $DB->fetch_row($sqln)){
				 $i ++;
                 $eventid = $rown["eventid"];
                 $eventt  = $rown["eventtitle"];
				 $eeventt = mahoa($eventt);
				 $linkevent = $func->seolinktopic($eeventt, $eventid);
				 $events = $rown["eventdes"];
				 $eventd = "Cập nhật: ".gmdate("d/m/Y, h:i A",$event["eventpost"] + 7*3600)."";
                 if (!empty($rown['eventpic'])) {
                if ( (!strstr($rown['eventpic'],"http://"))){
                      $folder1 = gmdate("Y",$rown["eventpost"] + 7*3600);
                      $folder2 = gmdate("m",$rown["eventpost"] + 7*3600);
                      $path = $conf['rooturl']."images/event/".$folder1."/".$folder2."/thumb_";
                      $src = $path.$rown['eventpic'];
                } else
                      $src = $rown['eventpic'];
            } else $img ="";
				 if ($i % 2 != 0) $bg1 = "#f5f5f7";
                    else $bg1 = "#FFFFFF";

						
					?>					
<div class="folder-news" style="BORDER-TOP: 0px; background-color: <?=$bg1?>;">
<a  href="<?=$linkevent?>"><img class="img-subject fl" src="<?=$src?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>
<p><a class="link-title" href="<?=$linkevent?>"><?=$eventt;?></a>
<br><label class="item-time"><font color="#E94C37"><i>( <?=$eventd?> )</i></font></label></p>
<p><?=$events?></p>

</div>

<?
						
	 }
}
?>
<?
if ($totals_event > $page) {
?>
<div class="folder-newsgachngang">
	<div class="continuepage">
		<center>
		<?php
			print $nav;
		?>
		</center>
	</div>
</div>
<?
}
?>
</div>

<DIV style="float: left">
<DIV class="linksite1 fl">
<DIV class="toplist-content fl" id=toplist>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by newsid DESC LIMIT 0,9";
	   
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
<div class="box-item2">
</div>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Top Topics ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."events where active!=0 order by eventview DESC LIMIT 0,9";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $eventid = $row1["eventid"];
              $sql1 ="select * from ".$conf['perfix']."events where eventid=$eventid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $eventid11 = $news1["eventid"];
				 $eventtitle11 = $news1["eventtitle"];
				 $eventdes11 = $news1["eventdes"];
				 $eeventtitle11 = mahoa($eventtitle11);
				 $linktipic11 = "".$func->seolinktopic($eeventtitle11,$eventid11)."";
                 if (!empty($news1['eventpic'])) {
					 if ( (!strstr($news1['eventpic'],"http://"))){
                      $folder1 = gmdate("Y",$news1["eventpost"] + 7*3600);
                      $folder2 = gmdate("m",$news1["eventpost"] + 7*3600);
                      $pathicon11 = $conf['rooturl']."images/event/".$folder1."/".$folder2."/icon_";
					  $paththumb11 = $conf['rooturl']."images/event/".$folder1."/".$folder2."/thumb_";
                      $srcicon11 = $pathicon11.$news1['eventpic'];
					  $srcthumb11 = $paththumb11.$news1['eventpic'];
                } else
                      $src = $news1['eventpic'];
            } else $img ="";
                 }
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
?>
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	
<a href="<?=$linktipic11?>"><img src="<?=$srcicon11?>" class="fl" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linktipic11?>"><?=$eventtitle11?></a></div>
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








<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Bài viết ngẫu nhiên ::.
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
 
<div class="list-item5-content fl" style="BORDER-TOP: 1px solid <?=$bg1?>; BORDER-BOTTOM: 1px solid <?=$bg2?>; BACKGROUND: <?=$bg3?>">	
<a href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fl" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linknews11?>"><?=$title11?></a></div>
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
  
