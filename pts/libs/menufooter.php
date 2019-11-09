<?
$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE ((type=0 and parentid =0) and hienthi!=0) order by cat_order asc");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<td align="center" style="padding-left: 5px; padding-right: 5px">
                            <a class="menufooter" title="<?=$ccatalogname1?>" href="<?=$link1?>"><?=$ccatalogname1?></a>
                        </td>
                        <td>|</td>
								
								
						
<?
}
?>									