<?php

namespace Mailxpert\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OAuthController extends Controller
{
    public function indexAction()
    {
        $url = $this->get('mailxpert_api.manager')->getLoginUrl();

        return $this->render('MailxpertAPIBundle:OAuth:index.html.twig', array('url' => $url));
    }

    public function codeAction()
    {
        $accessToken = $this->get('mailxpert_api.manager')->retrieveAccessToken();

        $localAccessToken = $this->get('mailxpert_api.access_token_manager')->findByAccessToken($accessToken->getAccessToken());

        if (!$localAccessToken) {
            $localAccessToken = $this->get('mailxpert_api.access_token_manager')->createAccessToken();
            $localAccessToken->setAccessToken($accessToken->getAccessToken());
            $localAccessToken->setExpireAt($accessToken->getExpiresAt());

            $this->getDoctrine()->getManager()->persist($localAccessToken);
        }

        $localAccessToken->setRefreshToken($accessToken->getRefreshToken());
        $localAccessToken->setRefreshTokenExpireAt($accessToken->getRefreshTokenExpiresAt());
        $localAccessToken->setScope($accessToken->getScope());

        $this->getDoctrine()->getManager()->flush();

        $this->get('mailxpert_api.access_token_manager')->setAccessToken($localAccessToken);

        $expireAt = new \DateTime();
        $expireAt->setTimestamp($accessToken->getExpiresAt());

        return $this->render('MailxpertAPIBundle:OAuth:oauth.html.twig', array('expireAt' => $expireAt));
    }
}