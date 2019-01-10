<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Entity\Email;


class AddEmailController extends Controller
{
    /**
     * @Route("/addEmail", name="AddEmail")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction()
    {

        $entityManager = $this->getDoctrine()->getManager();

        $email = new Email();
        $email->setEmailAddress("test123@interia.pl");

        $entityManager->persist($email);

        $entityManager->flush();

        return $this->render('main.html.twig');
    }

}

