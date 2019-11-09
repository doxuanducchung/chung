<DIV id=content>
<DIV class="content-center fl">	<div class="thumuc">
	Website Sitemap
	</div>
	<div class="folder-news" style="BORDER-top: 0px; padding-left: 30px; width: 463px">
	<a href="<?=$conf['rooturl']?>" class=link-sitemapcat><img src="<?=$conf['rooturl']?>images/background/gray-square.gif" border=0>&nbsp;Trang chủ</a><br>
<?
					$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE ((type=0 and parentid =0) and hienthi!=0) order by cat_order asc");
								while ($cq1=$DB->fetch_row($q1)){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								
						<a href="<?=$link1?>" class=link-sitemapcat><img src="<?=$conf['rooturl']?>images/background/gray-square.gif" border=0>&nbsp;<?=$ccatalogname1?></a><br>
						<?
						$q2 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE (parentid = $ccatalogid1 and hienthi!=0) order by cat_order asc");
						if ($DB->num_rows($q2)){
								while ($cq2=$DB->fetch_row($q2)){
								$ccatalogid2 = $cq2["catalogid"];
								$ccatalogname2 = $cq2["catalogname"];
								$cccatalogname2 = mahoa($ccatalogname2);
								$link2 = $func->seolinkmain($cccatalogname2, $ccatalogid2);
								?>
									<a href="<?=$link2?>" class=link-sitemapcatsub>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;<?=$ccatalogname2?></a><br>
								<?
			   					}
						}
						?>
							
<?
}
?>
	<a href="<?=$conf['rooturl']?>sitemap.html" class=link-sitemapcat><img src="<?=$conf['rooturl']?>images/background/gray-square.gif" border=0>&nbsp;Sitemap Website</a><br>
	<a class=link-sitemapcat><img src="<?=$conf['rooturl']?>images/background/gray-square.gif" border=0>&nbsp;Tag Bài viết</a><br>
	<?php
	$sql1 = "select * from ".$conf['perfix']."news where (isdisplay!=0 and tag!='') order by RAND()";
	$result1 = $DB->query ($sql1) ;
	$row = 0;

	$arraytag = array();
	
	while ($row1 = $DB->fetch_row($result1)){
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
						$tag = $news1["tag"];
						$tn = explode(",",$tag);
						if (!empty($tn[0])) {
							$tn0 = mahoa($tn[0]);
							$arraytag["$tn[0]"] = "$tn0";
						};
						if (!empty($tn[1])) {
							$tn1 = mahoa($tn[1]);
							$arraytag["$tn[1]"] = "$tn1";
						};
						if (!empty($tn[2])) {
							$tn2 = mahoa($tn[2]);
							$arraytag["$tn[2]"] = "$tn2";
						};
						}
						}
						$arraytagresult = array_unique($arraytag);
						$i = 0;
						while($elementtag = each($arraytagresult)) {
						$i ++;
						if ($i<=$conf['tagnum']) {						
	?>
	<a href="<?=$func->seolinktag(mahoa($elementtag["key"]));?>" class=link-sitemaptag><font size="<? echo rand(3,6);?>"><?=$elementtag["key"]?>(<?=$func->totalnewstag($elementtag["value"])?>)</font></a>
	<?
	}
	}
	?>
	
	
</div>
	
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
require_once("module/topmostview.php");
get_newsmain1($catid);
require_once("module/topcomment.php");
?>
</div>