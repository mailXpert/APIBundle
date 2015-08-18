<?php

namespace Mailxpert\APIBundle\Model;


interface AccessTokenInterface
{
    /**
     * @return mixed Get accessToken model entity for persistent models
     */
    public function getId();

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
     * @return int Expire at
     */
    public function getExpireAt();

    /**
     * @param int $expireAt Expire at
     *
     * @return self
     */
    public function setExpireAt($expireAt);

    /**
     * @return int Refresh Token expire at
     */
    public function getRefreshTokenExpireAt();

    /**
     * @param int $expireAt Refresh Token expire at
     *
     * @return self
     */
    public function setRefreshTokenExpireAt($expireAt);

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

    /**
     * Check if the access token is still valid
     *
     * @return boolean
     */
    public function isValid();
}