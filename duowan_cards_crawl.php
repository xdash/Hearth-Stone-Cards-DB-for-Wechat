<?php
header("content-type:text/html; charset=utf-8");

$start_page = "http://db.duowan.com/lushi"; // Start Page for Crawl





$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
mysql_select_db("app_hearthstone", $conn); 



mysql_query("truncate cards",$conn);//Clear table and write in new data




$crawl = new Duowan_Crawl();

$pages = $crawl->GetEachPage($start_page);


	for($i=0;$i<count($pages);$i++){
	
		$allCards = $crawl->GetCardsLinkOnPage($pages[$i]);
		
			for($j=0;$j<count($allCards);$j++){
			
				$cardInfo = $crawl->GetPropertiesOfCard($allCards[$j]); //Got Card Info
				
				echo "|"; // Simple Progress Bar
				
				//var_dump($cardInfo);
				
				$sql = AddItemToTable($cardInfo);
				mysql_query($sql,$conn); 				
				
			}
	}





function AddItemToTable($card){

	$sql = "INSERT INTO cards (ch_name,en_name,type,rare,job,atk,life,cost,skill,description,img,url) VALUES(
'".$card[0]."',
'".$card[1]."',
'".$card[4]."',
'".$card[3]."',
'".$card[2]."',
'".$card[7]."',
'".$card[6]."',
'".$card[5]."',
'".$card[8]."',
'".$card[9]."',
'".$card[10]."',
'".$card[11]."')";

	return $sql;

}








class Duowan_Crawl{


public function GetEachPage($start_page){
// Get All Pages' Links => $page[]

	// Get page navibar area
	$src = file_get_contents($start_page);
	$arr = explode('上一页</a>',$src); // Page Navibar Top
	$arr = explode('<a target="_self" title="下一页"',$arr[1]); // Page Navibar Bottom
	$src_pages = $arr[0];
	
	// Get URL of each page
	$page = explode('</a>',$src_pages); 
	array_pop($page);

	for($i=0;$i<count($page);$i++){
		$page[$i] = trim($page[$i]);
		$page[$i] = substr($page[$i],strpos($page[$i],'http://db.duowan.com'));
		$page[$i] = substr($page[$i],0,strpos($page[$i],'">'));	
		//echo $page[$i]."<br/>";
	}
	
	return $page;
	
	//var_dump($page);
}




public function GetCardsLinkOnPage($page_url){
// Get All Cards' Links on one page => $card_url[]

	// Get all cards' links on each page
	$src = file_get_contents($page_url);
	$arr = explode('<tbody>',$src); // Cards Area Top
	$arr = explode('</tbody>',$arr[1]); //Cards Area Bottom
	$src_cards = $arr[0];
	
	// Get URL of singe card
	$card_url = explode('</tr>',$src_cards); 
	array_pop($card_url);
	
	for($i=0;$i<count($card_url);$i++){
		$card_url[$i] = trim($card_url[$i]);
		$card_url[$i] = substr($card_url[$i],strpos($card_url[$i],'http://db.duowan.com'));
		$card_url[$i] = substr($card_url[$i],0,strpos($card_url[$i],'" data-src='));	
	}
	
	return $card_url;
}



public function GetPropertiesOfCard($card_url){


	// Get each card's information
	$src = file_get_contents($card_url); 
	$arr = explode('<tbody>',$src); // Cards Info Top
	$arr = explode('</tbody>',$arr[1]); //Cards Info Bottom
	$src_card_infos = $arr[0];
	
	
	// Get each property of singe card
	$card_info = explode('</tr>',$src_card_infos); 
	
	for($i=0;$i<count($card_info);$i++){
		
		
		 // Get image of card
		 // card[10]=卡图URL
		if (strpos($card_info[$i],'class="img-rounded"')>0){
			//echo "card image!";
			$card[10] = substr($card_info[$i],strpos($card_info[$i],'http://img'));
			$card[10] = substr($card[10],0,strpos($card[10],'"></td>'));
			$card[10] = str_replace(" ","%20",$card[10]);
			
			$card_info[$i] = str_replace("	","",$card_info[$i]); //tidy for process below	
			
			//break;
		}
		
		$card_info[$i] = substr($card_info[$i],strpos($card_info[$i],'<td><b>'));
		$card_info[$i] = trim($card_info[$i]);
		//$card_info[$i] = str_replace(" ","",$card_info[$i]);
		
		
		// Get property of card
		$card_property[$i] = substr($card_info[$i],strpos($card_info[$i],'<b>')+3);
		$card_property[$i] = trim(substr($card_property[$i],0,strpos($card_property[$i],'</b></td>'))); // Get one property
			
						
		switch (trim($card_property[$i])){
		
			case "名称：": //card[0]=中文名
				$card[0] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[0] = substr($card[0],0,strpos($card[0],'</td>'));		
			case "英文名：": //card[1]=英文名
				$card[1] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[1] = substr($card[1],0,strpos($card[1],'</td>'));				
			case "职业：": //card[2]=职业
				$card[2] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[2] = substr($card[2],0,strpos($card[2],'</td>'));				
			case "稀有度：": //card[3]=稀有度
				$card[3] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[3] = substr($card[3],0,strpos($card[3],'</td>'));					
			case "类型：": //card[4]=类型
				$card[4] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[4] = substr($card[4],0,strpos($card[4],'</td>'));					
			case "法力：": //card[5]=法力
				$card[5] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[5] = substr($card[5],0,strpos($card[5],'</td>'));				
			case "生命力：": //card[6]=生命力
				$card[6] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[6] = substr($card[6],0,strpos($card[6],'</td>'));					
			case "攻击：": //card[7]=攻击力
				$card[7] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[7] = substr($card[7],0,strpos($card[7],'</td>'));				
			case "特殊技能：": //card[8]=特殊技能
				$card[8] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[8] = substr($card[8],0,strpos($card[8],'</td>'));				
			case "描述：": //card[9]=描述
				$card[9] = substr($card_info[$i],strpos($card_info[$i],'<td>',10)+4);
				$card[9] = substr($card[9],0,strpos($card[9],'</td>'));								
		}	
	
	}
	
	$card[11] = $card_url; //card[11]=卡片URL
	
	return $card;

}






} // end of class

?>