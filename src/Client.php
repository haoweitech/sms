<?php

namespace Hwtech\Sms;

use ErrorException;

class Client
{
    const VERSION_CODE = '1.0.0';
    const VERSION_NUMBER = 10000;

    const SANDBOX = 0;
    const PRODUCTION = 1;

    private $url = [
        'https://testapi.haowei.tech/gateway.do',
        'https://api.haowei.tech/gateway.do'
    ];

    /**
     * @var string
     */
    private $app_id;

    /**
     * @var string
     */
    private $secret_key;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var int
     */
    private $environment;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $sign_type = 'MD5';


    public function __construct()
    {
        $this->environment = self::PRODUCTION;
        $this->version = '1.0';
        $this->timestamp = round(microtime(true) * 1000);
    }

    /**
     * @param int $environment
     * @throws ErrorException
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        if (!isset($this->url[$environment])) throw new ErrorException("网关运行环境错误");
    }

    /**
     * @param string $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    /**
     * @param string $secret_key
     */
    public function setSecretKey($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    /**
     * @param string $sign_type
     */
    public function setSignType($sign_type)
    {
        $this->sign_type = $sign_type;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param array $data
     * @param string $key
     * @return string
     */
    protected function createSignature($data, $key)
    {
        $map = [];
        ksort($data);
        $data['key'] = $key;
        foreach ($data as $k => $v) $map[] = $k . '=' . $v;
        return strtoupper(md5(implode('&', $map)));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function execute(Request $request)
    {
        $post = [];
        $post['app_id'] = $this->app_id;
        $post['version'] = $this->version;
        $post['timestamp'] = $this->timestamp;
        $post['sign_type'] = $this->sign_type;
        $post['method'] = $request->getMethod();
        $post['biz_content'] = $request->getBizContent();
        $post['sign'] = $this->createSignature($post, $this->secret_key);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url[$this->environment],
            CURLOPT_HEADER => false,
            CURLOPT_CONNECTTIMEOUT_MS => 5 * 1000,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT_MS => 15 * 1000,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post)
        ]);
        $milliseconds = round(microtime(true) * 1000);
        $raw = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return [
            json_decode($raw, true),
            $errno,
            $error,
            (round(microtime(true) * 1000) - $milliseconds) . 'ms'
        ];
    }
}


