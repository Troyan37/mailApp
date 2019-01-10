<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MailController extends Controller
{
    /**
     * @Route("/testSend", name="testSend")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function sendAction()
    {
        // Create the Transport
        $transport = (new Swift_SmtpTransport('email-smtp.eu-west-1.amazonaws.com', 587, 'tls'))
            ->setUsername('AKIAIAFOOIBNVM42XSOA')
            ->setPassword('AloWEJ5vjmm80DfuQXI1dlle/R6KSZDFDdKpxLj7PzYz')
            ->setStreamOptions([
                'ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]);


// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Testing SES'))
            ->setFrom(['troyan123@engineer.com'])
            ->setTo(['mateusztrojanowski37@gmail.com'])
            ->setBody('Test AWS');

// Send the message
        $mailer->send($message);


        return $this->render('main.html.twig');
    }


}
