<?php

namespace Mailxpert\APIBundle\Util;


use Mailxpert\APIBundle\Model\AccessTokenManagerInterface;
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
     */
    public function __construct(AccessTokenManagerInterface $accessTokenManager, $appId, $appSecret, $redirectUrl, $scope = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->redirectUrl = $redirectUrl;
        $this->scope = $scope;
        $this->accessTokenManager = $accessTokenManager;

        $config =  [
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret
        ];

        if ($accessTokenManager->hasAccessToken()) {
            $accessToken = $accessTokenManager->getAccessToken();

            if ($accessToken && $accessToken->isValid()) {
                $config['access_token'] = $accessToken->getAccessToken();
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
     * @return AccessToken
     */
    public function retrieveAccessToken()
    {
        return $this->getMailxpert()->getLoginHelper()->getAccessToken($this->redirectUrl);
    }

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->getMailxpert()->setAccessToken($accessToken);
    }

    /**
     * @return \Mailxpert\APIBundle\Model\AccessTokenInterface|null
     */
    public function refreshAccessToken()
    {
        if ($this->getAccessTokenManager()->hasAccessToken()) {
            $localAccessToken = $this->getAccessTokenManager()->getAccessToken();

            if ($localAccessToken->isValid()) {
                return $localAccessToken;
            }

            $perishedAccessToken = new AccessToken(
                $localAccessToken->getAccessToken(),
                $localAccessToken->getRefreshToken(),
                $localAccessToken->getExpireAt(),
                $localAccessToken->getScope(),
                $localAccessToken->getRefreshTokenExpireAt()
            );

            $accessToken = $this->getMailxpert()->getLoginHelper()->refreshAccessToken($perishedAccessToken, $this->redirectUrl);

            return $this->getAccessTokenManager()->updateAccessToken($accessToken);
        }

        return null;
    }

    /**
     * @return AccessTokenManagerInterface
     */
    private function getAccessTokenManager()
    {
        return $this->accessTokenManager;
    }


}