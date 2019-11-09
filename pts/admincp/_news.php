<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright � 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_news($sub);
class func_news{
      var $html="";
      var $output="";
      var $base_url="";
      var $listcat="";

      function func_news($sub){
               global $func,$DB,$conf;
               switch ($sub) {
                       case 'add' : $this->do_Add(); break;
                       case 'edit' : $this->do_Edit(); break;
                       case 'edit_pic' : $this->do_Edit_Pic(); break;
                       case 'del' : $this->do_Del(); break;
                       default : $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function Get_Cat($did=-1){
               global $func,$DB,$conf;
               $text= "<select size=1 name=\"catalog\" onChange=\"document.catform.submit();\">";
               $text.="<option value=\"\">-- Select Category --</option>";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 ORDER BY cat_order ASC");
               while ($cat=$DB->fetch_row($query)) {
                      if ($cat['catalogid']==$did)
                          $text.="<option value=\"{$cat['catalogid']}\" selected>{$cat['catalogname']}</option>";
                      else
                          $text.="<option value=\"{$cat['catalogid']}\" >{$cat['catalogname']}</option>";
                      $n=1;
                      $text.=$this->Get_Sub($cat['catalogid'],$n,$did);
               }
               $text.="</select>";
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
                       }else{
                           $output.="<option value=\"{$cat['catalogid']}\" >";
                           for ($i=0;$i<$k;$i++) $output.= "---";
                           $output.="{$cat['catalogname']}</option>";
                       }
                       $n=$k+1;
                       $output.=$this->Get_Sub($cat['catalogid'],$n,$did);
                }
                return $output;
       }

       function List_Tile($id){
                global $func,$DB,$conf;
                $text= "<select size=1 name=\"list_title\" >";
                $text.="<option value=\"\" selected>-- Select --</option>";
                for ($i="A";$i<"Z";$i++){
                     if ($id ==$i)
                         $text.="<option value=\"{$i}\" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$i."</option>";
                     else
                         $text.="<option value=\"{$i}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$i."</option>";
                }
                if ($id=="Z") $text.="<option value=\"Z\" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Z</option>";
                else $text.="<option value=\"Z\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Z</option>";
                if ($id=="Đ") $text.="<option value=\"&#272;\" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#272;</option>";
                else $text.="<option value=\"&#272;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#272;</option>";
                for ($j=0;$j<10;$j++){
                     if ($id ==$j)
                         $text.="<option value=\"{$j}\" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$j."</option>";
                     else
                         $text.="<option value=\"{$j}\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$j."</option>";
                }
                $text.="</select>";
                return $text;
       }

       function List_View ($id){
                global $func,$DB,$conf;
                $text= "<select size=1 name=\"list_view\" >";
                if ($id =="10") $text.="<option value=\"10\" selected> 10 </option>";
                else $text.="<option value=\"10\" > 10 </option>";

                if ($id =="20") $text.="<option value=\"20\" selected> 20 </option>";
                else $text.="<option value=\"20\" > 20 </option>";

                if ($id =="30") $text.="<option value=\"30\" selected> 30 </option>";
                else $text.="<option value=\"30\" > 30 </option>";

                if ($id =="50") $text.="<option value=\"50\" selected> 50 </option>";
                else $text.="<option value=\"50\" > 50 </option>";

                if ($id =="100") $text.="<option value=\"100\" selected> 100 </option>";
                else $text.="<option value=\"100\" > 100 </option>";

                $text.="</select>";
                return $text;
       }

