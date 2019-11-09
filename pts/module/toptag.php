<?
if ($conf['display_tagbox'] == 'yes'){
?>
<div class="box-item">
	<div class="list-item-header fl">
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeleft2.gif" alt=""></div>
		<div class="folder-active2 fl">
			.:: Tag Articles ::.
		</div>
		<div class="fl"><img src="<?=$conf['thumucroot']?>images/folder-activeright2.gif" alt=""></div>
		<div class="fr"><img src="<?=$conf['thumucroot']?>images/folder-topright.gif" alt=""></div>
	</div>
			<div class="fl" id="ListItem144">
			
<div class="box-middle1 list-item5 fl">	
 <div class="list-item5-contentscroll fl">
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

<a href="<?=$func->seolinktag(mahoa($elementtag["key"]));?>"><font size="<? echo rand(2,6);?>"><?=$elementtag["key"]?>(<?=$func->totalnewstag($elementtag["value"])?>)</font></a>

<?
}
}
?>
</div>
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