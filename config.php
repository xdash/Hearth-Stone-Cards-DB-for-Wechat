<?php

/** HearthStone Card Search Engine for Wechat
 * : fanbingx@gmail.com
 * : 2013-11-09 
 */ 


/*程序名称*/
define('App_Name','HearthStone Wechat');
/*作者*/
define('App_Author','XDash');
/*版本*/
define('App_Version','0.01');
/*主页*/
define('App_Homepage','http://www.fanbing.net');
/*Email*/
define('App_Email','fanbingx@gmail.com');


/*SAE数据库主机*/
$db_port=$_SERVER['HTTP_MYSQLPORT'];
define('App_MysqlHost',"m$db_port.mysql.sae.sina.com.cn:".$db_port);
/*SAE数据库名称*/
define('App_MysqlName','app_'.$_SERVER['HTTP_APPNAME']);
/*SAE数据库用户名*/
define('App_MysqlUser','replaceToYourUsername'); //替换成你的
/*SAE数据库密码*/
define('App_MysqlPw','replaceToYourPassword'); //替换成你的


/*微信公众平台开发者模式token*/
define("TOKEN", "hearthstoneReplaceToYourToken"); // 替换成你的；必须和微信公众平台中的设置保持一致，设置页面 http://mp.weixin.qq.com/cgi-bin/callbackprofile?t=wxm-callbackapi&type=info&lang=zh_CN








?>