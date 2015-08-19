<?php
/**
 * Gasverband.
 * Date: 19/08/15
 */

namespace Mailxpert\APIBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateRefreshTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailxpert:api:access_token:refresh')
            ->setDescription('Refresh access tokens before the refresh token is expired')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Execute the refreshment of access tokens')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accessTokens = $this->getContainer()->get('mailxpert_api.access_token_manager')->findAlmostExpiredTokens();

        foreach ($accessTokens as $accessToken) {
            $date = new \DateTime();
            $date->setTimestamp($accessToken->getRefreshTokenExpireAt());

            $output->writeln(sprintf('<info>Token %s is almost expired (%s)</info>', $accessToken->getAccessToken(), $date->format('d-m-Y H:i:s')));

            if ($input->getOption('force')) {
                $newAccessToken = $this->getContainer()->get('mailxpert_api.manager')->refreshAccessToken($accessToken);
                $date->setTimestamp($newAccessToken->getRefreshTokenExpireAt());
                $output->writeln(sprintf('<comment>Token %s refreshed and valid until %s</comment>', $newAccessToken->getAccessToken(), $date->format('d-m-Y H:i:s')));
            }
        }

        if (!$input->getOption('force')) {
            $output->writeln('<comment>To proceed with the update, use the --force</comment>');
        }
    }
}