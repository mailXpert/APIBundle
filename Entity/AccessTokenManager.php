<?php

namespace Mailxpert\APIBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Mailxpert\APIBundle\Exceptions\MailxpertAPIBundleException;
use Mailxpert\APIBundle\Model\AccessToken;
use Mailxpert\APIBundle\Model\AccessTokenInterface;
use Mailxpert\APIBundle\Model\AccessTokenManager as BaseAccessTokenManager;
use Mailxpert\Authentication\AccessToken as SDKAccessToken;
use Symfony\Component\HttpFoundation\Session\Session;

class AccessTokenManager extends BaseAccessTokenManager
{
    /**
     * @var string $class The AccessToken class
     */
    private $class;

    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var Session
     */
    private $session;

    /**
     * AccessTokenManager constructor.
     *
     * @param Registry $doctrine
     * @param Session  $session
     * @param string   $class
     */
    public function __construct(Registry $doctrine, Session $session, $class)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $criteria
     *
     * @return AccessTokenInterface|null
     */
    public function findAccessTokenBy(array $criteria)
    {
        return $this->getDoctrine()->getRepository($this->getClass())->findOneBy($criteria);
    }

    /**
     * @param array $criteria
     *
     * @return AccessTokenInterface[]|null
     */
    public function findBy(array $criteria)
    {
        return $this->getDoctrine()->getRepository($this->getClass())->findBy($criteria);
    }

    /**
     * @param int $time
     *
     * @return AccessTokenInterface[]|null
     */
    public function findAlmostExpiredTokens($time = 604800)
    {
        $repository = $this->getDoctrine()->getRepository($this->getClass());

        $query = $repository->createQueryBuilder('a')
            ->where('a.refreshTokenExpireAt > :expired')
            ->setParameter('expired', time())
            ->andWhere('a.refreshTokenExpireAt < :expiration')
            ->setParameter('expiration', time() + $time)
            ->getQuery()
        ;

        return $query->getResult();
    }

    /**
     * @param AccessTokenInterface $accessToken
     */
    public function deleteAccessToken(AccessTokenInterface $accessToken)
    {
        $this->getDoctrine()->getManager()->remove($accessToken);
        $this->getDoctrine()->getManager()->flush();
    }

    public function hasAccessToken()
    {
        return (bool) false !== $this->getSession()->get('mailxpert/apibundle/access_token_id', false);
    }

    /**
     * @throws MailxpertAPIBundleException
     *
     * @return AccessTokenInterface
     */
    public function getAccessToken()
    {
        $accessTokenId = $this->getSession()->get('mailxpert/apibundle/access_token_id');

        if (!$accessTokenId) {
            throw new MailxpertAPIBundleException('No access token ID stored in the session');
        }

        $accessToken = $this->findAccessTokenBy(['id' => $accessTokenId]);

        if (!$accessToken) {
            throw new MailxpertAPIBundleException(sprintf('No access token found with ID %s', $accessTokenId));
        }

        return $accessToken;
    }

    /**
     * @param AccessTokenInterface $accessToken
     */
    public function setAccessToken(AccessTokenInterface $accessToken)
    {
        $this->getSession()->set('mailxpert/apibundle/access_token_id', $accessToken->getId());
    }

    /**
     * @param AccessTokenInterface $accessToken
     * @param SDKAccessToken       $SDKAccessToken
     *
     * @return AccessTokenInterface
     */
    public function updateAccessToken(AccessTokenInterface $accessToken, SDKAccessToken $SDKAccessToken)
    {
        $accessToken->setAccessToken($SDKAccessToken->getAccessToken());
        $accessToken->setExpireAt($SDKAccessToken->getExpiresAt());
        $accessToken->setRefreshToken($SDKAccessToken->getRefreshToken());
        $accessToken->setRefreshTokenExpireAt($SDKAccessToken->getRefreshTokenExpiresAt());
        $accessToken->setScope($SDKAccessToken->getScope());

        $this->getDoctrine()->getManager()->flush();

        return $accessToken;
    }


    /**
     * @return Registry
     */
    private function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @return Session
     */
    private function getSession()
    {
        return $this->session;
    }
}