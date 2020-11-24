<?php
namespace alibaba\Api\alibaba\email;
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
class AcsClient
{
    private $accessKeyId = "*********";
    private $accessKeySecret = "*********";
    private $accountName = "";//管理控制台中配置的发信地址
    private $fromAlias = "";//发信人昵称，长度小于15个字符。

    private $url = "https://dm.aliyuncs.com/?";
    private $action = "SingleSendMail";//系统规定参数。取值：SingleSendMail。
    private $addressType = 1;//地址类型。取值：0：为随机账号1：为发信地址
    private $format = "JSON";
    private $regionId = "cn-hangzhou";
    private $replyToAddress = "true";//使用管理控制台中配置的回信地址（状态必须是验证通过）
    private $signatureVersion = "1.0";
    private $version = "2015-11-23";
    private $signatureMethod = "HMAC-SHA1";

    public function __construct($config = [])
    {
        $this->accessKeyId = $config['accessKeyId'] ? $config['accessKeyId'] : $this->accessKeyId;
        $this->accessKeySecret = $config['accessKeySecret'] ? $config['accessKeySecret'] : $this->accessKeySecret;
        $this->accountName = $config['accountName'] ? $config['accountName'] : $this->accountName;
        $this->action = $config['action'] ? $config['action'] : $this->action;
        $this->regionId = $config['regionId'] ? $config['regionId'] : $this->regionId;
        $this->fromAlias = $config['fromAlias'] ? $config['fromAlias'] : $this->fromAlias;
    }

    //普通单发
    public function singleSendMail($hmtlBody,$subject,$toAddress,$fromAlias){
        $data = [
            'AccessKeyId' => $this->accessKeyId,
            'AccountName' => $this->accountName,
            'Action' => $this->action,
            'AddressType' => $this->addressType,
            'Format' => $this->format,
            'FromAlias' => $this->fromAlias,
            'HtmlBody' => $hmtlBody,
            'RegionId' => $this->regionId,
            'ReplyToAddress' => $this->replyToAddress,
            'SignatureMethod' => $this->signatureMethod,
            'SignatureNonce' => $this->getSignatureNonce(),
            'SignatureVersion' => $this->signatureVersion,
            'Subject' => $subject,
            'Timestamp' => $this->getTimeStamp(),
            'ToAddress' => $toAddress,
            'Version' => $this->version,
        ];
        //签名
        $httpMethod = "GET";
        $signture = $this->computeSignature($data,$this->accessKeySecret);
        $data['Signature'] = $signture;
        $requestUrl = $this->url.http_build_query ( $data );
        $respObject = $this->sendCurlPost($requestUrl);
        return $respObject;
    }

    private function getUtf8Encode($str){
        $string = urlencode($str);
        $string = preg_replace('/\+/', '%20', $string);
        $string = preg_replace('/\*/', '%2A', $string);
        $string = preg_replace('/%7E/', '~', $string);
        return $string;
    }

    private function getTimeStamp(){
        date_default_timezone_set('UTC');
        $t = date("Y-m-d")."T".date("H:i:s")."Z";
        return $t;
    }

    private function getSignatureNonce(){
        return rand(111111,999999).'-'.rand(13333,99999).'-'.rand(1111,9999);
    }

    private function computeSignature($parameters, $accessKeySecret) {
        ksort ( $parameters );
        $canonicalizedQueryString = '';
        foreach ( $parameters as $key => $value ) {
            $canonicalizedQueryString .= '&' . $this->getUtf8Encode ( $key ) . '=' . $this->getUtf8Encode ( $value );
        }
        $stringToSign = 'GET&%2F&' . $this->getUtf8Encode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode ( hash_hmac ( 'sha1', $stringToSign, $accessKeySecret . '&', true ) );
        return $signature;
    }

    private function sendCurlPost($url){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "GET" );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        $result = json_decode ( $result, true );

        return $result;
    }
}