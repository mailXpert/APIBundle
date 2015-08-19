<?php
/**
 * Gasverband.
 * Date: 19/08/15
 */

namespace Mailxpert\APIBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateAccessTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailxpert:api:access_token:create')
            ->setDescription('Create a persistent mailXpert Access Token in the database')
            ->setDefinition([
                new InputOption('accessToken', '', InputOption::VALUE_REQUIRED, 'Access token'),
                new InputOption('refreshToken', '', InputOption::VALUE_REQUIRED, 'Refresh token'),
                new InputOption('expireAt', '', InputOption::VALUE_REQUIRED, 'Expire at'),
                new InputOption('refreshTokenExpireAt', '', InputOption::VALUE_REQUIRED, 'Expire at'),
                new InputOption('scope', '', InputOption::VALUE_OPTIONAL, 'scope'),
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accessToken = $this->getContainer()->get('mailxpert_api.access_token_manager')->createAccessToken();

        $accessToken->setAccessToken($input->getOption('accessToken'));
        $accessToken->setRefreshToken($input->getOption('refreshToken'));
        $accessToken->setExpireAt($input->getOption('expireAt'));
        $accessToken->setRefreshTokenExpireAt($input->getOption('refreshTokenExpireAt'));
        $accessToken->setScope($input->getOption('scope'));

        $this->getContainer()->get('doctrine')->getManager()->persist($accessToken);
        $this->getContainer()->get('doctrine')->getManager()->flush();

        $output->writeln(sprintf('<info>Access token successfully created with ID %s</info>', $accessToken->getId()));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Save a mailXpert Access Token (obtain a valid Access Token via the Developer console at https://dev.mailxpert.ch)');

        $this->askQuestion($input, $output, 'accessToken', 'access token');
        $this->askQuestion($input, $output, 'refreshToken', 'refresh token');
        $this->askQuestion($input, $output, 'expireAt', 'expire at value');
        $this->askQuestion($input, $output, 'refreshTokenExpireAt', 'refresh token expire at value');
//        $this->askQuestion($input, $output, 'scope', 'scope');
    }

    private function askQuestion(InputInterface $input, OutputInterface $output, $option, $optionName)
    {
        $questionHelper = $this->getQuestionHelper();

        $value = null;

        try {
            $value = $input->getOption($option) ? $input->getOption($option) : null;
        } catch (\Exception $error) {
            $output->writeln($error->getMessage());
        }

        if (null === $value) {
            while ($value === null) {
                $question = new Question(sprintf("Enter the %s: ", $optionName));
                $value = $questionHelper->ask($input, $output, $question);
            }
            $input->setOption($option, $value);
        }
    }

    protected function getQuestionHelper()
    {
        $question = $this->getHelperSet()->get('question');
        if (!$question) {
            $this->getHelperSet()->set($question = new QuestionHelper());
        }

        return $question;
    }
}