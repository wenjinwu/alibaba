<?php


namespace alibaba\Api\qiniu\oss;

use CURLFile;

class Oss
{
    private $accesskey = "*********";
    private $accesskeySecret = "*********";
    private $bucket = "*********";
    private $host = "https://up-z1.qiniup.com";

    public function __construct($config = [])
    {
        $this->accesskey = $config['accesskey'] ? $config['accesskey'] : $this->accesskey;
        $this->accesskeySecret = $config['accesskeySecret'] ? $config['accesskeySecret'] : $this->accesskeySecret;
        $this->bucket = $config['bucket'] ? $config['bucket'] : $this->bucket;
    }

    public function Upload($file) {

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $path = time().'.'.$ext;
        $data = [
            'token' => $this->getSign($path),
            'file' => new CURLFile(realpath($file)),
            'key' => $path
        ];

       $return = $this->ossCurl($data);

        return $return;
    }

    public function del($file){

        $return = $this->ossCurl($file);
        return $return;
    }

    public function token($file){
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $path = time().'.'.$ext;
        return $this->getSign($path);
    }

    private function Encode($s) {
        return str_replace(['+', '/'], ['-', '_'], base64_encode($s));
    }

    private function getSign($path) {
        $toSign = $this->Encode(json_encode(['scope' => $this->bucket . ':' . $path, 'deadline' => time() + 1200]));
        $sign = $this->Encode(hash_hmac('sha1', $toSign, $this->accesskeySecret, true));
        return sprintf('%s:%s:%s', $this->accesskey, $sign, $toSign);
    }

    private function ossCurl($data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return $data;
    }
}