<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @Route("/test", name="Test")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        return $this->render('main.html.twig');
    }

}

/*/*
require_once '../vendor/autoload.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.example.org', 25))
->setUsername('Test1')
->setPassword('abcdef')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Test'))
->setFrom(['john@doe.com' => 'Pan Test'])
->setTo(['receiver@domain.org', 'other@domain.org' => 'Odbiorca'])
->setBody('To wcale nie jest spam')
;

// Send the message
$result = $mailer->send($message);

    /*
    public function indexAction($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('mateusztrojanowski37@gmail.com')
            ->setTo('mateusztrojanowski37@gmail.com')
            ->setBody("test",
                'text/plain'
            );

            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )


        $mailer->send($message);

        // or, you can also fetch the mailer service this way
        // $this->get('mailer')->send($message);

    }

*/