<?php

namespace Acme\SubscribeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Hello World command for demo purposes.
 *
 * You could also extend from Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
 * to get access to the container via $this->getContainer().
 *
 * @author Tobias Schultze <http://tobion.de>
 */
class SendMessCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mail:send')
            ->setDescription('Sending messages');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sender = $this->getApplication()->getKernel()->getContainer()->get('mailt.sendering');
        $sender->send();
    }
}