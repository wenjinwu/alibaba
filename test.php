<?php
require_once __DIR__."/vendor/autoload.php";
use alibaba\Api\alibaba\sms\AlibabaSms;
use alibaba\Api\alibaba\email\EmailSend;
use alibaba\Api\alibaba\email\AcsClient;
use alibaba\Api\alibaba\oss\Oss;
////api发送邮件
//$config = [
//    'accessKeyId'=>'',
//    'accessKeySecret'=>'',
//    'accountName'=>'',
//    'fromAlias'=>'',
//];
//$client = new AcsClient($config);
//$response = $client->singleSendMail('正文','主题','接收邮箱','昵称');
//
////api短信
//$config = [
//    'accessKeyId'=> '',
//    'accessKeySecret'=> '',
//    'signName'=> '',
//    'templateCode'=> '',
//];
//$alibabaSms = new AlibabaSms($config);
//$send = $alibabaSms ->send_verify('手机号',['code'=>112]);
//
////PHPMailer发送邮件 (成功返回1 ，否则为false)
//$EmailSend = EmailSend::send_mail('接收邮箱', '主题', '正文');

//oss API
$config = [
    'accesskey'=> '',
    'accesskeySecret'=> '',
    'bucket'=> '',
];
$data = "./timg.jpg";
//$data = "/1607425519.jpg";
$oss = new Oss($config);
$send = $oss ->upload($data);
//$send = $oss ->del($data);
print_r($send);
die();