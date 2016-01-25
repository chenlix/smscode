<?php
date_default_timezone_set('Asia/Shanghai');
/**
 * 链接数据库
 */
$con = mysql_connect('192.168.1.149', 'develop', 'fangpinhui100');
if (!$con){
	die('Could not connect: '.mysql_error());//这里数据库连接不上也应该输出xml文本  方便在微信端知道哪里出错
}
mysql_query("set names utf8");
mysql_select_db('log', $con);
set_time_limit(0);
header("Content-Type: text/html; charset=UTF-8");
define('SCRIPT_ROOT',  dirname(__FILE__).'/');
require_once SCRIPT_ROOT.'include/Client.php';

/**
 * 网关地址
 */
$gwUrl = 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService';

/**
 * 序列号,请通过亿美销售人员获取
 */
$serialNumber = '9SDK-EMY-0999-JFTUP';

/**
 * 密码,请通过亿美销售人员获取
 */
$password = '088442';

/**
 * 登录后所持有的SESSION KEY，即可通过login方法时创建
 */
$sessionKey = '088442';

/**
 * 连接超时时间，单位为秒
 */
$connectTimeOut = 2;

/**
 * 远程信息读取超时时间，单位为秒
 */
$readTimeOut = 10;

/**
$proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
$proxyport		可选，代理服务器端口，默认为 false
$proxyusername	可选，代理服务器用户名，默认为 false
$proxypassword	可选，代理服务器密码，默认为 false
 */
$proxyhost = false;
$proxyport = false;
$proxyusername = false;
$proxypassword = false;

$client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
/**
 * 发送向服务端的编码，如果本页面的编码为GBK，请使用GBK
 */
$client->setOutgoingEncoding("UTF-8");

$id = (isset($argv[1])) ? intval($argv[1]) : 1;
$dateLog = "/var/www/logs/sendSmsNew.id";

sendSMS($id, $dateLog);         //发送短信

function sendSMS($id, $dateLog)
{
	global $client;
	//读取所要发送短信到数据，单条发送
	$sql="SELECT id,code,mobile FROM fph_send_sms WHERE id >=" . $id . " AND result = 1 AND 1=1 ORDER BY id ASC LIMIT 1";
	$res=mysql_query($sql);
	$rs=mysql_fetch_array($res);
	if (isset($rs['id']))
	{
		$statusCode = $client->sendSMS(array($rs['mobile']), $rs['code'], '', '', 'UTF-8');
		if ($statusCode == 0){
			$result = 2;
			$msg    = '发送成功';
			saveLog($dateLog, $rs['id'] + 1);
		}else{
			$result = 3;
			$msg    = '发送失败';
		}
		$updateSql = "update fph_send_sms set result = ".$result.", msg = '".$msg."' where id = ".$rs['id'];
		mysql_query($updateSql);
	} else {
		saveLog($dateLog, $id);
	}
	exit;
}


function saveLog($logFile, $content)
{
	file_put_contents($logFile, $content);
}