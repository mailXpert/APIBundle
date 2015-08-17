<?php

namespace Mailxpert\APIBundle\Model;


interface AccessTokenInterface
{
    /**
     * @return string mailXpert API Access Token
     */
    public function getAccessToken();

    /**
     * @param string $accessToken mailXpert API Access Token
     *
     * @return self
     */
    public function setAccessToken($accessToken);

    /**
     * @return string mailXpert API Refresh Token
     */
    public function getRefreshToken();

    /**
     * @param string $refreshToken mailXpert API Refresh Token
     *
     * @return self
     */
    public function setRefreshToken($refreshToken);

    /**
     * @return string Expire at
     */
    public function getExpireAt();

    /**
     * @param string $expireAt Expire at
     *
     * @return self
     */
    public function setExpireAt($expireAt);

    /**
     * @return string Access token scope
     */
    public function getScope();

    /**
     * @param string|null $scope Access token scope
     *
     * @return self
     */
    public function setScope($scope = null);

    /**
     * @param string $scope Scope to check
     *
     * @return boolean
     */
    public function hasScope($scope);
}