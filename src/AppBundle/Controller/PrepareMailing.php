<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entity\Mailing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PrepareMailing extends Controller
{

    /**
     * @Route("/prep", name="prep")
     * @return Response
     */
    public function checkAction()
    {

        //sprawdz czy sa jakies gotowe do wyslania - uruchom co 10 minut

        $entityManager = $this->getDoctrine()->getManager();
        $mailingRepository = $this->getDoctrine()->getRepository(Mailing::class);
        $mailing = $mailingRepository->findOneBy(array('status' => 'A'));

        if ($mailing != null) {

            $mailing->setStatus('S');
            $entityManager->flush();

            }
            return new Response('success');
    }
}
