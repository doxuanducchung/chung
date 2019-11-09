<?
if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
if (!empty($input["cat"]) && is_numeric($input["cat"]) ){
    $cat_id=$input["cat"];
    $query="select * from ".$conf['perfix']."catalog where catalogid=$cat_id and hienthi!=0 order by cat_order asc";
    if (!empty($conf['articelperpage'])) $page=intval($conf['articelperpage']);
    else $page=2;

    if (!empty($conf['cat_link'])) $num=intval($conf['cat_link']);
    else $num=2;
}else{
    $cat_id="";
    $query="select * from ".$conf['perfix']."catalog where (parentid=0 and type=0) and hienthi!=0 order by cat_order asc";
}
$catresult = $DB->query($query);
$result=$DB->query ("select * from ".$conf['perfix']."catalog where catalogid=$cat_id and hienthi!=0");
if ($DB->num_rows($result)) {
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
?>
	<div id="content">
	<div class="content-center fl">
	<div class="thumuc">
	<?=$cat_link?>
	</div>
<?
if ($DB->num_rows($catresult)){
    while ($cat =$DB->fetch_row($catresult) ){
           $cat_id =$cat["catalogid"];
           $pcat_id =$cat["parentid"];
           $cat_name =$cat["catalogname"];
		   $ccat_name = mahoa($cat_name);
		   $linkmaincat = "".$func->seolinkmain($ccat_name, $cat_id)."";

          $subcat = "";
          $resultSCat = $DB->query("select * from ".$conf['perfix']."catalog where parentid=$cat_id and hienthi!=0");
          while ($rowSCat = $DB->fetch_row($resultSCat)){
                 $subcat.= $rowSCat["catalogid"].",";
          }
          if (!empty ($subcat)){
              $subcat = substr($subcat,0,-1);
              $subcat = str_replace(",","','",$subcat);
              $query = $DB->query("select * from ".$conf['perfix']."news where isdisplay!=0 and (catalogid=$cat_id or catalogid in ('".$subcat."')) ");
          }else
              $query = $DB->query("select * from ".$conf['perfix']."news where isdisplay!=0 and catalogid=$cat_id");

          $totals_news = $DB->num_rows($query);
          $n=$page;
          $num_pages = ceil($totals_news/$n) ;
          if ($p > $num_pages) $p=$num_pages;
          if ($p < 1 ) $p=1;
          $start = ($p-1) * $n ;
          $nav = "<CENTER><DIV class=page_navi>".$func->paginate($totals_news, $n,$p,"p",$ccat_name,$cat_id)."</DIV></CENTER>";
          if (!empty ($subcat)){
              $sqlnews ="select * from ".$conf['perfix']."news where isdisplay!=0 and (catalogid=$cat_id or catalogid in ('".$subcat."')) order by newsid DESC LIMIT $start,$page";
          }else
              $sqlnews ="select * from ".$conf['perfix']."news where isdisplay!=0 and catalogid=$cat_id order by newsid DESC LIMIT $start,$page";
			$i =0;

          $result=$DB->query ($sqlnews);
          while ($news=$DB->fetch_row($result)){
				 $i ++;
                 $news_id = $news["newsid"];
                 $catid= $news["catalogid"];
                 $titlem = $news["title"];
				 $ttitlem = mahoa($titlem);
				 $linknewsm = "".$func->seolinknews($ttitlem, $news_id)."";
                 $short = $func->HTML($news["short"]);
                 $source = $func->HTML($news["source"]);
                 $adddate = $news["adddate"];
				 $postime = $news["timepost"];
				 $datepost = "Updated: ".gmdate("d/m/Y, H:i", $postime + 7*3600)."";
                 if (!empty($news['picture'])) {
                     if ( (!strstr($news['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$news['picture'];
                     } else
                           $src = $news['picture'];
                     $img = "<a href=\"?cmd=act:news|newsid:{$news_id}\" onMouseOver=\"window.status='".$titlem."'; return true\"><img src='$src' width='100' height='100' align='left' border='0' style='margin:5 5 0 0'></a>";
                 }else $img ="";
				 if ($i % 2 != 0) $bg1 = "#f5f5f7";
                    else $bg1 = "#FFFFFF";
  ?>

<div class="folder-news" style="BORDER-top: 0px; background-color: <?=$bg1?>;">
<a href="<?=$linknewsm?>"><img class="img-subject fl" src="<?=$src?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>
<p><a class="link-title" href="<?=$linknewsm?>"><?=$titlem?></a>
<br><label class="item-time"><font color="#E94C37"><i>( Updated: <?=$datepost?> )</i></font></label></p>
<p><?=$short?></p>

</div>



<?
}

    } 
?>
<?
if (!empty($input["cat"]) && ($totals_news > $page)) {
?>
<div class="folder-newsgachngang">
	<div class="continuepage">
		<center>
		<?
        print $nav ;
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
                     $imgcat = "<a href=\"?cmd=act:news|newsid:{$news_idcat}\" onMouseOver=\"window.status='".$titlemcat."'; return true\"><img src='$srccat' width='100' height='100' align='left' border='0' style='margin:5 5 0 0'></a>";
                 }else $imgcat ="";
		  ?>
	<div class="box-middle1 list-item1 fl" id="ListItem19">
	<div class="list-item1-content fl">	<a href="<?=$linknewsm?>"><img class="fl" style="width:80px; height: 60px; padding: 3px; border: 1px solid #ccc; margin: 3px" src="<?=$srccat?>" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>	
	<p><a class="link-listitem1-title" href="<?=$linknewsm?>"><?=$titlemcat?></a></p>	
	<p><?=$shortcat?></div>

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
get_newsmain1($catid);

}
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


?>

		
