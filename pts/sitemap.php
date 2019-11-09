<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
$sql_host = "localhost";
$sql_user = "root";
$sql_pass = "vertrigo";
$sql_database = "project";
$linkforum="http://huyson.net/pts/";

$conf['seo_link']						= 'yes';
      function mahoa($value){
               #---------------------------------a
               $value = str_replace("á", "a", $value);
               $value = str_replace("à", "a", $value);
               $value = str_replace("ả", "a", $value);
               $value = str_replace("ã", "a", $value);
               $value = str_replace("ạ", "a", $value);
               $value = str_replace("â", "a", $value);
               $value = str_replace("ă", "a", $value);
               #---------------------------------A
               $value = str_replace("Á", "a", $value);
               $value = str_replace("À", "a", $value);
               $value = str_replace("Ả", "a", $value);
               $value = str_replace("Ã", "a", $value);
               $value = str_replace("Ạ", "a", $value);
               $value = str_replace("Â", "a", $value);
               $value = str_replace("Ă", "a", $value);
               #---------------------------------a^
               $value = str_replace("ấ", "a", $value);
               $value = str_replace("ầ", "a", $value);
               $value = str_replace("ẩ", "a", $value);
               $value = str_replace("ẫ", "a", $value);
               $value = str_replace("ậ", "a", $value);
               #---------------------------------A^
               $value = str_replace("Ấ", "a", $value);
               $value = str_replace("Ầ", "a", $value);
               $value = str_replace("Ẩ", "a", $value);
               $value = str_replace("Ẫ", "a", $value);
               $value = str_replace("Ậ", "a", $value);
               #---------------------------------a(
               $value = str_replace("ắ", "a", $value);
               $value = str_replace("ằ", "a", $value);
               $value = str_replace("ẳ", "a", $value);
               $value = str_replace("ẵ", "a", $value);
               $value = str_replace("ặ", "a", $value);
               #---------------------------------A(
               $value = str_replace("Ắ", "a", $value);
               $value = str_replace("Ằ", "a", $value);
               $value = str_replace("Ẳ", "a", $value);
               $value = str_replace("Ẵ", "a", $value);
               $value = str_replace("Ặ", "a", $value);
               #---------------------------------e
               $value = str_replace("é", "e", $value);
               $value = str_replace("è", "e", $value);
               $value = str_replace("ẻ", "e", $value);
               $value = str_replace("ẽ", "e", $value);
               $value = str_replace("ẹ", "e", $value);
               $value = str_replace("ê", "e", $value);
               #---------------------------------E
               $value = str_replace("É", "e", $value);
               $value = str_replace("È", "e", $value);
               $value = str_replace("Ẻ", "e", $value);
               $value = str_replace("Ẽ", "e", $value);
               $value = str_replace("Ẹ", "e", $value);
               $value = str_replace("Ê", "e", $value);
               #---------------------------------e^
               $value = str_replace("ế", "e", $value);
               $value = str_replace("ề", "e", $value);
               $value = str_replace("ể", "e", $value);
               $value = str_replace("ễ", "e", $value);
               $value = str_replace("ệ", "e", $value);
               #---------------------------------E^
               $value = str_replace("Ế", "e", $value);
               $value = str_replace("Ề", "e", $value);
               $value = str_replace("Ể", "e", $value);
               $value = str_replace("Ễ", "e", $value);
               $value = str_replace("Ệ", "e", $value);
               #---------------------------------i
               $value = str_replace("í", "i", $value);
               $value = str_replace("ì", "i", $value);
               $value = str_replace("ỉ", "i", $value);
               $value = str_replace("ĩ", "i", $value);
               $value = str_replace("ị", "i", $value);
               #---------------------------------I
               $value = str_replace("Í", "i", $value);
               $value = str_replace("Ì", "i", $value);
               $value = str_replace("Ỉ", "i", $value);
               $value = str_replace("Ĩ", "i", $value);
               $value = str_replace("Ị", "i", $value);
               #---------------------------------o^
               $value = str_replace("ố", "o", $value);
               $value = str_replace("ồ", "o", $value);
               $value = str_replace("ổ", "o", $value);
               $value = str_replace("ỗ", "o", $value);
               $value = str_replace("ộ", "o", $value);
               #---------------------------------O^
               $value = str_replace("Ố", "o", $value);
               $value = str_replace("Ồ", "o", $value);
               $value = str_replace("Ổ", "o", $value);
               $value = str_replace("Ô", "o", $value);
               $value = str_replace("Ộ", "o", $value);
               #---------------------------------o*
               $value = str_replace("ớ", "o", $value);
               $value = str_replace("ờ", "o", $value);
               $value = str_replace("ở", "o", $value);
               $value = str_replace("ỡ", "o", $value);
               $value = str_replace("ợ", "o", $value);
               #---------------------------------O*
               $value = str_replace("Ớ", "o", $value);
               $value = str_replace("Ờ", "o", $value);
               $value = str_replace("Ở", "o", $value);
               $value = str_replace("Ỡ", "o", $value);
               $value = str_replace("Ợ", "o", $value);
               #---------------------------------u*
               $value = str_replace("ứ", "u", $value);
               $value = str_replace("ừ", "u", $value);
               $value = str_replace("ử", "u", $value);
               $value = str_replace("ữ", "u", $value);
               $value = str_replace("ự", "u", $value);
               #---------------------------------U*
               $value = str_replace("Ứ", "u", $value);
               $value = str_replace("Ừ", "u", $value);
               $value = str_replace("Ử", "u", $value);
               $value = str_replace("Ữ", "u", $value);
               $value = str_replace("Ự", "u", $value);
               #---------------------------------y
               $value = str_replace("ý", "y", $value);
               $value = str_replace("ỳ", "y", $value);
               $value = str_replace("ỷ", "y", $value);
               $value = str_replace("ỹ", "y", $value);
               $value = str_replace("ỵ", "y", $value);
               #---------------------------------Y
               $value = str_replace("Ý", "y", $value);
               $value = str_replace("Ỳ", "y", $value);
               $value = str_replace("Ỷ", "y", $value);
               $value = str_replace("Ỹ", "y", $value);
               $value = str_replace("Ỵ", "y", $value);
               #---------------------------------DD
               $value = str_replace("Đ", "d", $value);
               $value = str_replace("Đ", "d", $value);
               $value = str_replace("đ", "d", $value);
               #---------------------------------o
               $value = str_replace("ó", "o", $value);
               $value = str_replace("ò", "o", $value);
               $value = str_replace("ỏ", "o", $value);
               $value = str_replace("õ", "o", $value);
               $value = str_replace("ọ", "o", $value);
               $value = str_replace("ô", "o", $value);
               $value = str_replace("ơ", "o", $value);
               #---------------------------------O
               $value = str_replace("Ó", "o", $value);
               $value = str_replace("Ò", "o", $value);
               $value = str_replace("Ỏ", "o", $value);
               $value = str_replace("Õ", "o", $value);
               $value = str_replace("Ọ", "o", $value);
               $value = str_replace("Ô", "o", $value);
               $value = str_replace("Ơ", "o", $value);
               #---------------------------------u
               $value = str_replace("ú", "u", $value);
               $value = str_replace("ù", "u", $value);
               $value = str_replace("ủ", "u", $value);
               $value = str_replace("ũ", "u", $value);
               $value = str_replace("ụ", "u", $value);
               $value = str_replace("ư", "u", $value);
               #---------------------------------U
               $value = str_replace("Ú", "u", $value);
               $value = str_replace("Ù", "u", $value);
               $value = str_replace("Ủ", "u", $value);
               $value = str_replace("Ũ", "u", $value);
               $value = str_replace("Ụ", "u", $value);
               $value = str_replace("Ư", "u", $value);
               #---------------------------------
               $value = str_replace("."," ", $value);
               $value = str_replace(","," ", $value);
               $value = str_replace("!"," ", $value);
               $value = str_replace("?"," ", $value);
               $value = str_replace(":", " ", $value);
               $value = str_replace("'", " ", $value);
               $value = str_replace("&#039;", " ", $value);
               $value = str_replace("&quot;", " ", $value);
               $value = str_replace("&amp;","va", $value);
               $value = str_replace("(", " ", $value);
               $value = str_replace(")", " ", $value);
               $value = str_replace("-", " ", $value);
               $value = str_replace("   ", " ", $value);
               $value = str_replace("  ", " ", $value);
               return strtolower(str_replace(" ","-",trim($value)));
      }
	  

