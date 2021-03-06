<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity\Email;
use AppBundle\Entity\Entity\Email_has_tag;
use AppBundle\Entity\Entity\Mailing;
use AppBundle\Entity\Entity\Mailing_has_tag;
use AppBundle\Entity\Entity\MasterIndex;
use AppBundle\Entity\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SplFixedArray;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Tests\Fixtures\DeprecatedClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AutoSendController extends Controller
{
    /**
     *
     * @return Response
     */

    public function sendAction()
    {


        //pobierz z bazy i wyslij jedna wiadomosc - uruchom co 1 minute

        $shouldSend = true;
        $entityManager = $this->getDoctrine()->getManager();

        $tagMailingRepository = $this->getDoctrine()->getRepository(Mailing_has_tag::class);
        $mailingRepository = $this->getDoctrine()->getRepository(Mailing::class);
        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);
        $emailTagRepository = $this->getDoctrine()->getRepository(Email_has_tag::class);
        $emailRepository = $this->getDoctrine()->getRepository(Email::class);
        $masterIndexRepository = $this->getDoctrine()->getRepository(MasterIndex::class);


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

                //array_push($emailList,$singleEmailAddress);

            } else {

                throw $this->createNotFoundException(
                    'No tag found'
                );
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
            return new Response('success');
        }
    }


}
