<?
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

/*
  Chi co Duc Manh moi thich cai kieu nay - Bien dau vao cua truong hienthi:
       0: Khong hien thi
       1: Hien thi
       2: Hien thi + Tieu diem Left
       3: Hien thi + Tieu diem Right
       4: Hien thi toan bo
*/
$act=new func_catalog($sub);
class func_catalog{
      var $html="";
      var $output="";
      var $base_url="";
      var $listcat="";

      function func_catalog($sub){
               global $func,$DB;
               switch ($sub) {
                       case 'manage' : $this->do_Manage(); break;
                       case 'add' : $this->do_Add(); break;
                       case 'edit' : $this->do_Edit(); break;
                       case 'del' : $this->do_Del(); break;
                       default : $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function Get_Cat($did=-1){
               global $func,$DB,$conf;
               $text= "<select size=1 name=\"parent\">";
               $text.="<option value=\"0\">Make Root (Category)</option>";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 ORDER BY cat_order ASC");
               while ($cat=$DB->fetch_row($query)) {
                      if ($cat['catalogid']==$did)
                          $text.="<option value=\"{$cat['catalogid']}\" selected>{$cat['catalogname']}</option>";
                      else
                          $text.="<option value=\"{$cat['catalogid']}\" >{$cat['catalogname']}</option>";
                      $n=1;
                      $text.=$this->Get_Sub($cat['catalogid'],$n,$did);
               }
               $data['listcat'].="</select>";
               return $text;
       }

       function Get_Sub($cid,$n,$did=-1){
                global $func,$DB,$conf;
                $output="";
                $k=$n;
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cid} ORDER BY cat_order ASC");
                while ($cat=$DB->fetch_row($query)) {
                       if ($cat['catalogid']==$did){
                           $output.="<option value=\"{$cat['catalogid']}\" selected>";
                           for ($i=0;$i<$k;$i++) $output.= "---";
                           $output.="{$cat['catalogname']}</option>";
                       } else {
                           $output.="<option value=\"{$cat['catalogid']}\" >";
                           for ($i=0;$i<$k;$i++) $output.= "---";
                           $output.="{$cat['catalogname']}</option>";
                       }
                       $n=$k+1;
                       $output.=$this->Get_Sub($cat['catalogid'],$n,$did);
                }
                return $output;
       }

       function List_type($did){
                global $func,$DB,$conf;
                $output="<select name=\"type\">";
                if ($did=="0")
                    $output.=" <option value=\"0\" selected>Main catelogy</option>";
                else
                    $output.=" <option value=\"0\">Main catelogy</option>";
                if ($did=="1")
                    $output.=" <option value=\"1\" selected>Sub catelogy</option>";
                else
                    $output.=" <option value=\"1\">Sub catelogy</option>";
                $output.="</select>";
                return $output;
       }

       function List_ht($hid){
                global $func,$DB,$conf;
                $outht="<select name=\"hienthi\">";
                if ($hid=="0")
                    $outht.=" <option value=\"0\" selected>Disabled</option>";
                else
                    $outht.=" <option value=\"0\">Disabled</option>";
                if ($hid=="1")
                    $outht.=" <option value=\"1\" selected>Enabled</option>";
                else
                    $outht.=" <option value=\"1\">Enabled</option>";
                if ($hid=="2")
                    $outht.=" <option value=\"2\" selected>Enabled + Focus Left</option>";
                else
                    $outht.=" <option value=\"2\">Enabled + Focus Left</option>";
                if ($hid=="3")
                    $outht.=" <option value=\"3\" selected>Enabled + Focus Right</option>";
                else
                    $outht.=" <option value=\"3\">Enabled + Focus Right</option>";
                if ($hid=="4")
                    $outht.=" <option value=\"4\" selected>Enabled + Focus All</option>";
                else
                    $outht.=" <option value=\"4\">Enabled + Focus All</option>";
                $outht.="</select>";
                return $outht;
       }

       function do_Add(){
                global $func,$DB,$conf;
                $data = array();
                if (!empty($_POST['btnAdd'])) {
                    $data = $_POST;
                    $data['name'] = $func->txt_HTML($data['name']);
                    $data['parentid']=$_POST["parent"] ;
                    $data['err'] = "";
                    // Check for Error
                    $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogname='{$data['name']}'");
                    if ($check=$DB->fetch_row($query)) $data['err']="Catalog Name Existed";
                    // End check
                    if (empty($data['err'])) {
                        $insert_q = $DB->query("INSERT INTO ".$conf['perfix']."catalog(catalogid,catalogname,type,parentid,hienthi) VALUES('','{$data['name']}','{$data['type']}','{$data['parentid']}','{$data['hienthi']}')");
                        $data['err'] = "Add Catalog Successfull";
                    }
                }
                $data['tittle'] = "Add Catalog";
                $data['listcat']=$this->Get_Cat($data['parentid']);
                $this->output .= $this->html_add($data);
       }

       function do_Edit(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $data['cat_id']=$id;
                if (isset($_POST['btnEdit'])) {
                    $data = $_POST;
                    $data['title'] = $func->txt_HTML($data['title']);
                    $data['err'] = "";
                    // Check for Error
                    $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogname='{$data['title']}' and catalogid <> {$data['cat_id']} ");
                    if ($check=$DB->fetch_row($query)) $data['err']="Catalog Name existed";
                    // End check
                    if (empty($data['err'])) {
                        $query = "UPDATE ".$conf['perfix']."catalog SET catalogname='{$data['title']}',type='{$data['type']}',parentid='{$data['parent']}',hienthi='{$data['hienthi']}' WHERE catalogid='{$data['cat_id']}'";
                        $update_q = $DB->query($query);
                        $data['err'] = "Edit Cataloge Successfull !";
                    }
                }
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogid='{$data['cat_id']}'");
                if ($check=$DB->fetch_row($query)){
                    $data['title'] = $func->txt_unHTML($check['catalogname']);
                    $data['parentid']=$check['parentid'];
                    $data['listcat']=$this->Get_Cat($data['parentid']);
                    $data['listtype']=$this->List_type($check['type']);
                    $data['listht']=$this->List_ht($check['hienthi']);
                }
                $data['tittle'] = "Edit Cataloge";
                $this->output .= $this->html_edit($data);
       }

       function do_Del(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $del=0; $qr="";
                if ($id!=0) {
                    $del=1;
                    $qr = " OR catalogid='{$id}' ";
                }
                if (isset($_POST["delcat"]))  $key=$_POST["delcat"] ;
                for ($i=0;$i<count($key);$i++) {
                     $del=1;
                     $qr .= " OR catalogid='{$key[$i]}' ";
                }
                if (isset ($_POST["btnOrder"])){
                    if (isset($_POST["hcatid"])) $catid = $_POST["hcatid"] ; else $catid="";
                    if (isset($_POST["txtOrder"])) $order = $_POST["txtOrder"] ; else $order="";
                    if (isset($_POST["txtht"])) $hienthi = $_POST["txtht"] ; else $hienthi="";
                    for ($i=0;$i<count($order);$i++){
                         $sql = "UPDATE ".$conf['perfix']."catalog SET cat_order='{$order[$i]}',hienthi='{$hienthi[$i]}' WHERE catalogid='{$catid[$i]}'";
                         $update_q = $DB->query($sql);
                    }
                }
                if ($del) {
                    $query = "DELETE FROM ".$conf['perfix']."catalog WHERE catalogid=-1".$qr;
                    if ($ok = $DB->query($query)){
                        $mess = "Delete Cataloge successfull";
                        $DB->query("DELETE FROM ".$conf['perfix']."news WHERE catalogid=-1".$qr);
                        $DB->query("DELETE FROM ".$conf['perfix']."focus_news WHERE cat_id=-1".$qr);
                        $DB->query("DELETE FROM ".$conf['perfix']."focus_cat WHERE cat_id=-1".$qr);
                    } else $mess = "Catalog not found !";
                    $url = "index.php?act=cataloge&sub=manage";
                    $this->output .= $this->html_ann($url,$mess);
                } else $this->do_Manage();
       }

       function do_Manage(){
                global $func,$DB,$conf;
                if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 AND type=0");
                $totals_news = $DB->num_rows($query);
                $n=10;
                $num_pages = ceil($totals_news/$n) ;
                if ($p > $num_pages) $p=$num_pages;
                if ($p < 1 ) $p=1;
                $start = ($p-1) * $n ;
                $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
                for ($i=1; $i<$num_pages+1; $i++ ) {
                     if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                     else $nav.="[<a href='?act=cataloge&sub=manage&p={$i}'>$i</a>] ";
                }
                $nav .= "</div></center>";
                // Het phan trang
                $list = "";
                $stt=0;
                $sql="SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 AND type=0 ORDER BY  cat_order ASC";
                $query = $DB->query($sql);
                while ($catalog=$DB->fetch_row($query)) {
                       $catalog['stt'] = $stt;
                       $catalog['parent'] ="Main Root";
                       $catalog['ext']="";
                       $catalog['ht'] = $catalog['hienthi'];
                       if ( $catalog['ht']=="0" )  $catalog['dis']="selected";  else  $catalog['dis']="";
                       if ( $catalog['ht']=="1" )  $catalog['ena']="selected";  else  $catalog['ena']="";
                       if ( $catalog['ht']=="2" )  $catalog['enl']="selected";  else  $catalog['enl']="";
                       if ( $catalog['ht']=="3" )  $catalog['enr']="selected";  else  $catalog['enr']="";
                       if ( $catalog['ht']=="4" )  $catalog['all']="selected";  else  $catalog['all']="";
                       $list .=$this->html_row($catalog);
                       $n=1;
                       $list .=$this->Row_Sub($catalog['catalogid'],$n);
                       $stt++;
                }
                /* Neu dung Subcat == Root
                $sql1 = "SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 AND type=1 ORDER BY cat_order ASC";
                $query1 = $DB->query($sql1);
                while ($catalog1=$DB->fetch_row($query1)) {
                       $catalog1['parent'] ="Sub Root";
                       $catalog1['ext']="";
                       $catalog1['ht'] = $catalog1['hienthi'];
                       if ( $catalog1['ht']=="0" )  $catalog1['dis']="selected";  else  $catalog1['dis']="";
                       if ( $catalog1['ht']=="1" )  $catalog1['ena']="selected";  else  $catalog1['ena']="";
                       if ( $catalog1['ht']=="2" )  $catalog1['enl']="selected";  else  $catalog1['enl']="";
                       if ( $catalog1['ht']=="3" )  $catalog1['enr']="selected";  else  $catalog1['enr']="";
                       if ( $catalog1['ht']=="4" )  $catalog1['all']="selected";  else  $catalog1['all']="";
                       $list1 .=$this->html_row($catalog1);
                       $m=1;
                       $list1 .=$this->Row_Sub($catalog1['catalogid'],$m);
                }
                $nd['tittle1'] = "Manage Sub Cataloge";
                */
                $nd['tittle'] = "Manage Main Cataloge";
                $nd['nd'] = $list;
                $nd['nd1'] = $list1;
                $nd['num'] = $stt+2;
                $this->output .= $this->html_nav($nd);
                $this->output .= $nav."<br>";
       }

       function Row_Sub($cid,$n){
                global $func,$DB,$conf;
                $textout="";
                $n1=$n;
                $query1 = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid='{$cid}' ORDER BY cat_order ASC");
                while ($subcats=$DB->fetch_row($query1)) {
                       $subcats['stt'] = $stt;
                       $subcats['parent'] = "Sub Cateloge";
                       $subcats['ht'] = $subcats['hienthi'];
                       if ( $subcats['ht']=="0" )  $subcats['dis']="selected";  else  $subcats['dis']="";
                       if ( $subcats['ht']=="1" )  $subcats['ena']="selected";  else  $subcats['ena']="";
                       if ( $subcats['ht']=="2" )  $subcats['enl']="selected";  else  $subcats['enl']="";
                       if ( $subcats['ht']=="3" )  $subcats['enr']="selected";  else  $subcats['enr']="";
                       if ( $subcats['ht']=="4" )  $subcats['all']="selected";  else  $subcats['all']="";
                       $subcats['ext']="&nbsp;&nbsp;";
                       for ($k=0;$k<$n1;$k++) $subcats['ext'].="&nbsp;&nbsp;";
                       $textout .= $this->html_row($subcats);
                       $n=$n1+1;
                       $textout .=$this->Row_Sub($subcats['catalogid'],$n);
                }
                return $textout;
       }

       function html_add($data){
                return<<<EOF
                        <script language=javascript>
                           function checkform(f) {
                                    var name = f.name.value;
                                    if (name == '') {
                                        alert('Plz enter Catalog name');
                                        f.name.focus();
                                        return false;
                                    }
                                    var description = f.description.value;
                                    if (description == ''){
                                        alert("Plz enter Content");
                                        f.description.focus();
                                        return false;
                                    }
                                    return true;
                           }
                        </script><br>
                        <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                           <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                     <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                     <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                     <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                  </tr>
                              </table></td>
                           </tr>
                           <tr>
                              <td bgcolor="#FFFFFF" class="main_table" align=center>
                                 <form action="?act=cataloge&sub=add" method="post" name="add_news" onSubmit="return checkform(this);">
                                    <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                       <tr>
                                           <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                       </tr>
                                       <tr>
                                           <td width="24%" align="right">Name : </td>
                                           <td width="76%" align="left"><input name="name" type="text" id="tittle" size="50" maxlength="250" value="{$data['name']}"></td>
                                       </tr>
                                       <tr>
                                           <td align="right">Type Catelogy: </td>
                                           <td align="left"><select name="type">
                                               <option value="0">Main catelogy</option>
                                               <option value="1">Sub catelogy</option>
                                           </select></td>
                                       </tr>
                                       <tr>
                                           <td align="right">Parent category  : </td>
                                           <td align="left">{$data['listcat']}</td>
                                       </tr>
                                       <tr>
                                           <td align="right">Active: </td>
                                           <td align="left"><select name="hienthi">
                                               <option value="0">Disabled</option>
                                               <option value="1">Enabled</option>
                                               <option value="2">Enabled + Focus Left</option>
                                               <option value="3">Enabled + Focus Right</option>
                                               <option value="4">Enabled + Focus All</option>
                                           </select></td>
                                       </tr>
                                       <tr>
                                           <td colspan="2">
                                              <input type="submit" name="btnAdd" value="Submit">
                                              <input type="reset" name="Submit2" value="Reset">
                                           </td>
                                       </tr>
                                    </table>
                                 </form></td>
                              </tr>
                              <tr>
                                 <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                         <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                         <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                         <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                     </tr>
                                 </table></td>
                              </tr>
                           </table><br>
EOF;
        }

        function html_edit($data){
                 return<<<EOF
                          <script language=javascript>
                             function checkform(f) {
                                      var title = f.title.value;
                                      if (title == '') {
                                          alert('Plz enter Catalog name');
                                          f.title.focus();
                                          return false;
                                      }
                                      var description = f.description.value;
                                      if (description == ''){
                                          alert("Plz enter Content");
                                          f.description.focus();
                                          return false;
                                      }
                                      return true;
                             }
                          </script>
                          <br><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                 <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                 </table></td>
                             </tr>
                             <tr>
                                 <td bgcolor="#FFFFFF" class="main_table" align=center>
                                 <form action="?act=cataloge&sub=edit" method="post" name="edit_catalog" onSubmit="return checkform(this);">
                                    <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                       <tr>
                                          <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                       </tr>
                                       <tr>
                                          <td width="24%" align="right">Catalogy Name : </td>
                                          <td width="76%" align="left"><input name="title" type="text" id="title" size="50" maxlength="250" value="{$data['title']}"></td>
                                       </tr>
                                       <tr>
                                          <td align="right">Type Catelogy: </td>
                                          <td align="left">{$data['listtype']}</td>
                                       </tr>
                                       <tr>
                                          <td align="right">Parent category  : </td>
                                          <td align="left">{$data['listcat']} </td>
                                       </tr>
                                       <tr>
                                          <td align="right">Active: </td>
                                          <td align="left">{$data['listht']}</td>
                                       </tr>
                                       <tr>
                                          <td colspan="2">
                                            <input name="cat_id" type="hidden" value="{$data['cat_id']}">
                                            <input type="submit" name="btnEdit" value="Submit">
                                            <input type="reset" name="Submit2" value="Reset">
                                          </td>
                                       </tr>
                                    </table>
                                 </form></td>
                             </tr>
                             <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                      <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                      <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                      <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                   </tr>
                                </table></td>
                             </tr>
                          </table><br>
EOF;
        }

        function html_nav($data){
                 return<<<EOF
                          <script language="javascript">
                             function checkall(num){
                                      for ( i=0;i < document.manage.elements.length ; i++ ){
                                            if ( document.manage.all.checked==true ){
                                                 document.manage.elements[i].checked = true;
                                            } else {
                                                 document.manage.elements[i].checked  = false;
                                            }
                                      }
                             }
                          </script>
                          <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                 <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                 </table></td>
                              </tr>
                              <tr>
                                 <td bgcolor="#FFFFFF" class="main_table" align=center>
                                 <form action="index.php?act=cataloge&sub=del" method="post" name="manage" id="manage">
                                     <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                        <tr>
                                           <td width="8%" class="row_tittle">Delete</td>
                                           <td width="10%" class="row_tittle">Order</td>
                                           <td width="25%" class="row_tittle">Cateloge Name</td>
                                           <td width="25%" class="row_tittle">Cateloge Parent</td>
                                           <td width="22%" class="row_tittle">Active</td>
                                           <td width="10%" class="row_tittle">Actions</td>
                                        </tr>
                                        {$data['nd']}
                                        <tr>
                                           <td width="8%"class="row_tittle">&nbsp;</td>
                                           <td colspan=6 class="row_tittle" align=left>
                                           <input type="submit" name="btnOrder" value="Edit Order & Active">&nbsp;&nbsp;
                                           <input type="submit" name="Submit" value="Delete seleted Catalog"></td>
                                        </tr>
                                     </table>
                                 </form></td>
                              </tr>
                              <tr>
                                 <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                      <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                      <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                      <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                   </tr>
                                 </table></td>
                              </tr>
                          </table><br><br>
                          <!-- Neu su dung Subcat la Root <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                      <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                      <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle1']}</td>
                                      <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                   </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                <form action="index.php?act=cataloge&sub=del" method="post" name="manage" id="manage">
                                   <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                      <tr>
                                          <td width="8%" class="row_tittle">Delete</td>
                                          <td width="10%" class="row_tittle">Order</td>
                                          <td width="25%" class="row_tittle">Cateloge Name</td>
                                          <td width="25%" class="row_tittle">Cateloge Parent</td>
                                          <td width="22%" class="row_tittle">Active</td>
                                          <td width="10%" class="row_tittle">Actions</td>
                                      </tr>
                                      {$data['nd1']}
                                      <tr>
                                          <td width="8%" class="row_tittle">&nbsp;</td>
                                          <td colspan=6 class="row_tittle" align=left>
                                              <input type="submit" name="btnOrder" value="Edit Order & Active">&nbsp;&nbsp;
                                              <input type="submit" name="Submit" value="Delete seleted Catalog">
                                          </td>
                                      </tr>
                                   </table>
                                </form></td>
                             </tr>
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                       <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                       <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                   </tr>
                                </table></td>
                             </tr>
                          </table><br>-->
EOF;
        }

        function html_row($data){
                 return<<<EOF
                          <tr>
                             <td class="row1"><input name="delcat[]" type="checkbox" value="{$data['catalogid']}"></td>
                             <td class="row" align=left>
                                 <input name="hcatid[]" type="hidden" value="{$data['catalogid']}">
                                 {$data['ext']}<input name="txtOrder[]" type="text" size="2" maxlength="2" value="{$data['cat_order']}" style="text-align:center">
                             </td>
                             <td class="row" align=left>{$data['ext']}<a href="?act=cataloge&sub=edit&id={$data['catalogid']}">{$data['catalogname']}</a></td>
                             <td class="row" align=left>{$data['parent']}</td>
                             <td class="row" align=center><select name="txtht[]">
                                                           <option value="0" {$data['dis']}> Disabled </option>
                                                           <option value="1" {$data['ena']}> Enabled </option>
                                                           <option value="2" {$data['enl']}> Enabled + Focus Left </option>
                                                           <option value="3" {$data['enr']}> Enabled + Focus Right </option>
                                                           <option value="4" {$data['all']}> Enabled + Focus All </option>
                                                        </select>
                             </td>
                             <td class="row">
                                 <a href="?act=cataloge&sub=edit&id={$data['catalogid']}"><img src="images/edit.gif" width="22" height="22" alt="Edit Cataloge"></a>&nbsp;
                                 <a href="?act=cataloge&sub=del&id={$data['catalogid']}"><img src="images/delete.gif" width="22" height="22" alt="Delete Cataloge"></a>
                             </td>
                          </tr>
EOF;
        }

        function html_ann($url,$mess){
                 return<<<EOF
                          <br><br><br><br><br>
                          <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                          <meta http-equiv='refresh' content='1; url={$url}'>
                          </head>
                          <body>
                          <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                      <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                      <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>Announcement</td>
                                      <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                   </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                   <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                      <tr>
                                         <td width="100%" align=center><font color="red">{$mess}</font></td>
                                      </tr>
                                   </table>
                                </td>
                             </tr>
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                       <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                       <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                    </tr>
                                </table></td>
                             </tr>
                          </table>
                          </body><br><br><br><br><br>
EOF;
         }
}

?>