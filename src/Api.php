<?php


namespace alibaba\Api;

use alibaba\Api\alibaba\sms\AlibabaSms;

class Api
{
    protected $sms;

    function __construct()
    {
        $this->init();
    }

    protected function init() {
        $this->sms = new AlibabaSms();
    }
}