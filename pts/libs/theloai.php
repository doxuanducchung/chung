<?
if (!empty($input["cat"]) && is_numeric($input["cat"]))
$q = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogid={$input['cat']}");
    if ( $cq=$DB->fetch_row($q) ){
						   $cid = $cq["catalogid"];
						   $ctype= $cq["type"];
						   $cparentid = $cq["parentid"];
						   $ccatalogname = $cq["catalogname"];
						   $cccatalogname = mahoa($ccatalogname);
						   $clink = $func->seolinkmain($cccatalogname, $cid);
						   
						   if ($ctype == 0) {
						   
								$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$input['cat']}");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<a class="link-othernews" href="<?=$link1?>"><img src="<?=$conf['thumucroot']?>images/background/blue-square.gif" border="0">&nbsp;<b><?=$ccatalogname1?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;
								
<?								
								}
								
						   } else {
								$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=$cparentid");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<a class="link-othernewstheloai-menu" href="<?=$link1?>"><img src="<?=$conf['thumucroot']?>images/background/blue-square.gif" border="0">&nbsp;<b><?=$ccatalogname1?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;
								
<?								
								}
								
						   }
	}
//Het phan Cat
if (!empty($input["newsid"]) && is_numeric($input["newsid"])) {
    $n= $DB->query ("select * from ".$conf['perfix']."news where newsid={$input['newsid']}");
	if ( $cn=$DB->fetch_row($n) ) {
	$catid = $cn["catalogid"];
	$q = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogid=$catid");
		if ( $cq=$DB->fetch_row($q) ){
						   $cid = $cq["catalogid"];
						   $ctype= $cq["type"];
						   $cparentid = $cq["parentid"];
						   $ccatalogname = $cq["catalogname"];
						   $cccatalogname = mahoa($ccatalogname);
						   $clink = $func->seolinkmain($cccatalogname, $cid);
						   
						   if ($ctype == 0) {
						   
								$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=$cid");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<a class="link-othernews" href="<?=$link1?>"><img src="<?=$conf['thumucroot']?>images/background/blue-square.gif" border="0">&nbsp;<b><?=$ccatalogname1?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;
								
<?								
								}
								
						   } else {
								$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=$cparentid");
								while  ( $cq1=$DB->fetch_row($q1) ){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								<a class="link-othernews" href="<?=$link1?>"><img src="<?=$conf['thumucroot']?>images/background/blue-square.gif" border="0">&nbsp;<b><?=$ccatalogname1?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;
								
<?								
								}
								
						   }
		}
	
	}
}
if (empty($input["newsid"]) && empty($input["cat"])) {
?>
<font color="red"><b>Topics nổi bật:&nbsp;</b></font> 
<?
    $querye = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE active=1 ORDER BY eventid DESC LIMIT 5");
	while ($rowe = $DB->fetch_row($querye)) {
                    $eventid = $rowe['eventid'];
                    $eventt = $rowe['eventtitle'];
					$eeventt = mahoa($eventt);
                    $linke = $func->seolinktopic($eeventt, $eventid);
?>
								<a class="link-othernews" href="<?=$linke?>"><img src="<?=$conf['thumucroot']?>images/background/blue-square.gif" border="0">&nbsp;<B><?=$eventt?></B></a>&nbsp;&nbsp;
								
<?								
	}
}						   
?>