<?php

namespace AppBundle\Command;

use AppBundle\Entity\Entity\Mailing;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareMailingCommand extends ContainerAwareCommand
{

    protected static $defaultName = 'app:prepare-mailing';

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //sprawdz czy sa jakies gotowe do wyslania - uruchom co 10 minut

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $mailingRepository = $this->getContainer()->get('doctrine')->getRepository(Mailing::class);
        $mailing = $mailingRepository->findOneBy(array('status' => 'A'));

        if ($mailing != null) {

            $mailing->setStatus('S');
            $entityManager->flush();

        }

    }
}
