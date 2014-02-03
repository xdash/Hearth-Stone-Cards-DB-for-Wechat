<?php

header("content-type:text/html; charset=utf-8");

/**********************************************************

   Plugin Name: 炉石传说卡牌游戏资料查询器
   Plugin URI: http://www.fanbing.net
   Description: 暴雪首款免费跨平台休闲卡牌游戏大作《炉石传说》卡牌资料查询数据库。
   Version: 0.1
   Author: XDash
   Email: fanbingx@gmail.com
   Author URI: http://www.fanbing.net
   License: BSD
   Lastupdate:2013.11.09
   
***********************************************************/



// ------ Settings & Includes ----------


require_once("config.php");
require_once("strings.php");



// ------ BODY ----------


$wechatObj = new wechatCallbackapiTest(); // 微信消息接口实例，用于返回各类消息

if( isset($_REQUEST['echostr']) )

	$wechatObj->valid();

elseif( isset( $_REQUEST['signature'] ) ){

    $wechatObj->responseMsg();
    
}



class wechatCallbackapiTest{

// ********   微信消息主要交互流程用类   ********** //

	public $fromUsername;
	public $toUsername;
	public $msgType;
	public $keyword;
	public $event;
	

	public function responseMsg(){
	/*** 微信主要消息类 */
		
		$this->fetchData(); // 读取用户发送的消息，获取toUser、keyword等信息
		
		switch ($this->msgType){ // 根据不同的消息类型，发送回复响应
		
			case "event": // 用户事件
			
				$this->onEvent(); 
			
			case "text": // 文本
				
				$this->onText(); 
				
			case "location": // 位置
				
				exit;
				
			case "image": // 图片
			
				exit;	
							
			default:
			
				exit;
		
		}
	}



	private function onText(){
	/*** 处理用户发送的文本 */
	
	
		if(!empty( $this->keyword )){ // 关键词
        
        	switch (strtolower($this->keyword)){
        	
				case "hi": 		// 欢迎信息
						
					echo $this->respondPlainText(WELCOME);	


				case "r": 		// 随机一卡
						
					$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
					mysql_select_db("app_hearthstone", $conn); 
					$sql = "select * from cards";
					$result = mysql_query($sql,$conn);
					$total_num = mysql_num_rows($result);
					$r = rand(1,$total_num); // 获取卡片总量，随机输出卡片ID

					$sql = "select * from cards where id=".$r;
					$result = mysql_query($sql,$conn); 

					while( $row = mysql_fetch_array($result) ){
							$card['url'] = $row['url'];
							$card['pic'] = $row['img'];
							$description = $row['description']<>""?" ● ".$row['description']:"";
							$card['title'] = "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;										$card['content'] =  "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;					
							$results[] = $card;//增加一条查询结果 
						}
						
					$this->respondMultipleNews($results);
						
				
				default: // 不属于任何一种系统关键字，按搜索卡片处理

					$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
					mysql_select_db("app_hearthstone", $conn); 
					$sql = "select * from cards where ch_name like '%".$this->keyword."%' or en_name like '%".$this->keyword."%' or skill like '%".$this->keyword."%' or description like '%".$this->keyword."%' LIMIT 10";
					$result = mysql_query($sql,$conn); 


					if (mysql_num_rows($result)>0){ //搜到

						while( $row = mysql_fetch_array($result) ){
							$card['url'] = $row['url'];
							$card['pic'] = $row['img'];
							$description = $row['description']<>""?" ● ".$row['description']:"";
							$card['title'] = "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;										$card['content'] =  "【".$row['ch_name']." ".$row['en_name']."】「".$row['type']."/".$row['rare']."/".$row['job']."」ATK ".$row['atk']." / HP ".$row['life']." / COST ".$row['cost'].$description;					
							$results[] = $card;//增加一条查询结果 
						}
						
						//echo $this->respondPlainText($results[2]['content']);
						
						
						//ob_start(); 
						
						$this->respondMultipleNews($results);
						

					}else{ //没搜到
					
						echo $this->respondPlainText(NORESULT);
						
					}				

  			} // END SIGN for Switch
  				
						     
        } // END SIGN OF if (!empty($keyword))
	
	
	}




