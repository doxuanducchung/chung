<SCRIPT language=JavaScript1.2 type=text/javascript>
    var ie45,ns6,ns4,dom;
    if (navigator.appName=="Microsoft Internet Explorer") ie45=parseInt(navigator.appVersion)>=4;
    else if (navigator.appName=="Netscape"){  ns6=parseInt(navigator.appVersion)>=5;  ns4=parseInt(navigator.appVersion)<5;}
    dom=ie45 || ns6;
    but_cong = new Image(16,16);
    but_cong.src = "images/but_cong.gif";
    but_tru = new Image(16,16);
    but_tru.src = "images/but_tru.gif";

    function change_icon(imgDocID,imgObjName) {
             //document.images[imgDocID].src = eval(imgObjName + ".src") ;
             document.images[imgDocID].src = imgObjName.src;
    }

    function showhide(id) {
             el = document.all ? document.all[id] :   dom ? document.getElementById(id) :   document.layers[id];
             els = dom ? el.style : el;
             img_els = 'img_'+id;
             if (dom){
                 if (els.display == "none") {
                     els.display = "";
                     change_icon(img_els,but_tru);
                 } else {
                     els.display = "none";
                     change_icon(img_els,but_cong);
                 }
             } else if (ns4){
                        if (els.display == "show") {
                            els.display = "hide";
                            change_icon(img_els,but_tru);
                        } else {
                            els.display = "show";
                            change_icon(img_els,but_cong);
                        }
                    }
    }
  </script>
  <table width="160" border="0" cellpadding="2" cellspacing="2">
     <tr>
        <td align="center"><img src="images/admin_logo.jpg" width="149" height="117"></td>
     </tr>
     <tr>
        <td align="center"><img src="images/arrow2.gif" width="11" height="9"  border="0">&nbsp;<a href="?act=login&code=2" target="_parent"><strong>Logout</strong></a></td>
     </tr>
     <tr>
        <td align="center"></td>
     </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
              <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
              <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle><a onclick="showhide('menu_catalog');return false" href="javascript:void(0);">&nbsp;<img src="images/but_cong.gif" name="img_menu_catalog" width="10" height="10" border="0"></a>&nbsp;Thể loại </td>
              <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="main_table" align=center>
          <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center id="menu_catalog" style="display:none">
            <tr>
              <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=cataloge&sub=add" target="Main">Thêm thể loại Bài viết</a></td>
            </tr>
            <tr>
              <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=cataloge&sub=manage" target="Main">Quản lý thể loại Bài viết</a></td>
            </tr>
			
        </table></td>
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
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
           <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                  <td background="images/navtop_bg.gif" class="nav_tittle" align="left" valign="middle"><a onclick="showhide('menu_news');return false" href="javascript:void(0);">&nbsp;<img src="images/but_tru.gif" name="img_menu_news" width="10" height="10" border="0"></a>&nbsp;News </td>
                  <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
               </tr>
           </table></td>
        </tr>
        <tr>
           <td bgcolor="#FFFFFF" class="main_table" align=center>
              <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" id="menu_news" style="display:">
			 
                <tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=news&sub=add" target="Main">Cập nhật Bài viết</a></td>
                </tr>
                <tr>
                   <td align="left"><img src="images/arrow3.gif" width="4" height="6" /> <a href="?act=news&amp;sub=manage" target="Main">Quản lý Bài viết</a></td>
                </tr>
                <tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=active&sub=manage" target="Main">Hiển thị Bài viết</a></td>
                </tr>
                <tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=focus&sub=manage" target="Main">Bài viết nổi bật</a></td>
                </tr>
				
                <tr>
                  <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=newspic" target="Main">Tin Ảnh</a></td>
                </tr>
                <tr>
                  <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=event" target="Main">Sự kiện</a></td>
                </tr>
				<tr>
                  <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=comment" target="Main">Comment</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=docngay&sub=manage" target="Main">Manager Readnow</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu1&sub=manage" target="Main">Menu 1(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu2&sub=manage" target="Main">Menu 2(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu3&sub=manage" target="Main">Menu 3(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu4&sub=manage" target="Main">Menu 4(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu5&sub=manage" target="Main">Menu 5(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu6&sub=manage" target="Main">Menu 6(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu7&sub=manage" target="Main">Menu 7(Khong dung)</a></td>
                </tr>
				<tr>
                   <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=menu8&sub=manage" target="Main">Menu 8(Khong dung)</a></td>
                </tr>
           </table></td>
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
    </table></td>
  </tr>
  <tr>
    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27" /></td>
              <td background="images/navtop_bg.gif" class="nav_tittle" align="left" valign="middle"><a onclick="showhide('menu_logo');return false" href="javascript:void(0);">&nbsp;<img src="images/but_tru.gif" name="img_menu_logo" width="10" height="10" border="0"></a>&nbsp;Banners Manager</td>
              <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27" /></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" class="main_table" align="center">
          <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" id="menu_logo" style="display:">
                   <tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=logoman" target="Main">Add Banner</a></td>
            </tr>
            <tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=header1" target="Main">Banner Header 1</a></td>
            </tr>
			 <tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=header2" target="Main">Banner Header 2</a></td>
            </tr>
            
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=logoright" target="Main">Liên kết</a></td>
            </tr>

			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=trangchu1" target="Main">Trang chu 1</a></td>
            </tr>
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=trangchu2" target="Main">Trang chu 2</a></td>
            </tr>
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=trangchu3" target="Main">Trang chu 3</a></td>
            </tr>
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=trangchu4" target="Main">Trang chu 4</a></td>
            </tr>
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=newsmain1" target="Main">News Main 1</a></td>
            </tr>
			<tr>
              <td width="100%" align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=newsmain2" target="Main">News Main 2</a></td>
            </tr>
        </table></td>
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
    </table></td>
  </tr>
  <tr>
     <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                  <td background="images/navtop_bg.gif" class="nav_tittle" align="left" valign="middle"><a onclick="showhide('menu_poll');return false" href="javascript:void(0);">&nbsp;<img src="images/but_cong.gif" name="img_menu_poll" width="10" height="10" border="0"></a>&nbsp;Modules</td>
                  <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
              </tr>
           </table></td>
         </tr>
         <tr>
           <td bgcolor="#FFFFFF" class="main_table" align="center">
              <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center" id="menu_poll" style="display:none">
                <tr>
                  <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=poll&sub=add" target="Main">Post Poll</a></td>
                </tr>
                <tr>
                  <td width="100%" align="left"> <img src="images/arrow3.gif" width="4" height="6"> <a href="?act=poll" target="Main">Manage Poll</a></td>
                </tr>
                <tr>
                   <td align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=contact" target="Main">Contact</a></td>
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
      </table></td>
  </tr>
     <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                  <td background="images/navtop_bg.gif" class="nav_tittle" align="left" valign="middle"><a onclick="showhide('menu_admin');return false" href="javascript:void(0);">&nbsp;<img src="images/but_cong.gif" name="img_menu_admin" width="10" height="10" border="0"></a>&nbsp;Site Information </td>
                  <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF" class="main_table" align="center">
              <table width="100%"  border="0" cellspacing="2" cellpadding="2" align="center" id="menu_admin" style="display:none">
                <tr>
                   <td align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=member" target="Main">User Manager</a></td>
                </tr>
                <tr>
                   <td align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=config" target="Main">Configure Information</a></td>
                </tr>
                <tr>
                   <td align="left"><img src="images/arrow3.gif" width="4" height="6"> <a href="?act=statistic" target="Main">Statistic</a></td>
                </tr>
            </table></td>
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
        </table></td>
  </tr>
</table><br>