       function List_SubCat($cat_id){
                global $func,$DB,$conf;
                $output="";
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cat_id}");
                while ($cat=$DB->fetch_row($query)) {
                       $output.=$cat["catalogid"].",";
                       $output.=$this->List_SubCat($cat['catalogid']);
                }
                return $output;
       }

       // Tu dong Tim kiem, Save to Local va Thay the Picture trong Content
       function replace_img($str,$adddate){
                global $conf;
                $data['result']= $tmp = $str ;
                $data['url']="";
                $up=1;
                // Kiem tra thu muc chua Picture
                $timep = explode("-",$adddate);
                $dir1 = $timep[0];
                $dir2 = $timep[1];
                $dir3 = $timep[2];
                $upload_pic = $conf['rootpath']."images/news/";
                if (!is_dir($upload_pic.$dir1)) {
                    mkdir($upload_pic.$dir1,0777);
                    $handle = fopen($upload_pic.$dir1."/index.html", "w");
                    fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                    fclose($handle);
                    @chmod($upload_pic.$dir1."/index.html", 0644);
                }
                if (!is_dir($upload_pic.$dir1."/".$dir2)) {
                    mkdir($upload_pic.$dir1."/".$dir2,0777);
                    $handle = fopen($upload_pic.$dir1."/".$dir2."/index.html", "w");
                    fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                    fclose($handle);
                    @chmod($upload_pic.$dir1."/index.html", 0644);
                }
                if (!is_dir($upload_pic.$dir1."/".$dir2."/".$dir3)) {
                    mkdir($upload_pic.$dir1."/".$dir2."/".$dir3,0777);
                    $handle = fopen($upload_pic.$dir1."/".$dir2."/".$dir3."/index.html", "w");
                    fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                    fclose($handle);
                    @chmod($upload_pic.$dir1."/index.html", 0644);
                }
                // Het phan kiem tra thu muc va tao thu muc chua anh moi

                while ($start= strpos($tmp,"src=")){
                       /* Neu la tren Server thi dung doan sau
                       $end = strpos($tmp,'"',$start+6);
                       $http=substr($tmp,$start+5,($end-($start+5))); */
                       // Neu la Localhost thi dung cai nay
                       $end = strpos($tmp,'"',$start+7);
                       $http=substr($tmp,$start+6,($end-($start+7)));
                       // Auto Save Anh No Upload
                       $fext = strtolower(substr($http,strrpos($http,".")+1));
                       // Kiem tra xem Url cua anh co phai la Local khong
                       $localp = strlen($conf['rooturl']);
                       $localp = substr($http,0,$localp);
                       if ($localp != $conf['rooturl']) {
                           if ( ($fext=="jpg") || ($fext=="gif") ) {
                                 $lname = "chf_".time().".".$fext;
                                 $fname = $upload_pic.$dir1."/".$dir2."/".$dir3."/".$lname;
                                 $tt = 1; $i = 1;
                                 while ($tt==1) {
                                        if (file_exists($fname)) {
                                            $lname = "chf_".$i.time().".".$fext;
                                            $fname = $upload_pic.$dir1."/".$dir2."/".$dir3."/".$lname;
                                        } else $tt=0;
                                        $i++;
                                 }
                                 $file = @fopen($fname,"w");
                                 if ( $f = @fopen($http,"r") ) {
                                      while (! @feof($f)) {
                                               @fwrite($file, fread($f, 1024));
                                      }
                                      @fclose($f); @fclose($file);
                                      $url = "".$conf['thumucroot']."images/news/".$dir1."/".$dir2."/".$dir3."/".$lname;
                                      $data['result']= str_replace($http,$url,$data['result']);
                                      if ($up ==1){
                                          $data['url'] = $url ;
                                          $up ==0;
                                      }
                                 } else $data['err'] = "Cannot Read from this Image ! Plz save to your Computer and Upload It";
                           }else $data['err'] = "Image Type Not Support";
                       } // Neu khong phai anh cua Local thi moi Copy moi
                       $tmp = substr($tmp,$end+1) ;
                }// end while
                return $data;
       }

       function do_Add(){
                global $func,$DB,$conf;
                $data['f_title'] = "Add News";
                $data['html_option']="";
                if (!empty($_POST['btnAdd'])) {
                    $data = $_POST;
                    $data['f_title'] = "Add News";
                    $data['n_title'] = $func->txt_HTML($data['title']);
                    $data['n_short'] = $func->txt_HTML($data['short']);
                    $data['n_time'] = date("Y-m-d");
                    $data['n_timepost'] = time();
                    $data['n_content'] =$data['content'];
                    $data['n_userid'] = $_SESSION['user_id'];
                    $data['n_source'] = $func->txt_HTML($data['source']);
                    $data['n_picdes'] = $func->txt_HTML($data['picdes']);
					$data['n_tag'] = $data['tag'];
					$tagarray = explode(",",$data['n_tag']);
					$data['n_tag1'] = mahoa($tagarray[0]);
					$data['n_tag2'] = mahoa($tagarray[1]);
					$data['n_tag3'] = mahoa($tagarray[2]);
                    $data['err'] = "";
                    // Check for Error
                    $query ="SELECT * FROM ".$conf['perfix']."news WHERE title='{$data['n_title']}'";
                    $query = $DB->query($query);
                    if ($check=$DB->fetch_row($query)) $data['err']="News Title existed";
                    // Kiem tra xem co upload anh khong
                    if (!empty($_FILES['image'][name])) {
                         // Manh thay doi cau truc luu anh news
                         $dir1=date("Y",$data['n_timepost']);
                         $dir2=date("m",$data['n_timepost']);
                         $dir3=date("d",$data['n_timepost']);
                         $upload_pic = $conf['rootpath']."images/news/";
                         if (!is_dir($upload_pic.$dir1)) {
                              mkdir($upload_pic.$dir1,0777);
                              $handle = fopen($upload_pic.$dir1."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2)) {
                              mkdir($upload_pic.$dir1."/".$dir2,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2."/".$dir3)) {
                              mkdir($upload_pic.$dir1."/".$dir2."/".$dir3,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/".$dir3."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         // Het phan kiem tra thu muc va tao thu muc chua anh moi
                         $up['path']= $conf['rootpath'];
                         $up['dir'] = "images/news/".$dir1."/".$dir2."/".$dir3;
                         $up['file']= $_FILES['image'];
                         $up['type']= "hinh";
                         $up['resize']= 0;
                         $up['thum']= 1;
                         $up['w']= 128; //Chieu ngang toi da cua anh dai dien thumbnail
                         $up['ato']= 248; //Chieu ngang toi da cua anh to
						 $up['ico']= 80; //Chieu ngang toi da cua anh icon
                         $up['timepost']= $data['n_timepost']; //Lay thoi gian luc dang bai
                         $result = $func->Upload($up);
                         if (empty($result['err'])) {
                             $data['n_image']=$result['link'];
                             $data['t_image']=$result['type'];
                         } else {
                             $data['err'] = $result['err'];
                         }
                         if (empty($data['err'])) {
                             $query="INSERT INTO ".$conf['perfix']."news VALUES ('','{$data['catalog']}','{$data['event']}','{$data['n_title']}','{$data['n_short']}','{$data['n_content']}','{$data['n_image']}','{$data['t_image']}','{$data['n_picdes']}','{$data['n_time']}','{$data['n_source']}','{$data['n_userid']}','{$up['timepost']}', '{$data['n_tag']}', '{$data['n_tag1']}','{$data['n_tag2']}','{$data['n_tag3']}','0','0','{$data['display']}')";
                             $insert_q = $DB->query($query);
                             $mess = "Upload Pic & Add News Successfull";
                         }
                    } else {
                         // Neu khong upload pic for news
                         if (empty($data['err'])) {
                             $query="INSERT INTO ".$conf['perfix']."news VALUES ('','{$data['catalog']}','{$data['event']}','{$data['n_title']}','{$data['n_short']}','{$data['n_content']}','','','','{$data['n_time']}','{$data['n_source']}','{$data['n_userid']}','{$data['n_timepost']}', '{$data['n_tag']}', '{$data['n_tag1']}','{$data['n_tag2']}','{$data['n_tag3']}','0','0','{$data['display']}')";
                             $insert_q = $DB->query($query);
                             $mess = "No Upload Pic & Add News Successfull";
                         }
                    } // Het kiem tra Upload News Pic

                    // Cap nhat so bai viet do Username nay Post
                    $savebv  = $DB->query("UPDATE ".$conf['perfix']."user SET num_post=num_post+1 WHERE user_id=".$data['n_userid']);
                    $url = "index.php?act=news&sub=manage";
                    $this->output .= $this->html_ann($url,$mess);
                } else {
                    // Neu chua Post News moi
                    $query = $DB->query("SELECT eventid,eventtitle FROM ".$conf['perfix']."events ORDER BY eventpost DESC");
                    $list = "";
                    while ($event = $DB->fetch_row($query)){
                           $list .= "<option value=\"{$event["eventid"]}\"> - {$event["eventtitle"]}</option>";
                    }
                    $data['listevent'] = $list;
                    $data['listcat']=$this->Get_Cat($data['catalog']);
                    $this->output .= $this->html_add($data);
                }
       }

       function do_Edit(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $data['n_id']=$id;
                $data['n_userid'] = $_SESSION['user_id'];
                if ( isset($_POST['btnEdit'])){
                     $data = $_POST;
                     $data['n_title'] = $func->txt_HTML($data['title']);
                     $data['n_short'] = $func->txt_HTML($data['short']);
                     $data['n_datep'] = $func->txt_HTML($data['adddateold']);
                     $data['n_content'] = $data['content'];
                     $data['n_source'] = $func->txt_HTML($data['source']);
                     $data['n_picdes'] = $func->txt_HTML($data['picdes']);
					 $data['n_tag'] = $func->txt_HTML($data['tag']);
					 $tagarray = explode(",",$data['n_tag']);
					 $data['n_tag1'] = mahoa($tagarray[0]);
					 $data['n_tag2'] = mahoa($tagarray[1]);
					 $data['n_tag3'] = mahoa($tagarray[2]);
                     $data['err'] = "";

                     // Check for Error Title
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE title='{$data['n_title']}' AND newsid <> '{$data['n_id']}'");
                     if ($check = $DB->fetch_row($query)) $data['err'] = "News Title Existed";

                     // Check Upload New Pic
                     if ( !empty($_FILES['image'][name])){

                         // Manh thay doi cau truc luu anh news
                         // Lay thong tin cu cua News
                         $queryold = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE newsid='{$data['n_id']}'");
                         $oldinfo = $DB->fetch_row($queryold);
                         $picture = $oldinfo['picture'];
                         $adddate = $oldinfo['adddate'];
                         $tmp = explode("-",$adddate);
                         $dir1 = $tmp[0];
                         $dir2 = $tmp[1];
                         $dir3 = $tmp[2];
                         $upload_pic = $conf['rootpath']."images/news/";

                         // Xoa anh cu cua News
                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/".$dir3."/".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/".$dir3."/".$picture);

                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/".$dir3."/thumb_".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/".$dir3."/thumb_".$picture);

                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/".$dir3."/icon_".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/".$dir3."/icon_".$picture);

                         // Kiem tra va tao thu muc upload anh moi
                         if (!is_dir($upload_pic.$dir1)) {
                              mkdir($upload_pic.$dir1,0777);
                              $handle = fopen($upload_pic.$dir1."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2)) {
                              mkdir($upload_pic.$dir1."/".$dir2,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2."/".$dir3)) {
                              mkdir($upload_pic.$dir1."/".$dir2."/".$dir3,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/".$dir3."/index.html", "w");
                              fwrite($handle,"<meta HTTP-EQUIV=\"refresh\" Content=\"0; url=http://saomoi.vn\">");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         // Het phan kiem tra thu muc va tao thu muc chua anh moi
                         $up['path']= $conf['rootpath'];
                         $up['dir'] = "images/news/".$dir1."/".$dir2."/".$dir3;
                         $up['file']= $_FILES['image'];
                         $up['type']= "hinh";
                         $up['resize']= 0;
                         $up['thum']= 1;
                         $up['w']= 128; //Chieu ngang toi da cua anh dai dien thumbnail
                         $up['ato']= 248; //Chieu ngang toi da cua anh to
                         $up['ico']= 80; //Chieu ngang toi da cua anh icon
                         $up['timepost']= time(); //Lay thoi gian luc dang bai
                         $result = $func->Upload($up);
                         if (empty($result['err'])) {
                             $data['n_image']=$result['link'];
                             $data['t_image']=$result['type'];
                         } else {
                             $data['err'] = $result['err'];
                         }
                         // Update vao CSDL neu co PIC moi
                         if (empty($data['err'])){
                             $query ="UPDATE ".$conf['perfix']."news SET catalogid='{$data['catalog']}', eventid='{$data['event']}', title='{$data['n_title']}', short='{$data['n_short']}', content='{$data['n_content']}', picture='{$data['n_image']}', pic_type='{$data['t_image']}', pic_des='{$data['n_picdes']}', source='{$data['n_source']}', tag='{$data['n_tag']}',tag1='{$data['n_tag1']}',tag2='{$data['n_tag2']}', tag3='{$data['n_tag3']}', isdisplay='{$data['display']}' WHERE newsid='{$data['n_id']}'";
                             $update_q = $DB->query($query);
                             $mess = "Upload New Pic & Edit News Successfull !";
                         }
                     } else {
                        // Update vao CSDL neu khong Upload New Pic
                        if (empty($data['err'])){
                            $query ="UPDATE ".$conf['perfix']."news SET catalogid='{$data['catalog']}', eventid='{$data['event']}', title='{$data['n_title']}', short='{$data['n_short']}', content='{$data['n_content']}', pic_des='{$data['n_picdes']}', source='{$data['n_source']}', tag='{$data['n_tag']}',tag1='{$data['n_tag1']}',tag2='{$data['n_tag2']}', tag3='{$data['n_tag3']}', isdisplay='{$data['display']}' WHERE newsid='{$data['n_id']}'";
                            $update_q = $DB->query($query);
                            $mess = "No Upload New Pic & Edit News Successfull !";
                        }
                     }// End check Upload New Pic
                     $url = "index.php?act=news&sub=manage";
                     $this->output .= $this->html_ann($url,$mess);
                } else { // Het Update Edit News
                     // Neu la Admin thi khong can check user_id cho kiem duyet het toan bo
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE newsid='{$data['n_id']}'");
                     if ($news=$DB->fetch_row($query)){
                         $news['f_tittle'] = "Edit News";
                         $news['err'] = $data['err'];
                         $news['adddateold'] = $news['adddate'];
                         if (!empty($news['picture'])){
                              if ( (!strstr($news['picture'],"http://"))){
                                     $tmp = explode ("-",$news['adddateold']);
                                     $pic_folder = $tmp[0]."/".$tmp[1]."/".$tmp[2];
                                     $src = $conf['rooturl']."images/news/".$pic_folder."/thumb_".$news['picture'];
                              } else
                                     $src = $news['picture'];
                              $news['pic'] = "<img src=\"{$src}\" border=\"0\"><br>";
                         } else $news['pic'] = "";
                         // Chuyen doi ngay sang d/m/Y
                         $news['adddate'] = $func->makedate($news["adddate"]);
                         if ($news['isdisplay']=="0"){
                             $news['dis_option']='<option value=0 selected> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 > Bài viết </option>';
							 $news['dis_option'].='<option value=2 > Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 > Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4 > Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5 > Top Stress </option>';
                         }
						 if ($news['isdisplay']=="1"){
                             $news['dis_option']='<option value=0> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 selected> Bài viết </option>';
							 $news['dis_option'].='<option value=2 > Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 > Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4 > Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5 > Top Stress </option>';
                         } 
						 if ($news['isdisplay']=="2"){
                             $news['dis_option']='<option value=0> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 > Bài viết </option>';
							 $news['dis_option'].='<option value=2 selected> Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 > Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4 > Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5 > Top Stress </option>';
                         } 
						 if ($news['isdisplay']=="3"){
                             $news['dis_option']='<option value=0> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 > Bài viết </option>';
							 $news['dis_option'].='<option value=2 > Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 selected> Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4 > Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5 > Top Stress </option>';
                         } 
						 if ($news['isdisplay']=="4"){
                             $news['dis_option']='<option value=0> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 > Bài viết </option>';
							 $news['dis_option'].='<option value=2 > Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 > Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4 selected> Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5 > Top Stress </option>';
                         } 
						 if ($news['isdisplay']=="5"){
                             $news['dis_option']='<option value=0> Không hiển thị </option>';
                             $news['dis_option'].='<option value=1 > Bài viết </option>';
							 $news['dis_option'].='<option value=2 > Top Hot-Sock </option>';
							 $news['dis_option'].='<option value=3 > Top Teen-Sao </option>';
							 $news['dis_option'].='<option value=4> Top Video-Pictures </option>';
							 $news['dis_option'].='<option value=5  selected> Top Stress </option>';
                         } 
                         // Lay Event For News
                         $eventidn = $news["eventid"];
                         $querye = $DB->query("SELECT eventid,eventtitle FROM ".$conf['perfix']."events ORDER BY eventpost DESC");
                         $list = "";
                         while ($event = $DB->fetch_row($querye)){
                              if ($event["eventid"]==$eventidn){
                                  $list .= "<option value=\"{$event["eventid"]}\" selected> - {$event["eventtitle"]}</option>";
                              } else {
                                  $list .= "<option value=\"{$event["eventid"]}\"> - {$event["eventtitle"]}</option>";
                              }
                         }
                         $news['listevent'] = $list;
                         $news['listcat']=$this->Get_Cat($news['catalogid']);
                         $this->output .= $this->html_edit($news);
                     } else {
                     $mess = "Your can not Edit News";
                     $url = "index.php?act=news&sub=manage";
                     $this->output .= $this->html_ann($url,$mess);
                     }
                }
       }

       function do_Del(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $del=0; $qr="";
                if ($id!=0) {
                    $del=1;
                    $qr = " OR newsid='{$id}' ";
                }
                if (isset($_POST["delnews"])) $key=$_POST["delnews"] ;
                for ($i=0;$i<count($key);$i++) {
                     $del=1;
                     $qr .= " OR newsid='{$key[$i]}' ";
                }
                if ($del) {
                    // Begin Del Image
                    $query = $DB->query("SELECT picture,adddate,user_id FROM ".$conf['perfix']."news WHERE newsid=-1".$qr);
                    while ($img=$DB->fetch_row($query)) {
                           // Cap nhat Num_post cho Username post News bi xoa
                           $savebv  = $DB->query("UPDATE ".$conf['perfix']."user SET num_post=num_post-1 WHERE user_id=".$img['user_id']);

                           if (!empty($img['picture'])){
                               // Del Pic in Local Only he he
                               if ( (!strstr($img['picture'],"http://"))){
                                    // Lay thu muc chua anh News
                                    $adddate = $img['adddate'];
                                    $tmp = explode("-",$adddate);
                                    $dir1 = $tmp[0];
                                    $dir2 = $tmp[1];
                                    $dir3 = $tmp[2];
                                    $upload_pic = $conf['rootpath']."images/news/";
                                    $fname0 = $upload_pic.$dir1."/".$dir2."/".$dir3."/".$img['picture'];
                                    $fname1 = $upload_pic.$dir1."/".$dir2."/".$dir3."/thumb_".$img['picture'];
                                    $fname2 = $upload_pic.$dir1."/".$dir2."/".$dir3."/icon_".$img['picture'];
                                    @unlink($fname0);
                                    @unlink($fname1);
                                    @unlink($fname2);
                                 }
                           } // Het kiem tra co PIC hay khong
                    }
                    // End del image
                    $query = "DELETE FROM ".$conf['perfix']."news WHERE newsid=-1".$qr;
                    if ($ok = $DB->query($query)){
                        $DB->query("DELETE FROM ".$conf['perfix']."focus WHERE newsid=-1".$qr);
                        $mess = "Delete News successfull";
                    } else
                        $mess = "News not found !";
                    $url = "index.php?act=news&sub=manage";
                    $this->output .= $this->html_ann($url,$mess);
                } else $this->do_Manage();
       }

       function do_Manage(){
                global $func,$DB,$conf;
                $n =$conf["record"];
                if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
                if ((isset($_GET['cat_id'])) && (is_numeric($_GET['cat_id']))) $cat_id=$_GET['cat_id'];
                if ((isset($_POST['catalog'])) ) $cat_id=$_POST['catalog'];
                if (isset($_GET['user'])) $user=$_GET['user'];
                if (isset($_POST['user'])) $user=$_POST['user'];
                if (isset($_GET['list_id'])) $list_id=$_GET['list_id'];
                if (isset($_POST['list_title'])) $list_id=$_POST['list_title'];
                if (isset($_GET['date'])) $date=$_GET['date'];
                if (isset($_POST['txtdate'])) $date=$_POST['txtdate'];
                if (isset($_GET['n'])) $n=$_GET['n'];
                if (isset($_POST['list_view'])) $n=$_POST['list_view'];
                $where = " where newsid <> 0 " ;
                $ext ="";
                if(!empty($cat_id)){
                    $a_cat_id = $this->List_SubCat($cat_id);
                    $a_cat_id = str_replace(",","','",$a_cat_id);
                    $where .=" and catalogid in ('$cat_id','".$a_cat_id."') ";
                    $ext.="&cat_id=".$cat_id;
                }
                if(!empty($user)){
                    $query = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE username like '%".$user."%' ");
                    $mem = $DB->fetch_row($query);
                    $a_userid .=$mem["user_id"];
                    $where .=" and user_id='".$a_userid."' ";
                    $ext.="&user=".$user;
                }
                if(!empty($list_id)){
                    $where .=" and title like '".$list_id."%' ";
                    $ext.="&list_id=".$list_id;
                }
                if(!empty($date)){
                    $adddate=$func->makedatetoMySQL($date);
                    $where .=" and adddate ='$adddate' ";
                    $ext.="&date=".$date;
                }
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."news $where and isdisplay !=0 ");
                $totals_news = $DB->num_rows($query);
                if(!empty($n)){
                    $ext.="&n=".$n;
                }
                $num_pages = ceil($totals_news/$n) ;
                if ($p > $num_pages) $p=$num_pages;
                if ($p < 1 ) $p=1;
                $start = ($p-1) * $n ;
                $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
                for ($i=1; $i<$num_pages+1; $i++ ) {
                     if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                     else $nav.="[<a href='index.php?act=news&sub=manage{$ext}&p={$i}'>$i</a>] ";
                }
                $nav .= "</div></center>";
                $list = "";
                $stt=0;
                $sql= "SELECT * FROM ".$conf['perfix']."news $where and isdisplay !=0 ORDER BY newsid DESC LIMIT $start,$n";
                $query = $DB->query($sql);
                while ($news=$DB->fetch_row($query)) {
                       if (empty($news['picture'])) $news['picture'] = "<i>No Image</i>";
                       else {
                            if ( (!strstr($news['picture'],"http://"))){
                                  $tmp = explode("-",$news['adddate']);
                                  $folder = $conf['rooturl']."images/news/".$tmp[0]."/".$tmp[1]."/".$tmp[2]."/";
                                  $src = $folder.$news['picture'];
                            } else  $src = $news['picture'];
                            $des = $news['pic_des'];
                            $news['picture']="<img onclick=\"javascript: popupImage('{$src}','','{$des}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                       }
                       $news['stt'] = $stt;
                       $user_q = $DB->query ("select * from ".$conf['perfix']."user where user_id = ".$news["user_id"]);
                       if ($row_user = $DB->fetch_row($user_q)){
                           $news["user"] = $row_user["username"];
                       }
                       $news["adddate"] = $func->makedate($news["adddate"]);
                       $list .= $this->html_row($news);
                       $stt++;
                }
                $nd['tittle'] = "Manage News";
                $nd['nd'] = $list;
                $nd['num'] = $stt+2;
                $nd['listcat']=$this->Get_Cat($cat_id);
                $nd["list_title"] = $this->List_Tile($list_id) ;
                $nd['date']=$date;
                $nd['user']=$user;
                $nd["list_view"] = $this->List_View($n);
                $this->output .= $this->html_nav($nd);
                $this->output .= $nav."<br>";
       }

       function html_add($data){
                return<<<EOF
                        <script language=javascript>
                                function checkform(f) {
                                         var cat = f.catalog.value;
                                         if (cat == '') {
                                             alert('Plz Chose Catalog');
                                             return false;
                                         }
                                         var title = f.title.value;
                                         if (title == '') {
                                             alert('Plz enter Title');
                                             f.title.focus();
                                             return false;
                                         }
                                         var short = f.short.value;
                                         if (short == '') {
                                             alert('Plz enter Short Description');
                                             f.short.focus();
                                             return false;
                                         }
                                         var content = f.content.value;
                                         if (content == ''){
                                             alert("Plz enter Content");
                                             f.content.focus();
                                             return false;
                                         }
                                return true;
                        }
                </script>
                <script language="Javascript1.2">
                        <!-- // load htmlarea
                        _editor_url = "../htmlarea/"; // URL to htmlarea files
                        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
                        if (navigator.userAgent.indexOf('Mac') >= 0) { win_ie_ver = 0; }
                        if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
                        if (navigator.userAgent.indexOf('Opera') >= 0) { win_ie_ver = 0; }
                        if (win_ie_ver >= 5.5) {
                            document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
                            document.write(' language="Javascript1.2"></scr' + 'ipt>');
                        } else {
                            document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
                        // -->
                </script>
                <br><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                             <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                             <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['f_title']}</td>
                             <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                          </tr>
                       </table></td>
                    </tr>
                    <tr>
                       <td bgcolor="#FFFFFF" class="main_table" align=center>
                       <form action="index.php?act=news&sub=add" method="post" enctype="multipart/form-data" name="news"  onSubmit="return checkform(this);">
                          <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                             <tr>
                                 <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                             </tr>
                             <tr>
                                 <td align="right">Cataloge : </td>
                                 <td align="left">{$data['listcat']}</td>
                             </tr>
                             <tr>
                                 <td align="right">Event : </td>
                                 <td align="left"><select name="event" style="width:448px"><option value="0"> -- Select Event ------------------------------------------------------------------------------------</option>{$data['listevent']}</select></td>
                             </tr>
                             <tr>
                                 <td width="15%" align="right">Title : </td>
                                 <td width="85%" align="left"><input name="title" type="text" id="title" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Short Description : </td>
                                 <td align="left"><textarea name="short" cols="60" rows="5" id="short" style="width:445px"></textarea></td>
                             </tr>
                             <tr>
                                 <td align="right">Content : </td>
                                 <td align="left"><textarea name="content" cols="60" rows="5" id="content" style="width:425px"></textarea></td>
                             </tr>
							 <tr>
                                 <td width="15%" align="right">Tag : </td>
                                 <td width="85%" align="left"><input name="tag" type="text" id="tag" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Image : </td>
                                 <td align="left"><input name="image" type="file" id="image" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Image Description : </td>
                                 <td align="left"><input name="picdes" type="text" id="picdes" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Source : </td>
                                 <td align="left"><input name="source" type="text" id="source" size="50" maxlength="250" style="width:200px"> (VNN, VNE, VFF, D&#226;n Tr&#237;, Tu&#7893;i Tr&#7867;, Thanh Ni&#234;n...)</td>
                             </tr>
                             <tr>
                                 <td align="right">Display : </td>
                                 <td align="left"><select name="display">
                                     <option value="1" selected>Bài viết</option>
                                     
									 <option value="2">Top Hot-Sock</option>
                                     <option value="3">Top Teen-Sao</option>
									 <option value="4">Top Video-Pictures</option>
									 <option value="5">Top Stress</option>
									 <option value="0">Không hiển thị</option>
                                 </select></td>
                             </tr>
                             <tr>
                                 <td colspan="2">
                                    <script language="JavaScript1.2" defer>
                                            var config = new Object();
                                            config.width = "450px";
                                            config.height = "200px";
                                            editor_generate('content',config);
                                    </script>
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
                                          var cat = f.catalog.value;
                                          if (cat == '') {
                                               alert('Plz Chose Catalog');
                                               return false;
                                          }
                                          var title = f.title.value;
                                          if (title == '') {
                                               alert('Plz enter Title');
                                               f.title.focus();
                                               return false;
                                          }
                                          var content = f.content.value;
                                          if (content == ''){
                                               alert("Plz enter Content");
                                               f.content.focus();
                                               return false;
                                          }
                                          return true;
                                 }
                         </script>
                         <script language="Javascript1.2">
                                 <!-- // load htmlarea
                                 _editor_url = "../htmlarea/";  // URL to htmlarea files
                                 var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
                                 if (navigator.userAgent.indexOf('Mac') >= 0) { win_ie_ver = 0; }
                                 if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
                                 if (navigator.userAgent.indexOf('Opera') >= 0) { win_ie_ver = 0; }
                                 if (win_ie_ver >= 5.5) {
                                     document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
                                     document.write(' language="Javascript1.2"></scr' + 'ipt>');
                                 } else {
                                     document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
                                 // -->
                         </script>
                         <br><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['f_tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                   </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                <form action="index.php?act=news&sub=edit" method="post" enctype="multipart/form-data" name="news" onSubmit="return checkform(this);">
                                   <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                      <tr>
                                          <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Cataloge : </td>
                                          <td align="left">{$data['listcat']}</td>
                                      </tr>
                                      <tr>
                                          <td align="right">Event : </td>
                                          <td align="left"><select name="event" style="width:448px"><option value="0"> -- Select Event ------------------------------------------------------------------------------------</option>{$data['listevent']}</select></td>
                                      </tr>
                                      <tr>
                                          <td width="15%" align="right">Title : </td>
                                          <td width="85%" align="left"><input name="title" type="text" id="title" size="80" maxlength="250" value="{$data['title']}" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Short Description : </td>
                                          <td align="left"><textarea name="short" cols="50" rows="5" id="short" style="width:445px">{$data['short']}</textarea></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Content : </td>
                                          <td align="left"><textarea name="content" cols="50" rows="5" id="content" style="width:425px">{$data['content']}</textarea></td>
                                      </tr>
									  <tr>
                                          <td width="15%" align="right">Tag : </td>
                                          <td width="85%" align="left"><input name="tag" type="text" id="tag" size="80" maxlength="250" value="{$data['tag']}" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Image : </td>
                                          <td align="left">{$data['pic']}<input name="image" type="file" id="image" size="40" maxlength="250" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Image Description : </td>
                                          <td align="left"><input name="picdes" type="text" id="picdes" size="40" maxlength="250" value="{$data['pic_des']}" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Source : </td>
                                          <td align="left"><input name="source" type="text" id="source" size="50" maxlength="250" value="{$data['source']}" style="width:200px"> (VNN, VNE, VFF, D&#226;n Tr&#237;, Tu&#7893;i Tr&#7867;, Thanh Ni&#234;n...)</td>
                                      </tr>
                                      <tr>
                                          <td align="right">Display : <input name="adddateold" type="hidden" id="adddateold" value="{$data['adddateold']}"></td>
                                          <td align="left"><select name="display">{$data['dis_option']}</select>&nbsp;&nbsp;&nbsp;&nbsp;Update Day : &nbsp;&nbsp;&nbsp;&nbsp;{$data['adddate']}</td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              <script language="JavaScript1.2" defer>
                                                      var config = new Object();
                                                      config.width = "450px";
                                                      config.height = "200px";
                                                      editor_generate('content',config);
                                              </script>
                                              <input name="n_id" type="hidden" value="{$data['newsid']}">
                                              <input type="submit" name="btnEdit" value="Submit">
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

        function html_nav($data){
                 return<<<EOF
                          <script language="javascript1.2">
                                  function checkall(num){
                                           for ( i=0;i < document.manage.elements.length ; i++ ){
                                                 if ( document.manage.all.checked==true ){
                                                      document.manage.elements[i].checked = true;
                                                 } else {
                                                      document.manage.elements[i].checked  = false;
                                                 }
                                           }
                                  }

                                  var ppimgNW;
                                  function popupImage(src, note, title, css, border) {
                                           if (border==null) border = 0;
                                           if (note==null) note = '';
                                           if (ppimgNW != null) ppimgNW.close();

                                           ppimgNW = window.open('','POPUPIMAGE','width=1,height=1');
                                           var doc = ppimgNW.document;
                                           doc.write('<html>');
                                           doc.write('<head>');

                                           if (title!=null) doc.write('<title>'+ title +'</title>');
                                           doc.write('<style> body {'+css+'} #ppImgText{'+ css +'} #ppImg{cursor:hand}</style></head>');
                                           doc.write('<body leftmargin="0" topmargin="' + border + '" onload="doResize();">');
                                           doc.write('<div align="center">');
                                           doc.write('<img src="' + src + '" id="ppImg" onclick="self.close();" title="Close">');
                                           doc.write('</div>');
                                           doc.write('<div style="height:1; width:' + border + '; font-size:4pt;">');
                                           doc.write('</div>');
                                           doc.write('<div id="ppImgText" align="center">');
                                           doc.write(note);
                                           doc.write('</div>');
                                           doc.write('</body>');
                                           doc.write('</html>');

                                           doc.write('<' + 'script>');
                                           doc.write('function doResize() {');
                                           doc.write('  try { var imgW = ppImg.width, imgH = ppImg.height;');
                                           doc.write('  window.resizeTo(imgW + 8 +' + border*2 +', imgH + ppImgText.offsetHeight + 26 + '+ border*2 +');');
                                           doc.write('  setTimeout(\'doResize()\', 1000); } catch (ex) {} ');
                                           doc.write('}');
                                           doc.write('doResize(); ');
                                           doc.write('</' + 'script>');
                                         }
                          </script>
                          <SCRIPT language=javascript src="../js/Datetime.js" type=text/javascript></SCRIPT>
                          <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <form action="index.php?act=news&sub=manage" method="post" name="catform">
                                 <tr>
                                    <td><strong>Cataloge : </strong>{$data['listcat']}</td>
                                 </tr>
                                 <tr>
                                    <td height="30"><strong>Title Begin</strong> :{$data['list_title']} &nbsp; <strong>User Post</strong> : <input type="text" name="user" size="15" maxlength="15" value="{$data['user']}"> &nbsp; <strong>Date Post</strong> :
                                        <input type="text" name="txtdate" size="12" maxlength="10" onClick="javascript:fPopCalendar(txtdate,txtdate)" value="{$data['date']}"> &nbsp; View : {$data['list_view']} news/page
                                         <input name="btnGo" type="submit" value=" Go >> ">
                                    </td>
                                 </tr>
                                 <tr><td>&nbsp;</td></tr>
                              </form>
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
                                    <form action="index.php?act=news&sub=del" method="post" name="manage" id="manage">
                                       <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                          <tr>
                                             <td width="5%" class="row_tittle">Delete</td>
                                             <td width="50%" class="row_tittle">Tittle</td>
                                             <td width="15%" class="row_tittle">Poster</td>
                                             <td width="10%" class="row_tittle">Date</td>
                                             <td width="6%" class="row_tittle">Hits</td>
                                             <td width="6%" class="row_tittle">Image</td>
                                             <td width="8%" class="row_tittle">Actions</td>
                                          </tr>
                                          {$data['nd']}
                                          <tr>
                                             <td width="5%" class="row_tittle"><input type="checkbox" name="all" onClick="javascript:checkall({$data['num']});"></td>
                                             <td colspan=6 class="row_tittle" align=left><input type="submit" name="Submit" value="Delete seleted News"></td>
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

        function html_row($data){
                 return<<<EOF
                          <tr>
                             <td class="row1"><input name="delnews[]" type="checkbox" value="{$data['newsid']}"></td>
                             <td class="row" align="left" style="padding-left:10px"><a href="?act=news&sub=edit&id={$data['newsid']}">{$data['title']}</a></td>
                             <td class="row" align="center">{$data['user']}</td>
                             <td class="row" align="center">{$data['adddate']}</td>
                             <td class="row" align="center">{$data['viewnum']}</td>
                             <td class="row">{$data['picture']}</td>
                             <td class="row">
                                 <a href="?act=news&sub=edit&id={$data['newsid']}"><img src="images/edit.gif" width="22" height="22" alt="Edit News"></a>&nbsp;
                                 <a href="?act=news&sub=del&id={$data['newsid']}"><img src="images/delete.gif" width="22" height="22" alt="Delete News"></a>
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
                            <table width="60%"border="0" align="center" cellpadding="0" cellspacing="0">
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
                                          <td width="100%" height="40" align="center"><font color="red">{$mess}</font></td>
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
} // Het Class
?>