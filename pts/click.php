<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

require_once("libs/_config.php");
require_once("libs/_mysql.php");
$DB = new DB;
$DB->connect();

if (isset($_GET["bid"]) and is_numeric($_GET["bid"])) {
    $logoid = $_GET["bid"];
    $query  = $DB->query("SELECT link,click,vitri FROM ".$conf['perfix']."banner WHERE logo_id='$logoid'");
    $banner = $DB->fetch_row($query);
    if ( $banner["vitri"] == "hot" ) {
         $linkbn = $conf['rooturl'].$banner["link"];
    } else {
         $linkbn = $banner["link"];
    }
    $click  = $banner["click"] + 1;
    $save   = $DB->query("UPDATE ".$conf['perfix']."banner SET click='$click' WHERE logo_id='$logoid'");
    Header ("Location: $linkbn");
}
$DB->close();
?>