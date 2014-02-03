<?php

header("content-type:text/html; charset=utf-8");

$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
mysql_select_db("app_hearthstone", $conn); 

$sql = "select * from cards where ch_name like '%火%' or en_name like '%火%' or skill like '%或%' or description like '%火%'";

$result = mysql_query($sql,$conn); 


var_dump($result);

if (count($result)>0){

	while( $row = mysql_fetch_array($result) ){
		echo "用户名:".$row['en_name']."<br />";
		echo "电子邮件:".$row['ch_name']."<br />";
		echo "电子邮件:".$row['description']."<br />";    
	}

}

?>