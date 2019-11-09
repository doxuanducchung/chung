<?
if ($conf['display_topcommentbox'] == 'yes') {
?>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Top Comment ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
<?php
 $sql1 = "select * from ".$conf['perfix']."news where (isdisplay!=0 and commentnum!=0) order by commentnum DESC LIMIT 0,5";
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
				 $commentnum = $news1["commentnum"];
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
<a href="<?=$linknews11?>"><img src="<?=$srcicon?>" class="fr" alt="" onerror="loadErrorImage(this,'<?=$conf['thumucroot']?>images/noimg.jpg');"></a>		
<a href="<?=$linknews11?>"><?=$title11?><br><i>(<?=$commentnum?>: Comments)</i></a></div>
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
}
?>