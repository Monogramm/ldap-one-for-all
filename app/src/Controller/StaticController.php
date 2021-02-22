<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    /**
     * @Route("/static/pwa", name="pwa")
     *
     * @return Response
     */
    public function pwa(): Response
    {
        $response = new Response();

        $charset = $response->getCharset() ?: 'UTF-8';
        $response->headers->set('Content-Type', 'text/javascript; charset='.$charset);

        return $this->render('pwa.js.twig', [
            // TODO Generate VAPID for push notifications
            'vapid_public_key' => null
        ], $response);
    }
}
