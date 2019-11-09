<?php
if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
$page=1;
$num=2; // So luong ban tin khac
if (!empty($input["cat"]) && is_numeric($input["cat"]) ){
    $cat_id=$input["cat"];
    $query="select * from ".$conf['perfix']."catalog where catalogid=$cat_id and hienthi!=0 order by cat_order asc";
    if (!empty($conf['cat_short'])) $page=intval($conf['cat_short']);
    else $page=10;

    if (!empty($conf['cat_link'])) $num=intval($conf['cat_link']);
    else $num=10;
}else{
    $cat_id="";
    $query="select * from ".$conf['perfix']."catalog where (parentid=0 and type=0) and hienthi!=0 order by cat_order asc";
}
$catresult = $DB->query($query);
?>

<DIV id=topnews>
<DIV class="top4 fl" id=top4>
<DIV class="hotnews-top fl">
<DIV class=fl><IMG class=alt alt="" src="<?=$conf['thumucroot']?>images/hotnews-topleft.gif"></DIV>
<DIV class="hotnews-topright fl"></DIV></DIV>
<DIV class="hotnews-content fl">

  <?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news  where isdisplay!=0 order by newsid DESC LIMIT 0,1";
	   
       $result1 = $DB->query ($sql1) ;
       while ($row1 = $DB->fetch_row($result1)){
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
			  $newsid1 = $news1["newsid"];
			  $solanxem=$news1["viewnum"];
			  $tieude1 = $news1["title"];
			  $ttieude1 = mahoa($tieude1);
			  $linknews1 = "".$func->seolinknews($ttieude1,$newsid1)."";
			  $adddate1 = $news1["adddate"];
			  $datepost1 = "Updated: ".gmdate("d/m/Y, H:i A", $news1['timepost'] + 7*3600)."";
		         if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/";
                           $src = $path.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     
                 }
				
              }
  ?>



<DIV class="hotnews-detail fl">
<P><label class="itembaivietmoi">.:: Bài viết Mới cập nhật ::.</label></P>
<P><A 
href="<?=$linknews1?>"><IMG 
class="img-topnews fl" alt="" src="<?=$src?>" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></A><A class=link-topnews href="<?=$linknews1?>"><?=$tieude1?></A></P>
<P><label class="item-time"><font color="#E94C37"><i>( <?=$datepost1?> )</i></font></label></P>
<P><?=$news1["short"]?></P></DIV>




</DIV>
<DIV class="hotnews-bottom fl">
<DIV class="hbl he4 fl"></DIV>
<DIV class="hotnews-bottomright fl"></DIV></DIV>
<DIV class="t3 fl">

<IMG src="http://8x77.com.vn/pts/images/banner/1256419316.gif" style="padding: 3px; border: 1px solid #ccc; width: 485px; height: 85px">

</DIV>
</DIV>
<DIV class="toplist fl">
<DIV class="toplist-date fl">
<DIV class="hotnews-date fl txtr" id=topnewsdate></DIV>
<DIV class=fl><IMG alt="" src="<?=$conf['thumucroot']?>images/date-arrow.gif"></DIV>
</DIV>
<DIV class="toplist-top fl">
<DIV class=fl><IMG alt="" src="<?=$conf['thumucroot']?>images/toplist-topleft.gif"></DIV>
<DIV class="toplist-topcenter fl ftz1"></DIV>
<DIV class=fl><IMG alt="" src="<?=$conf['thumucroot']?>images/toplist-topright.gif"></DIV></DIV>
<DIV class="toplist-middle fl">
<DIV class="toplist-left fl">
<DIV class="toplist-content fl" id=toplist>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where (isdisplay!=0 and newsid<$newsid)order by newsid DESC LIMIT 0,7";
	   
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
  }
  ?>
  
  </UL></DIV></DIV>
<DIV class="toplist-right fl ftz1"></DIV></DIV>
<DIV class="toplist-bottom fl">
<DIV class=fl><IMG alt="" src="<?=$conf['thumucroot']?>images/toplist-bottomleft.gif"></DIV>
<DIV class="toplist-bottomcenter fl ftz1"></DIV>
<DIV class=fl><IMG alt="" 
src="<?=$conf['thumucroot']?>images/toplist-bottomright.gif"></DIV></DIV></DIV>
<DIV class="linksite fl">
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
<DIV id=content>
<DIV class="content-center fl">



<!-- Het Tin Noi Bat -->

