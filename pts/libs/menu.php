<?
if (!empty($input["cat"]) && is_numeric($input["cat"]) ){
    $catid = $input["cat"];
	}
?>

<div class="wrapper1">
	<div class="wrapper5">
		<div class="nav-wrapper">
			<div class="nav" id="menuhehe">
				<ul id="navigation">
			   		<li class="<?if (empty($input['act'])) echo 'active'; else echo '';?>">
						<a href="<?=$conf['rooturl']?>">
							<span class="menu-left"></span>
							<span class="menu-mid">Trang chủ</span>
							<span class="menu-right"></span>
						</a>
					</li>
			   		

<?
					$q1 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE ((type=0 and parentid =0) and hienthi!=0) order by cat_order asc");
								while ($cq1=$DB->fetch_row($q1)){
								$ccatalogid1 = $cq1["catalogid"];
								$ccatalogname1 = $cq1["catalogname"];
								$cccatalogname1 = mahoa($ccatalogname1);
								$link1 = $func->seolinkmain($cccatalogname1, $ccatalogid1);
								?>
								
					<li class="<?if (($ccatalogid1 == $catid )||($ccatalogid1 == get_subcat($catid))) echo 'active'; else echo '';?>">
						<a href="<?=$link1?>">
							<span class="menu-left"></span>
							<span class="menu-mid"><?=$ccatalogname1?></span>
							<span class="menu-right"></span>
						</a>
						<?
						$q2 = $DB->query ("SELECT * FROM ".$conf['perfix']."catalog WHERE (parentid = $ccatalogid1 and hienthi!=0) order by cat_order asc");
						if ($DB->num_rows($q2)){
						?>
						<div class="sub">
			   				<ul>
								<?
								while ($cq2=$DB->fetch_row($q2)){
								$ccatalogid2 = $cq2["catalogid"];
								$ccatalogname2 = $cq2["catalogname"];
								$cccatalogname2 = mahoa($ccatalogname2);
								$link2 = $func->seolinkmain($cccatalogname2, $ccatalogid2);
								?>
			   					<li>
									<a href="<?=$link2?>"><img src="<?=$conf['rooturl']?>images/background/gray-square.gif" border=0>&nbsp;<?=$ccatalogname2?></a>
								</li>
								<?
			   					}
								?>
			   				</ul>
			   				<div class="btm-bg"></div>
			   			</div>
						<?
						}
						?>
			   		</li>								
<?
}
?>
					<li class="<?if ($input['act'] == 'sitemap') echo 'active'; else echo '';?>">
						<a href="<?=$conf['rooturl']?>sitemap.html">
							<span class="menu-left"></span>
							<span class="menu-mid">Sitemap</span>
							<span class="menu-right"></span>
						</a>
					</li>
			   	</ul>
			</div>
		</div>

	</div>
</div>

									