	private function onEvent(){
	/*** 响应特殊用户事件 */	
	
		if(!empty( $this->event )){ // 事件
   			
			switch ($this->event){
   			
			case "subscribe": // 关注事件
   				
				echo $this->respondPlainText(WELCOME);
   			
			case "unsubscribe":// 取消关注事件
   				
				echo $this->respondPlainText(SAYBYE);
   				
   			}
		}
	}







	private function respondPlainText($text){
	/*** 微信回复纯文本信息 */
	
		$returnText = "<xml><ToUserName><![CDATA[".$this->fromUsername."]]></ToUserName><FromUserName><![CDATA[".$this->toUsername."]]></FromUserName><CreateTime><?=time()?></CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$text."]]></Content></xml>";
		return $returnText;
	}






	private function respondSingleNews($news){
	/*** 微信回复单条图文信息 */

	?>								
		<xml>
		<ToUserName><![CDATA[<?=$this->fromUsername?>]]></ToUserName>
		<FromUserName><![CDATA[<?=$this->toUsername?>]]></FromUserName>
		<CreateTime><?=time()?></CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>1</ArticleCount>
		<Articles><?php foreach( $news as $item ): ?>
		<item> 
			<Title><![CDATA[<?=$item['title']?>]]></Title>
			<Description><![CDATA[<?=$item['content']?>]]></Description>
			<PicUrl><![CDATA[<?=$item['pic']?>]]></PicUrl>							
			<Url><![CDATA[<?=$item['url']?>]]></Url>
		</item>
		<?php endforeach; ?></Articles>
		<FuncFlag>0</FuncFlag>
		</xml>		
	<?php		
		$xml = ob_get_contents();
		//file_put_contents('xml.txt', $xml);
		header('Content-Type: text/xml');
		echo trim($xml); 
	}







	private function respondMultipleNews($news){
	/*** 微信回复多条图文信息 */

	?>				
		<xml>
		<ToUserName><![CDATA[<?=$this->fromUsername?>]]></ToUserName>
		<FromUserName><![CDATA[<?=$this->toUsername?>]]></FromUserName>
		<CreateTime><?=time()?></CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount><?=count($news)?></ArticleCount>
		<Articles><?php foreach( $news as $item ): ?>
		<item> 
			<Title><![CDATA[<?=$item['title']?>]]></Title>
			<Description><![CDATA[<?=$item['content']?>]]></Description>
			<PicUrl><![CDATA[<?=$item['pic']?>]]></PicUrl>							
			<Url><![CDATA[<?=$item['url']?>]]></Url>
		</item>
		<?php endforeach; ?></Articles>
		<FuncFlag>0</FuncFlag>
		</xml>			
	<?php			
		$xml = ob_get_contents();
		//file_put_contents('xml.txt', $xml);
		header('Content-Type: text/xml');

		echo trim($xml); 

	}






	private function fetchData(){
	/*** 解析用户的消息请求 */	
	
	    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	    if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->fromUsername = $postObj->FromUserName;
			$this->toUsername = $postObj->ToUserName;		
			$this->keyword = trim($postObj->Content);
			$this->msgType = trim($postObj->MsgType);			
			$this->event = trim($postObj->Event);
			$time = time();
		}
	}





	public function valid(){
	/*** 如果消息的用户签名正确，则返回消息 */
	
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
          echo $echoStr;
          exit;
        }
	}
	
	
	
	
	
	private function checkSignature(){
	/*** 检查消息的用户签名 */

    	$signature = $_GET["signature"];
    	$timestamp = $_GET["timestamp"];
    	$nonce = $_GET["nonce"];  
            
    	$token = TOKEN;
    	$tmpArr = array($token, $timestamp, $nonce);
    	sort($tmpArr);
    	$tmpStr = implode( $tmpArr );
    	$tmpStr = sha1( $tmpStr );
    
    	if( $tmpStr == $signature ){
      	return true;
    	}else{
      	return false;
    	}
	}



} // End Sign of class wechatCallbackapiTest