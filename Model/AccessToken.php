<?php

namespace Mailxpert\APIBundle\Model;

/**
 * Class AccessToken
 *
 * @package Mailxpert\APIBundle\Model
 */
class AccessToken implements AccessTokenInterface
{
    protected $id;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * @var string
     */
    protected $expireAt;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $accessToken
     *
     * @return AccessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $refreshToken
     *
     * @return AccessToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @param string $expireAt
     *
     * @return AccessToken
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * @param string|null $scope
     *
     * @return AccessToken
     */
    public function setScope($scope = null)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function hasScope($scope)
    {
        $scopes = explode(',', $this->getScope());

        return in_array($scope, $scopes);
    }

    public function __toString()
    {
        return (string) $this->getAccessToken();
    }
}