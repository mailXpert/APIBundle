<?php

namespace mailxpert\apibundle\Util;


use Mailxpert\Authentication\AccessToken;
use Mailxpert\Mailxpert;

class APIManager
{
    /**
     * @var string $appId mailXpert API Application Id
     */
    private $appId;

    /**
     * @var string $appSecret mailXpert API Application secret
     */
    private $appSecret;

    /**
     * @var string $redirectUrl mailXpert API Redirection URL
     */
    private $redirectUrl;

    /**
     * @var Mailxpert
     */
    private $mailxpert;

    /**
     * AccessTokenManager constructor.
     *
     * @param $appId
     * @param $appSecret
     * @param $redirectUrl
     */
    public function __construct($appId, $appSecret, $redirectUrl)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->redirectUrl = $redirectUrl;

        $this->mailxpert = new Mailxpert([
            'appId' => $this->appId,
            'appSecret' => $this->appSecret
        ]);
    }

    public function getMailxpert()
    {
        return $this->mailxpert;
    }

    public function setAccessToken(AccessToken $accessToken)
    {
        $this->getMailxpert()->setAccessToken($accessToken);
    }
}