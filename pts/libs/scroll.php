<script type="text/javascript">
var pausecontent=new Array()

<?php
			$i = -1;
			$sql1 = "select * from ".$conf['perfix']."events where active!=0 order by eventview DESC LIMIT 0,10";
			$result1 = $DB->query ($sql1) ;
			while ($row1 = $DB->fetch_row($result1)){
			  $i ++;
              $eventid = $row1["eventid"];
              $sql1 ="select * from ".$conf['perfix']."events where eventid=$eventid";
              $resultEvent=$DB->query ($sql1);
              if($event1=$DB->fetch_row($resultEvent)){
				 $eventid = $event1["eventid"];
				 $eventt = $event1["eventtitle"];
				 $eeventt = mahoa($eventt);
				 $linkevent = $func->seolinktopic($eeventt, $eventid);
				 $eventdes = $event1["eventdes"];
				 $eventd = "Updated: ".gmdate("d/m/Y, h:i A",$event["eventpost"] + 7*3600)."";
                 if (!empty($event1['eventpic'])) {
					 if ( (!strstr($event1['eventpic'],"http://"))){
                      $folder1 = gmdate("Y",$event1["eventpost"] + 7*3600);
                      $folder2 = gmdate("m",$event1["eventpost"] + 7*3600);
                      $path = $conf['rooturl']."images/event/".$folder1."/".$folder2."/";
                      $src = $path.$event1['eventpic'];
					 } else
                     $src = $event1['eventpic'];
				 } else $img ="";
				 if ($row % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
                    else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
			  }
echo '
pausecontent[',$i,']=\'<div class="list-item5-contentscroll fl"><a href="',$linkevent,'"><strong>&raquo;</strong>&nbsp;',$eventt,'</a><br><font color="#006666"><i>(&nbsp;',$eventd,'&nbsp;)</i></font><br><br><a href="',$linkevent,'"><img src="',$src,'" class="fl" alt=""></a><br><br></div>\'';
   

}
?>

</script>