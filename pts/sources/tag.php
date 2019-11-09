<?
if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
if (!empty($input["tag"]) && is_string($input["tag"]) ){
    $tag = $input["tag"];
	if (!empty($conf['articeltagperpage'])) $page=intval($conf['articeltagperpage']);
    else $page=2;
?>
<div id="content">	
<div class="content-center fl">
<div class="thumuc">Bài viết theo Tag :<P><?=$tag?></P></div>
<?
	$sqlnews = $DB->query("select * from ".$conf['perfix']."news where (isdisplay!=0 and ((tag1 ='$tag') or (tag2 ='$tag') or (tag3 = '$tag'))) order by newsid DESC");
	 $totals_news = $DB->num_rows($sqlnews);
     $n = $page;
     $num_pages = ceil($totals_news/$n) ;
     if ($p > $num_pages) $p=$num_pages;
     if ($p < 1 ) $p=1;
     $start = ($p-1) * $n;
     $nav = "<CENTER><DIV class=page_navi>".$func->paginatetag($totals_news,$n,$p,"p",$tag)."</DIV></CENTER>";
	 $sqln = $DB->query("select * from ".$conf['perfix']."news where (isdisplay!=0 and ((tag1 ='$tag') or (tag2 ='$tag') or (tag3 = '$tag'))) order by newsid DESC LIMIT $start, $page");
     if ($DB->num_rows($sqln)){
		 while ($rown = $DB->fetch_row($sqln)){
                 $newsid = $rown["newsid"];
                 $title  = $rown["title"];
				 $ttitle = mahoa($title);
				 $linknews = $func->seolinknews($ttitle, $newsid);
				 $t = $rown["tag"];
                 $tn = explode(",",$t);
				 $short = $func->HTML($rown["short"]);
				 $adddate = $rown["adddate"];
				 $postime = $rown["timepost"];
				 $datepost = "Cập nhật: ".gmdate("d/m/Y, H:i", $postime + 7*3600)."";
                 if (!empty($rown['picture'])) {
                     if ( (!strstr($rown['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$rown['picture'];
                     } else
                           $src = $rown['picture'];
                 }else $img ="";
					
					?>
					
<div class="folder-news" style="BORDER-TOP: 0px; PADDING-RIGHT: 5px">
<a  href="<?=$linknews?>"><img class="img-subject fl" src="<?=$src?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>
<p><a class=link-foldernewstopic href="<?=$linknews?>"><?=$title;?></a>
<br><label class="item-time"><font color="#E94C37"><i>( <?=$datepost?> )</i></font></label></p>
<p><?=$short?></p>
</div>
<?
}
}
?>
<?
if ($totals_news > $page) {
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
<DIV class="adv-title fl"><IMG alt="" src="<?=$conf['thumucroot']?>images/adv-title.gif"> </DIV>
</DIV>
<!--Quang cao 1-->
<?
get_trangchu1($catid);
?>
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
 $sql1 = "select * from ".$conf['perfix']."events where active!=0 order by eventview DESC LIMIT 0,7";
	$result1 = $DB->query ($sql1) ;
	$row = 0;


	
	while ($row1 = $DB->fetch_row($result1)){
			  $row ++;
              $newsid = $row1["eventid"];
              $sql1 ="select * from ".$conf['perfix']."events where eventid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
                 $newsid11 = $news1["eventid"];
				 $eventtitle11 = $news1["eventtitle"];
				 $eventdes11 = $news1["eventdes"];
				 $eeventtitle11 = mahoa($eventtitle11);
				 $linktopic11 = "".$func->seolinktopic($eeventtitle11,$newsid11)."";
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
<a href="<?=$linktopic11?>"><img src="<?=$srcicon11?>" class="fr" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linktopic11?>"><?=$eventtitle11?></a></div>
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
  
