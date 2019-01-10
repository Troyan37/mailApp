<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity\Mailing;
use AppBundle\Entity\Entity\Mailing_has_tag;
use AppBundle\Entity\Entity\Tag;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MailingController extends Controller
{
    /**
     * @Route("/mail", name="mail")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function sendAction()
    {

        /**
         * @Assert\NotBlank
         */
        $topic = $_POST['name'];
        $content = $_POST['message'];
        $tagName = $_POST['email'];

        //$fromEmail = $_POST['fromEmail'];
        $fromEmail = "troyan123@engineer.com";
        $status = 'N';


        //Stworz mailing na podstawie danych z formularza

        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);
        $mailingRepository = $this->getDoctrine()->getRepository(Mailing::class);

        $mailing = new Mailing();
        $mailing_tag = new Mailing_has_tag();
        $tag = $tagRepository->findOneBy(array('tagName' => $tagName));

        if (!$tag) {
            throw $this->createNotFoundException(
                'No tag found'
            );
        }

        $mailing->setTopic($topic);
        $mailing->setContent($content);
        $mailing->setFromEmail($fromEmail);
        $mailing->setStatus($status);

        $entityManager->persist($mailing);

        $entityManager->flush();

        //Dodanie Mailing_tag

        $mailing = $mailingRepository->findOneBy(array('content' => $content, 'topic' => $topic, 'fromEmail' => $fromEmail, 'status' => 'N'));

        $mailingId = $mailing->getId();
        $tagId = $tag->getId();


        $mailing_tag->setMailingMailingId($mailingId);
        $mailing_tag->setTagTagId($tagId);

        $entityManager->persist($mailing_tag);
        $entityManager->flush();


        //zmiana statusu z New na Awaiting
        $mailing->setStatus('A');
        $entityManager->flush();


        return $this->render('main.html.twig');
    }


}
