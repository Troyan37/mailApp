<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity\Email;
use AppBundle\Entity\Entity\Email_has_tag;
use AppBundle\Entity\Entity\Mailing;
use AppBundle\Entity\Entity\Mailing_has_tag;
use AppBundle\Entity\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SplFixedArray;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AutoSendController extends Controller
{
    /**
     * @Route("/auto", name="auto")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

   /* public function initAction(){

        // Create the Transport
        $transport = (new Swift_SmtpTransport('email-smtp.eu-west-1.amazonaws.com', 587, 'tls'))
            ->setUsername('AKIAIAFOOIBNVM42XSOA')
            ->setPassword('AloWEJ5vjmm80DfuQXI1dlle/R6KSZDFDdKpxLj7PzYz')
            ->setStreamOptions([
                'ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]);
        $mailer = new Swift_Mailer($transport);


        //call every minute?
        //while(true) {
            //sleep(300);
            $this->sendAction($mailer);
        //}

        //temp
        sleep(2);
        return $this->render('main.html.twig');
    }*/


    public function sendAction()
    {


        //pobierz z bazy

        $entityManager = $this->getDoctrine()->getManager();

        $tagMailingRepository = $this->getDoctrine()->getRepository(Mailing_has_tag::class);
        $mailingRepository = $this->getDoctrine()->getRepository(Mailing::class);
        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);
        $emailTagRepository = $this->getDoctrine()->getRepository(Email_has_tag::class);
        $emailRepository = $this->getDoctrine()->getRepository(Email::class);


        $mailing = $mailingRepository->findOneBy(array('status' => 'A'));

        if ($mailing != null) {

            $mailing->setStatus('S');
            $entityManager->flush();

            $mailingId = $mailing->getId();
            $topic = $mailing->getTopic();
            $content = $mailing->getContent();
            $fromEmail = $mailing->getFromEmail();

            $tag = $tagMailingRepository->findBy(array('mailingMailingId' => $mailingId)); //TODO: obluga wielu tagow

            //$tagIds = null;
            //$tagName = null;
            $emailList = array();

            //Sprawdz czy jest wiecej niz jeden tag
            if (sizeof($tag) > 1) {
                //wiele tagow
                $tagIds = array();
                foreach ($tag as $t) {
                    array_push($tagIds, $tagRepository->find($t));
                }

                //TODO: obsluga wielu tagow

            } else if(sizeof($tag) == 1) {
                //jeden tag
                $tagId = $tag[0]->getTagTagId();
                $emailTagPair = $emailTagRepository->findBy(array('tagTagId' => $tagId));


                foreach($emailTagPair as $i){

                    $singleEmailAddress =  $emailRepository->find($i->getEmailEmailId())->getEmailAddress();
                    array_push($emailList,$singleEmailAddress);
                }

            }else{

                throw $this->createNotFoundException(
                    'No tag found'
                );
            }

            // Create the Transport
            $transport = (new Swift_SmtpTransport('email-smtp.eu-west-1.amazonaws.com', 587, 'tls'))
                ->setUsername('AKIAIAFOOIBNVM42XSOA')
                ->setPassword('AloWEJ5vjmm80DfuQXI1dlle/R6KSZDFDdKpxLj7PzYz')
                ->setStreamOptions([
                    'ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]);
            $mailer = new Swift_Mailer($transport);


            foreach($emailList as $address){

                $message = (new Swift_Message($topic))
                    ->setFrom([$fromEmail])
                    ->setTo([$address])
                    ->setBody($content);

                sleep(2);
                $mailer->send($message);

            }

            $mailing->setStatus('X');
            $entityManager->flush();


        }
        return $this->render('main.html.twig');
    }


}
