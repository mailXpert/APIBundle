<?php

namespace Mailxpert\APIBundle\Model;

use Mailxpert\Authentication\AccessToken as SDKAccessToken;

class AccessToken implements AccessTokenInterface
{
    public const VALID_OFFSET = 300;

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
     * @var int
     */
    protected $expireAt;

    /**
     * @var int
     */

    protected $refreshTokenExpireAt;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getAccessToken();
    }

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
     * @return int
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (bool) (time() < $this->getExpireAt());
    }

    public function isValidWithOffset($offset = self::VALID_OFFSET)
    {
        return time() < ($this->getExpireAt() - $offset);
    }

    /**
     * @return int
     */
    public function getRefreshTokenExpireAt()
    {
        return $this->refreshTokenExpireAt;
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
     * @param int $expireAt
     *
     * @return AccessToken
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * @param int $refreshTokenExpireAt
     *
     * @return AccessToken
     */
    public function setRefreshTokenExpireAt($refreshTokenExpireAt)
    {
        $this->refreshTokenExpireAt = $refreshTokenExpireAt;

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

    /**
     * @return SDKAccessToken
     */
    public function getSDKAccessToken()
    {
        return new SDKAccessToken(
            $this->getAccessToken(),
            $this->getRefreshToken(),
            $this->getExpireAt(),
            $this->getScope(),
            $this->getRefreshTokenExpireAt()
        );
    }


}