// Ket noi sql
$fp = fopen("sitemap.xml", "w+");
$conn = mysql_connect($sql_host, $sql_user, $sql_pass);
if ( !$conn ) {
    //Không ket noi duoc, thoat ra va bao lai
    die("khong noi duoc vao MySQL server: ".mysql_error($conn));
} //end if
// Thong tin xml
$header_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<rss version=\"2.0\">\n<channel>\n<title>.:: Photoshop Online|Photoshop effects|Pree photoshop | Text effects|Learning photoshop|Download photo|Web graphics ::. ".$linkforum."</title>\n<description>Powered by Mr Bean http://huyson.net</description>\n<link>".$linkforum."</link>\n<ttl>1</ttl>\n";
fwrite($fp, $header_xml);
//chon CSDL lam viec
mysql_select_db($sql_database, $conn)
    or die("Không chon duoc CSDL: ".mysql_error($conn));

$sql = "SELECT * FROM pts_catalog";
$result = mysql_query($sql, $conn);
if ( !$result )
    die("Khong the thuc hien duoc cau lenh SQL: ".mysql_error($conn));

while ( $row = mysql_fetch_row($result) ) {
	
	
	
    fwrite($fp, "<item><title>".$row[1]."</title><link>".$linkforum."tutorials-category/".mahoa($row[1])."/".$row[0]."/</link></item>\n");
    
} //end while
//nen luon giai phong sau khi lay het cac row tra ve tu cau lenh SELECT
mysql_free_result($result);

$sql = "SELECT * FROM pts_news where isdisplay!=0";
$result = mysql_query($sql, $conn);
if ( !$result )
    die("Khong the thuc hien duoc cau lenh SQL: ".mysql_error($conn));

while ( $row = mysql_fetch_row($result) ) {
	
    fwrite($fp, "<item><title>".$row[3]."</title><link>".$linkforum."tutorials-detail/".$row[0]."/".mahoa($row[3]).".html</link></item>\n");
} //end while
//nen luon giai phong sau khi lay het cac row tra ve tu cau lenh SELECT
mysql_free_result($result);


// Ghi footer
$footer_xml = "<item><title>.:: Photoshop Online|Photoshop effects|Pree photoshop | Text effects|Learning photoshop|Download photo|Web graphics ::.</title><link>http://huyson.net</link></item>\n</channel>\n</rss>";
fwrite($fp,$footer_xml);
fclose($fp);
//dóng k?t n?i
mysql_close($conn);
echo "Xong !! Xem ket qua nao  <a href=sitemap.xml>sitemap.xml</a>";
      
?>