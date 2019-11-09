<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

class DB {
      var $connection="";
      var $query_id="";
      function connect(){
               global $conf;
               $this->sql_host= $conf['host'];
               $this->sql_user= $conf['dbuser'];
               $this->sql_pass= $conf['dbpass'];
               $this->sql_dbname= $conf['dbname'];
               $this->connection = @mysql_connect($this->sql_host,$this->sql_user,$this->sql_pass);
               if(!$this->connection){
                   print "L&#7895;i k&#7871;t n&#7889;i c&#417; s&#7903; d&#7919; li&#7879;u";
                   exit;
               }
               if ( !mysql_select_db($this->sql_dbname, $this->connection) ){
                    echo ("Khong the doc duoc CSDL");
               }
       }

       function query($the_query){
                $this->query_id = @mysql_query($the_query, $this->connection);
                return $this->query_id;
       }

       function fetch_row($query_id = "") {
                if ($query_id == ""){
                    $query_id = $this->query_id;
                }
                $record_row = @mysql_fetch_array($query_id, MYSQL_ASSOC);
                return $record_row;
       }

       function update_string($data) {
                $return_string = "";
                foreach ($data as $k => $v){
                         $return_string .= $k . "='".$v."',";
                }
                $return_string = preg_replace( "/,$/" , "" , $return_string );
                return $return_string;
       }

       function num_rows($query_id = "") {
                if ($query_id == ""){
                    $query_id = $this->query_id;
                }
                return @mysql_num_rows($query_id);
       }

       function close(){
                mysql_close($this->connection);
       }

       function compile_db_insert_string($data) {
                $field_names  = "";
                $field_values = "";
                foreach ($data as $k => $v){
                         $v = preg_replace( "/'/", "\\'", $v );
                         $field_names  .= "$k,";
                         if ( is_numeric( $v ) and intval($v) == $v ){
                              $field_values .= $v.",";
                         } else {
                              $field_values .= "'$v',";
                         }
                }
                $field_names  = preg_replace( "/,$/" , "" , $field_names  );
                $field_values = preg_replace( "/,$/" , "" , $field_values );
                return array( 'FIELD_NAMES'  => $field_names,
                              'FIELD_VALUES' => $field_values,
                );
       }

       function compile_db_update_string($data) {
                $return_string = "";
                foreach ($data as $k => $v){
                         $v = preg_replace( "/'/", "\\'", $v );
                         if ( is_numeric( $v ) and intval($v) == $v ){
                              $return_string .= $k . "=".$v.",";
                         } else {
                              $return_string .= $k . "='".$v."',";
                         }
                }
                $return_string = preg_replace( "/,$/" , "" , $return_string );
                return $return_string;
       }

       function do_update( $tbl, $arr, $where="" ){
                $dba = $this->compile_db_update_string( $arr );
                $query = "UPDATE ".$conf['perfix']."$tbl SET $dba";
                if ( $where ){
                     $query .= " WHERE ".$where;
                }
                $ci = $this->query( $query );
                return $ci;
       }

       function do_insert( $tbl, $arr ){
                $dba = $this->compile_db_insert_string( $arr );
                $ci = $this->query("INSERT INTO ".$conf['perfix']."$tbl ({$dba['FIELD_NAMES']}) VALUES({$dba['FIELD_VALUES']})");
                return $ci;
       }

       function mySQLSafe($value, $quote="'") {
                // strip quotes if already in
                $value = str_replace(array("\'","'"),"&#39;",$value);
                // Stripslashes
                if (get_magic_quotes_gpc()) {
                    $value = stripslashes($value);
                }
                // Quote value
                if (version_compare(phpversion(),"4.3.0")=="-1") {
                    $value = mysql_escape_string($value);
                } else {
                    $value = mysql_real_escape_string($value);
                }
                $value = $quote . $value . $quote;
                return $value;
       }
}

?>
