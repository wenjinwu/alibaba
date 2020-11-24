<?php


namespace alibaba\Api\alibaba\email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailSend
{
//service@cloud2uc.com
//5jEOwm39-66666
    protected static $Username = '';
    protected static $Password = "";
    protected static $nickname = "CLOUD2UC云服务中心";
    protected static $Port = 465;
    protected static $Host = 'smtp.mxhichina.com';

    public static function send_mail($to,$title,$content) {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;  // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            $mail->CharSet = 'UTF-8';//包含中文字符
            $mail->isSMTP();
            $mail->Host = self::$Host;  //阿里邮箱的服务器地址
            $mail->SMTPAuth = true;
            $mail->Username = self::$Username;//授权邮箱
            $mail->Password = self::$Password;//邮箱登陆密码
            $mail->SMTPSecure = 'ssl';// 使用 ssl 加密方式登录
            $mail->Port = self::$Port;//smtp 服务器的远程服务器端口号
            $mail->setFrom(self::$Username, self::$nickname);//授权邮箱，发件人昵称
            $mail->addAddress($to); // 收件人邮箱
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $title;//标题
            $mail->Body = $content;//内容
            $return = $mail->send();
            return $return;
        } catch (Exception $e) {
            throw new $e($mail->ErrorInfo, -1);
        }
    }

    protected static function exception($msg, $code = 0, $exception = '')
    {
        $e = $exception ?: '\think\Exception';
        throw new $e($msg, $code);
    }
}