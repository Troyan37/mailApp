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

        /*
        You could alternatively use a different transport such as Sendmail:

        // Sendmail
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        */

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Testing SES'))
            ->setFrom(['troyan123@engineer.com'])
            ->setTo(['mateusztrojanowski37@gmail.com'])//tutaj mozna wpisac wiele?
            ->setBody('Test AWS');

// Send the message - disable for testing
        //$result = $mailer->send($message);
        $mailer->send($message);


        //printf("Sent %d messages\n", $result);

/*
        if ($mailer->send($message)) {
            echo "Sent\n";
        } else {
            echo "Failed\n";
        }
*/
        return $this->render('main.html.twig');
    }


}
