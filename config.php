<?php

/** HearthStone Card Search Engine for Wechat
 * : fanbingx@gmail.com
 * : 2013-11-09 
 */ 


/*��������*/
define('App_Name','HearthStone Wechat');
/*����*/
define('App_Author','XDash');
/*�汾*/
define('App_Version','0.01');
/*��ҳ*/
define('App_Homepage','http://www.fanbing.net');
/*Email*/
define('App_Email','fanbingx@gmail.com');


/*SAE���ݿ�����*/
$db_port=$_SERVER['HTTP_MYSQLPORT'];
define('App_MysqlHost',"m$db_port.mysql.sae.sina.com.cn:".$db_port);
/*SAE���ݿ�����*/
define('App_MysqlName','app_'.$_SERVER['HTTP_APPNAME']);
/*SAE���ݿ��û���*/
define('App_MysqlUser','replaceToYourUsername'); //�滻�����
/*SAE���ݿ�����*/
define('App_MysqlPw','replaceToYourPassword'); //�滻�����


/*΢�Ź���ƽ̨������ģʽtoken*/
define("TOKEN", "hearthstoneReplaceToYourToken"); // �滻����ģ������΢�Ź���ƽ̨�е����ñ���һ�£�����ҳ�� http://mp.weixin.qq.com/cgi-bin/callbackprofile?t=wxm-callbackapi&type=info&lang=zh_CN








?>