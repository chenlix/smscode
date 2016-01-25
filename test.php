<?php
set_time_limit(0);

header("Content-Type: text/html; charset=UTF-8");

/**
 * 定义程序绝对路径
 */
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
$connectTimeOut = 5;

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
$client->setOutgoingEncoding("GBK");

sendSMS();         //发送短信

/**
 * 接口调用错误查看 用例
 */
function chkError()
{
    global $client;

    $err = $client->getError();
    if ($err)
    {
        /**
         * 调用出错，可能是网络原因，接口版本原因 等非业务上错误的问题导致的错误
         * 可在每个方法调用后查看，用于开发人员调试
         */

        echo $err;
    }

}


/**
 * 取消短信转发 用例
 */
function cancelMOForward()
{
    global $client;


    $statusCode = $client->cancelMOForward();
    echo "处理状态码:".$statusCode;
}

/**
 * 短信充值 用例
 */
function chargeUp()
{
    global $client;

    /**
     * $cardId [充值卡卡号]
     * $cardPass [密码]
     *
     * 请通过亿美销售人员获取 [充值卡卡号]长度为20内 [密码]长度为6
     *
     */

    $cardId = 'EMY01200810231542008';
    $cardPass = '123456';
    $statusCode = $client->chargeUp($cardId,$cardPass);
    echo "处理状态码:".$statusCode;
}


/**
 * 查询单条费用 用例
 */
function getEachFee()
{
    global $client;
    $fee = $client->getEachFee();
    echo "费用:".$fee;
}


/**
 * 企业注册 用例
 */
function registDetailInfo()
{
    global $client;

    $eName = "xx公司";
    $linkMan = "陈xx";
    $phoneNum = "010-1111111";
    $mobile = "159xxxxxxxx";
    $email = "xx@yy.com";
    $fax = "010-1111111";
    $address = "xx路";
    $postcode = "111111";

    /**
     * 企业注册  [邮政编码]长度为6 其它参数长度为20以内
     *
     * @param string $eName 	企业名称
     * @param string $linkMan 	联系人姓名
     * @param string $phoneNum 	联系电话
     * @param string $mobile 	联系手机号码
     * @param string $email 	联系电子邮件
     * @param string $fax 		传真号码
     * @param string $address 	联系地址
     * @param string $postcode  邮政编码
     *
     * @return int 操作结果状态码
     *
     */
    $statusCode = $client->registDetailInfo($eName,$linkMan,$phoneNum,$mobile,$email,$fax,$address,$postcode);
    echo "处理状态码:".$statusCode;

}

/**
 * 更新密码 用例
 */
function updatePassword()
{
    global $client;

    /**
     * [密码]长度为6
     *
     * 如下面的例子是将密码修改成: 654321
     */
    $statusCode = $client->updatePassword('654321');
    echo "处理状态码:".$statusCode;
}

/**
 * 短信转发 用例
 */
function setMOForward()
{

    global $client;

    /**
     * 向 159xxxxxxxx 进行转发短信
     */
    $statusCode = $client->setMOForward('159xxxxxxxx');
    echo "处理状态码:".$statusCode;
}

/**
 * 得到上行短信 用例
 */
function getMO()
{
    global $client;
    $moResult = $client->getMO();
    echo "返回数量:".count($moResult);
    foreach($moResult as $mo)
    {
        //$mo 是位于 Client.php 里的 Mo 对象
        // 实例代码为直接输出
        echo "发送者附加码:".$mo->getAddSerial();
        echo "接收者附加码:".$mo->getAddSerialRev();
        echo "通道号:".$mo->getChannelnumber();
        echo "手机号:".$mo->getMobileNumber();
        echo "发送时间:".$mo->getSentTime();

        /**
         * 由于服务端返回的编码是UTF-8,所以需要进行编码转换
         */
        echo "短信内容:".iconv("UTF-8","GBK",$mo->getSmsContent());
        //echo "短信内容:".$mo->getSmsContent();

        // 上行短信务必要保存,加入业务逻辑代码,如：保存数据库，写文件等等
    }

}

/**
 * 短信发送 用例
 */
function sendSMS()
{
    global $client;
    /**
     * 下面的代码将发送内容为 test 给 159xxxxxxxx 和 159xxxxxxxx
     * $client->sendSMS还有更多可用参数，请参考 Client.php
     */
    $statusCode = $client->sendSMS(array('18117108553'), '测试2222');
    echo "处理状态码:".$statusCode;
}

/**
 * 发送语音验证码 用例
 */
function sendVoice()
{
    global $client;
    /**
     * 下面的代码将发送验证码123456给 159xxxxxxxx
     * $client->sendSMS还有更多可用参数，请参考 Client.php
     */
    $statusCode = $client->sendVoice(array('159xxxxxxxx'),"123456");
    echo "处理状态码:".$statusCode;
}

/**
 * 余额查询 用例
 */
function getBalance()
{
    global $client;
    $balance = $client->getBalance();
    echo "余额:".$balance;
}

/**
 * 短信转发扩展 用例
 */
function setMOForwardEx()
{
    global $client;

    /**
     * 向多个号码进行转发短信
     *
     * 以数组形式填写手机号码
     */
    $statusCode = $client->setMOForwardEx(
        array('159xxxxxxxx','159xxxxxxxx','159xxxxxxxx')
    );
    echo "处理状态码:".$statusCode;
}


?>