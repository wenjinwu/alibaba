# 阿里大于(鱼) - v2.0

> PS：**阿里** [github.com/wenjinwu/alibaba](https://github.com/wenjinwu/alibaba)

## 更新


#### v2.0.3 (2016-10-12)

- 新增沙箱配置

## 功能

- `通过` [阿里短信发送](test.php)
- `通过` [阿里邮件PHPMailer发送](test.php)
- `通过` [阿里邮件推送API发送](test.php)

> **`待测`**：因个人开发者，阿里大于权限相对较低。暂时无法测试；功能已开发，如测试可用，请告知~~

## 环境

- PHP >= 7.0
- ext-curl *
- [composer](https://getcomposer.org/)
- [phpmailer](https://packagist.org/packages/phpmailer/phpmailer)

## 安装

```shell
composer require wengoldwu/alibaba
```

## 使用

```php
<?php
use alibaba\Api\alibaba\sms\AlibabaSms;
use alibaba\Api\alibaba\email\EmailSend;
use alibaba\Api\alibaba\email\AcsClient;

//api发送邮件
$config = [
    'accessKeyId'=>'',
    'accessKeySecret'=>'',
    'accountName'=>'',
    'fromAlias'=>'',
];
$client = new AcsClient($config);
$response = $client->singleSendMail('正文','主题','接收邮箱','昵称');

//api短信
$config = [
    'accessKeyId'=> '',
    'accessKeySecret'=> '',
    'signName'=> '',
    'templateCode'=> '',
];
$alibabaSms = new AlibabaSms($config);
$send = $alibabaSms ->send_verify('手机号',['code'=>112]);

//PHPMailer发送邮件 (成功返回1 ，否则为false)
$EmailSend = EmailSend::send_mail('接收邮箱', '主题', '正文');

// 返回结果
print_r($EmailSend);
print_r($send);
print_r($response);
?>
```

## 帮助

- 意见、BUG反馈： github.com/wenjinwu/alibaba

## 支持

- 官方网址： https://www.alidayu.com/
- 官方API文档： https://api.alidayu.com/doc2/apiList.htm
- composer： https://getcomposer.org/

## 捐赠

如果你觉得本扩展对你有帮助，请捐赠以表支持，谢谢~~

<table>
    <tr>
        <td align="center"><img src="#" width="220"><p>微信</p></td>
        <td align="center"><img src="#" width="220"><p>支付宝</p></td>
    </tr>
</table>

## License

- MIT
