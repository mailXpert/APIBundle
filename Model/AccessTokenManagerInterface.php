<?php

namespace Mailxpert\APIBundle\Model;

use Mailxpert\Authentication\AccessToken as SDKAccessToken;

interface AccessTokenManagerInterface
{
    /**
     * Create an Access Token
     *
     * @return AccessTokenInterface
     */
    public function createAccessToken();

    /**
     * Delete an Access Token
     *
     * @param AccessTokenInterface $accessToken The Access token to delete
     *
     * @return void
     */
    public function deleteAccessToken(AccessTokenInterface $accessToken);

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Finds one access token by the given criteria.
     *
     * @param array $criteria
     *
     * @return AccessTokenInterface
     */
    public function findAccessTokenBy(array $criteria);

    /**
     * Finds one access token by the given criteria.
     *
     * @param array $criteria
     *
     * @return AccessTokenInterface[]
     */
    public function findBy(array $criteria);

    /**
     * Check if there is already an access token in the session
     *
     * @return boolean
     */
    public function hasAccessToken();

    /**
     * Get access token from the session
     *
     * @return AccessTokenInterface
     */
    public function getAccessToken();

    /**
     * Store access token in session
     *
     * @param AccessTokenInterface $accessToken
     *
     * @return void
     */
    public function setAccessToken(AccessTokenInterface $accessToken);

    /**
     * Update local access token with valid access token and new refresh token
     *
     * @param SDKAccessToken $accessToken
     *
     * @return AccessTokenInterface
     */
    public function updateAccessToken(SDKAccessToken $accessToken);
}