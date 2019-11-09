<?
$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE ((type=0 and parentid =0) and hienthi!=0) order by cat_order asc");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<TD valign="middle"><A title="<?=$ccatalogname1?>" href="<?=$link1?>"><IMG 
            style="MARGIN-TOP: 4px; BORDER: 0px" src="<?=$conf['thumucroot']?>images/home_icon.gif"><?=$ccatalogname1?></A></TD>
          
								
						
<?
}
?>									