<?php
if ($DB->num_rows($catresult)){
    while ($cat =$DB->fetch_row($catresult) ){
           $cat_id =$cat["catalogid"];
           $pcat_id =$cat["parentid"];
           $cat_name =$cat["catalogname"];
		   $ccat_name = mahoa($cat_name);
		   $linkcat = "".$func->seolinkmain($ccat_name, $cat_id)."";
?>



<DIV class=folder>
<DIV class=folder-title>
<DIV class="folder-activeleft fl"></DIV>
<DIV class="folder-active fl"><A class=link-folder href="<?=$linkcat?>"><?=$cat_name?></A></DIV>
<DIV class="folder-activeright fl"></DIV>
<DIV class="subfolder fl">
<?
$resultSCat1 = $DB->query("select * from ".$conf['perfix']."catalog where parentid=$cat_id and hienthi!=0");
          while ($rowSCat1 = $DB->fetch_row($resultSCat1)){
                 $cat_id1 =$rowSCat1["catalogid"];
				$cat_name1 =$rowSCat1["catalogname"];
				$ccat_name1 = mahoa($cat_name1);
				$linkcat1 = "".$func->seolinkmain($ccat_name1, $cat_id1)."";
				?>
&nbsp;<A class=link-subfolder 
href="<?=$linkcat1?>"><?=$cat_name1?></A>&nbsp;|
<?
}
?>

				
				</DIV>
<DIV class="folder-titleright fr"></DIV></DIV>
<DIV class=folder-content id=tdHomeFolder109>
<?
//Bat dau tin tuc
?>

<?php
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
          $nav = "<div class=\"pagination\">".$func->paginate($totals_news, $n,$p,"p")."</div>";
          if (!empty ($subcat)){
              $sqlnews ="select * from ".$conf['perfix']."news where isdisplay!=0 and (catalogid=$cat_id or catalogid in ('".$subcat."')) order by newsid DESC LIMIT $start,$page";
          }else
              $sqlnews ="select * from ".$conf['perfix']."news where isdisplay!=0 and catalogid=$cat_id order by newsid DESC LIMIT $start,$page";

          $result=$DB->query ($sqlnews);
          while ($news=$DB->fetch_row($result)){
                 $news_id = $news["newsid"];
                 $catid= $news["catalogid"];
                 $titlem = $news["title"];
				 $ttitlem = mahoa($titlem);
				 $linknewsm = "".$func->seolinknews($ttitlem,$news_id)."";
                 $short = $func->HTML($news["short"]);
                 $source = $func->HTML($news["source"]);
                 $adddate = $news["adddate"];
                 if (!empty($news['picture'])) {
                     if ( (!strstr($news['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$news['picture'];
                     } else
                           $src = $news['picture'];
                     }
  ?>

<DIV class="folder-topnews2 fl"><A 
href="<?=$linknewsm?>"><IMG 
class="img-subject fl" alt="" src="<?=$src?>" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></A>
<P><A class=link-title href="<?=$linknewsm?>"><?=$titlem?>&nbsp;</A></P>
<P><?=$short?></P></DIV>
<?
}

if (!empty($start))
             $begin=$page+$start;
         else
             $begin=$page ;
         if (!empty ($subcat)){
             $queryOrder = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and  (catalogid=$cat_id or catalogid in ('".$subcat."'))  order by newsid DESC LIMIT $begin,5";
         }else
             $queryOrder = "SELECT * FROM ".$conf['perfix']."news WHERE isdisplay!=0 and  catalogid=$cat_id order by newsid DESC LIMIT $begin,5";
         $news_order = $DB->query ($queryOrder);
         if ($DB->num_rows($news_order)){
 ?>
 
 <DIV class="folder-othernews2 fl" style="PADDING-TOP: 5px">
<DIV class=fl>
<UL>
 <?
             while ($row =$DB->fetch_row($news_order)){
					$newsida = $row["newsid"];
					$titlea = $row["title"];
					$shorta = $row["short"];
					$ttitlea = mahoa($titlea);
					$linknewsa = "".$func->seolinknews($ttitlea,$newsida);
                    $date = "(".$func->makedate($row["adddate"]).")";
					$adddate = $row["adddate"];
					if (!empty($row['picture'])) {
                     if ( (!strstr($row['picture'],"http://"))){
                           $folder = explode("-",$adddate);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$row['picture'];
                     } else
                           $src = $row['picture'];
                     }
  ?>


  
  <LI style="background-image: url(<?=$conf['thumucroot']?>images/background/blue-square.gif); BACKGROUND-REPEAT: no-repeat; POSITION: relative">
  <A class=link-othernews href="<?=$linknewsa?>"><?=$titlea?></A> </LI>
  
  <?
  }
  ?>
  
</UL></DIV></DIV>
<?
}
?>
<DIV class="rss fr"><A class=link-rss 
href="<?=$linkcat?>">View All... <IMG class=img-rss 
alt="" src="<?=$conf['thumucroot']?>images/rss.gif"></A> </DIV></DIV>
<DIV class=folder-bottom>
<DIV class=fl><IMG alt="" src="<?=$conf['thumucroot']?>images/folder-bottomleft.gif"></DIV>
<DIV class="folder-bottomcenter fl"></DIV>
<DIV class=fr><IMG alt="" 
src="<?=$conf['thumucroot']?>images/folder-bottomright.gif"></DIV></DIV>
<DIV class=folder-bottom></DIV></DIV>

<?
}
}
?>


<?
//Bat dau cot thu 2
?>

<DIV class=folder>
</DIV></DIV>
<DIV class="content-left fl">
<?
require_once("module/topmostview.php");
?>
<?
get_trangchu3($catid);
?>
<?
require_once("module/topcomment.php");
?>
<?
get_trangchu4($catid);
?>
<?
require_once("module/toptag.php");
?>
</DIV>
