<?php

namespace Mailxpert\APIBundle\Model;


abstract class AccessTokenManager implements AccessTokenManagerInterface
{
    /**
     * @inheritdoc
     */
    public function createAccessToken()
    {
        $class = $this->getClass();
        $accessToken = new $class;

        return $accessToken;
    }

    /**
     * @param $accessToken
     *
     * @return AccessTokenInterface
     */
    public function findByAccessToken($accessToken)
    {
        return $this->findAccessTokenBy(['accessToken' => $accessToken]);
    }
}