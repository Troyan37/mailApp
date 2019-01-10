<?php

namespace AppBundle\Command;

use AppBundle\Entity\Entity\Email;
use AppBundle\Entity\Entity\Email_has_tag;
use AppBundle\Entity\Entity\Mailing;
use AppBundle\Entity\Entity\Mailing_has_tag;
use AppBundle\Entity\Entity\MasterIndex;
use AppBundle\Entity\Entity\Tag;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class AutoSendCommand extends ContainerAwareCommand
{

    protected static $defaultName = 'app:auto-send';
    
    public function execute(InputInterface $input, OutputInterface $output)
    {

        //pobierz z bazy i wyslij jedna wiadomosc - uruchom co 1 minute
        $shouldSend = true;
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $tagMailingRepository = $this->getContainer()->get('doctrine')->getRepository(Mailing_has_tag::class);
        $mailingRepository = $this->getContainer()->get('doctrine')->getRepository(Mailing::class);
        $tagRepository = $this->getContainer()->get('doctrine')->getRepository(Tag::class);
        $emailTagRepository = $this->getContainer()->get('doctrine')->getRepository(Email_has_tag::class);
        $emailRepository = $this->getContainer()->get('doctrine')->getRepository(Email::class);
        $masterIndexRepository = $this->getContainer()->get('doctrine')->getRepository(MasterIndex::class);


        $mailing = $mailingRepository->findOneBy(array('status' => 'S'));

        if ($mailing != null) {

            $masterIndex = $masterIndexRepository->find(0)->getMaster();

            $mailingId = $mailing->getId();
            $topic = $mailing->getTopic();
            $content = $mailing->getContent();
            $fromEmail = $mailing->getFromEmail();
            $singleEmailAddress = null;

            $tag = $tagMailingRepository->findBy(array('mailingMailingId' => $mailingId)); //TODO: obluga wielu tagow

            //$tagIds = null;
            //$tagName = null;
            //$emailList = array();

            //Sprawdz czy jest wiecej niz jeden tag
            if (sizeof($tag) > 1) {
                //wiele tagow
                $tagIds = array();
                foreach ($tag as $t) {
                    array_push($tagIds, $tagRepository->find($t));
                }

                //TODO: obsluga wielu tagow

            } else if (sizeof($tag) == 1) {
                //jeden tag
                $tagId = $tag[0]->getTagTagId();
                $emailTagPair = $emailTagRepository->findBy(array('tagTagId' => $tagId));

                if ($masterIndex < sizeof($emailTagPair)) {

                    $i = $emailTagPair[$masterIndex];
                    $singleEmailAddress = $emailRepository->find($i->getEmailEmailId())->getEmailAddress();

                    $masterIndexRepository->find(0)->setMaster($masterIndex+1);
                } else {

                    $mailing->setStatus('X');
                    $shouldSend = false;
                    $masterIndexRepository->find(0)->setMaster(0);
                }


            } else {
                   // 'No tag found'
            }


            if ($shouldSend) {
                $transport = (new Swift_SmtpTransport('email-smtp.eu-west-1.amazonaws.com', 587, 'tls'))
                    ->setUsername('AKIAIAFOOIBNVM42XSOA')
                    ->setPassword('AloWEJ5vjmm80DfuQXI1dlle/R6KSZDFDdKpxLj7PzYz')
                    ->setStreamOptions([
                        'ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]);
                $mailer = new Swift_Mailer($transport);


                $message = (new Swift_Message($topic))
                    ->setFrom([$fromEmail])
                    ->setTo([$singleEmailAddress])
                    ->setBody($content);

                $mailer->send($message);
            }

            $entityManager->flush();
        }
    }


}
