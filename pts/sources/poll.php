<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

if (isset($_POST["p_id"])) $p_id =$_POST["p_id"];
if (isset($_POST["option_id"])) $option_id =$_POST["option_id"];
if (isset($_POST["btnPoll"])){
    $DB->query ("update ".$conf['perfix']."poll_option set hits=hits+1  where option_id=$option_id ");
}
$totalhits=0;
$query = "select * from ".$conf['perfix']."poll where poll_id=$p_id ";
$result = $DB->query ($query);
if ($row =$DB->fetch_row($result)){
    $poll_name = $row["poll_name"];
}
$query = "select * from ".$conf['perfix']."poll_option where poll_id=$p_id";
$result = $DB->query ($query);
while ($row=$DB->fetch_row($result)){
       $totalhits +=$row["hits"];
}
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" style="margin-top:3px;">
  <tr>
    <td align="center"><font color="#FF0000" size="+1"><b>K&#7871;t qu&#7843; th&#259;m d&ograve; &yacute; ki&#7871;n</b></font></td>
  </tr>
  <tr>
    <td><b><?=$poll_name?></b></td>
  </tr>
  <tr>
    <td>S&#7889; ng&#432;&#7901;i tham gia b&#7847;u ch&#7885;n l&agrave; : <?=$totalhits?></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:5">
      <tr>
        <td width="50%" ><strong>Tr&#7843; l&#7901;i</strong></td>
        <td width="27%"><strong>T&#7927; l&#7879; % </strong></td>
        <td width="23%"><strong>L&#432;&#7907;t ch&#7885;n</strong></td>
      </tr>
      <?php
          $query = "select * from ".$conf['perfix']."poll_option where poll_id=$p_id";
          $result = $DB->query ($query);
          while ($row=$DB->fetch_row($result)){
                 $rate = number_format(( $row["hits"]/$totalhits)*100,1);
      ?>
      <tr>
        <td><?=$row["option_name"]?></td>
        <td><?php
                $width =$rate;
                print "<img src=\"./images/redline.gif\" width=\"$width\" height=\"8\"><br> &nbsp; $rate  %";
            ?>
        </td>
        <td align="center"><?=$row["hits"]?></td>
      </tr>
          <?php }?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>