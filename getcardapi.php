<?php
header("content-type:text/html; charset=utf-8");

$searchQuest = new getCardAPI();

//echo $_GET["keyword"];

$searchQuest->getCards(urlencode($_GET["keyword"]));




class getCardAPI{


	public function getCards($keyword){

	
		if(!empty($keyword)){ // 关键词
		
					$keyword = urldecode($keyword);
        
					$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
					mysql_select_db("app_hearthstone", $conn); 
					$sql = "select * from cards where ch_name like '%".$keyword."%' or en_name like '%".$keyword."%' or skill like '%".$keyword."%' or description like '%".$keyword."%'"; // 相对微信结果，取消了LIMIT 10
					$result = mysql_query($sql,$conn); 

					if (mysql_num_rows($result)>0){ //搜到

						while( $row = mysql_fetch_array($result) ){
							$card['url'] = $row['url'];
							$card['pic'] = $row['img'];
							$description = ($row['description'])<>""?" ● ".($row['description']):"";
							$card['title'] = "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;
							$card['title'] = urlencode($card['title']);
							$card['content'] = "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;
							$card['content'] = urlencode($card['content']);
			
							$results[] = $card; //$results 即是结果数组 
							
							
						}
						
						
					}else{
					
							$results[] = array();
						
					}				
  				
					
					
					//var_dump($results);
					
					echo urldecode(json_encode($results));
				
									
					//echo urldecode(json_encode($results));
						     
						     
						     
        } // END SIGN OF if (!empty($keyword))
        
        
        
        
        
	
	
	}// END SIGH OF function
	


}// END SIGH OF class



?>