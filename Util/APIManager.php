<?php

namespace Mailxpert\APIBundle\Util;

use Mailxpert\APIBundle\Exceptions\MailxpertAPIBundleException;
use Mailxpert\APIBundle\Model\AccessTokenInterface;
use Mailxpert\APIBundle\Model\AccessTokenManagerInterface;
use Mailxpert\Authentication\AccessToken as SDKAccessToken;
use Mailxpert\Mailxpert;

/**
 * Class APIManager
 * @package Mailxpert\APIBundle\Util
 */
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
     * @var string $scope mailXpert OAuth Scope
     */
    private $scope;

    /**
     * @var Mailxpert
     */
    private $mailxpert;

    /**
     * @var AccessTokenManagerInterface
     */
    private $accessTokenManager;

    /**
     * AccessTokenManager constructor.
     *
     * @param AccessTokenManagerInterface $accessTokenManager
     * @param string                      $appId
     * @param string                      $appSecret
     * @param string                      $redirectUrl
     * @param string|null                 $scope
     * @param null                        $APIBaseUrl
     * @param null                        $APIOAuthUrl
     */
    public function __construct(AccessTokenManagerInterface $accessTokenManager, $appId, $appSecret, $redirectUrl, $scope = null, $APIBaseUrl = null, $APIOAuthUrl = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->redirectUrl = $redirectUrl;
        $this->scope = $scope;
        $this->accessTokenManager = $accessTokenManager;

        $config = [
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
        ];

        if (!is_null($APIBaseUrl)) {
            $config['api_base_url'] = $APIBaseUrl;
        }

        if (!is_null($APIOAuthUrl)) {
            $config['oauth_base_url'] = $APIOAuthUrl;
        }

        if ($accessTokenManager->hasAccessToken()) {
            $accessToken = $accessTokenManager->getAccessToken();

            if ($accessToken && $accessToken->isValid()) {
                $config['access_token'] = $accessToken->getSDKAccessToken();
            }
        }

        $this->mailxpert = new Mailxpert(
            $config
        );
    }

    /**
     * @return Mailxpert
     */
    public function getMailxpert()
    {
        return $this->mailxpert;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        $scope = explode(',', $this->scope);

        return $this->getMailxpert()->getLoginHelper()->getLoginUrl($this->redirectUrl, $scope);
    }

    /**
     * @return SDKAccessToken
     */
    public function retrieveAccessToken()
    {
        return $this->getMailxpert()->getLoginHelper()->getAccessToken($this->redirectUrl);
    }

    /**
     * @param SDKAccessToken $accessToken
     */
    public function setAccessToken(SDKAccessToken $accessToken)
    {
        $this->getMailxpert()->setAccessToken($accessToken);
    }

    /**
     * @param AccessTokenInterface|null $accessToken
     *
     * @return AccessTokenInterface
     * @throws MailxpertAPIBundleException
     */
    public function refreshAccessToken(AccessTokenInterface $accessToken = null)
    {
        if (is_null($accessToken)) {
            if ($this->getAccessTokenManager()->hasAccessToken()) {
                $accessToken = $this->getAccessTokenManager()->getAccessToken();
            }
        }

        if (!$accessToken) {
            throw new MailxpertAPIBundleException('No access token object provided or stored in session');
        }

        $SDKAccessToken = $this->getMailxpert()->getLoginHelper()->refreshAccessToken($accessToken->getSDKAccessToken(), $this->redirectUrl);

        return $this->getAccessTokenManager()->updateAccessToken($accessToken, $SDKAccessToken);
    }

    /**
     * @return AccessTokenManagerInterface
     */
    private function getAccessTokenManager()
    {
        return $this->accessTokenManager;
    }


}