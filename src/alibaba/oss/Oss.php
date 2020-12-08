<?php


namespace alibaba\Api\alibaba\oss;


class Oss
{
    private $accesskey = "*********";
    private $accesskeySecret = "*********";
    private $bucket = "*********";
    private $host = "oss-cn-beijing.aliyuncs.com";

    public function __construct($config = [])
    {
        $this->accesskey = $config['accesskey'] ? $config['accesskey'] : $this->accesskey;
        $this->accesskeySecret = $config['accesskeySecret'] ? $config['accesskeySecret'] : $this->accesskeySecret;
        $this->bucket = $config['bucket'] ? $config['bucket'] : $this->bucket;
    }
    //上传文件  $data 文件；$path 上传的路径
    public function upload($data,$path = '/'){

        $ext = pathinfo($data, PATHINFO_EXTENSION);
        $name = time();
        $object = $path.$name.'.'.$ext;
        $url = "http://".$this->bucket.'.'.$this->host.$object;
        $method = "PUT";
        $time = gmdate ("D, d M Y H:i:s T");

        //Authorization拼装部分
        /***
        Signature = base64(hmac-sha1(AccessKeySecret,
        VERB + "\n"                   //HTTP请求的Method
        + Content-MD5 + "\n"          //可以省略
        + Content-Type + "\n"         //内容的类型
        + Date + "\n"                 //时间，GMT格式
        + CanonicalizedOSSHeaders     //可以省略
        + CanonicalizedResource))     //资源表示
         ***/
        $str = "".$method."\n\n"."image/jpeg\n".$time."\n/".$this->bucket."".$object."";

        $signature = base64_encode(hash_hmac("sha1", $str, $this->accesskeySecret, true));

        $headers = array(
            "Date:".$time,
            'Content-Type:image/jpeg',//定义文件上传后的类型
            "Authorization:OSS ".$this->accesskey.":".$signature//阿里云oss验证的合法信息拼装
        );
        return $this->sendCurlPost($url,$method,$headers,$data);
    }
    //删除文件  $data 文件
    public function del($data){
        $object = $data;
        $url = "http://".$this->bucket.'.'.$this->host.$object;
        $method = "DELETE";
        $time = gmdate ("D, d M Y H:i:s T");

        //Authorization拼装部分
        /***
        Signature = base64(hmac-sha1(AccessKeySecret,
        VERB + "\n"                   //HTTP请求的Method
        + Content-MD5 + "\n"          //可以省略
        + Content-Type + "\n"         //内容的类型
        + Date + "\n"                 //时间，GMT格式
        + CanonicalizedOSSHeaders     //可以省略
        + CanonicalizedResource))     //资源表示
         ***/
        $str = "".$method."\n\n"."image/jpeg\n".$time."\n/".$this->bucket."".$object."";

        $signature = base64_encode(hash_hmac("sha1", $str, $this->accesskeySecret, true));

        $headers = array(
            "Date:".$time,
            'Content-Type:image/jpeg',//定义文件上传后的类型
            "Authorization:OSS ".$this->accesskey.":".$signature//阿里云oss验证的合法信息拼装
        );
        return $this->sendCurlPost($url,$method,$headers);
    }

    private function sendCurlPost($url,$method,$headers,$file = ''){
        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式

        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);//设置HTTP头信息

        curl_setopt($ch, CURLOPT_PUT, true); //设置为PUT请求
        if($method == 'PUT'){
            curl_setopt($ch, CURLOPT_INFILE, fopen($file, 'rb')); //设置资源句柄
            //curl_setopt($ch, CURLOPT_INFILESIZE, filesize($data));//可以注释
        }

        $document = curl_exec($ch);//执行预定义的CURL

        if(!curl_errno($ch)){
            $data = [
                'code'=>1,
                'msg'=>'成功',
            ];
        } else {
            $data = [
                'code'=>0,
                'msg'=>curl_error($ch),
            ];
        }
        curl_close($ch);

        return $data;
    }
}