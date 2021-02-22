<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/{vueRouting}", name="vue", requirements={"vueRouting"="^(?!.*_wdt|_profiler|api|static).+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vueRouting(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('base.html.twig', [
            'google_analytics_id' => null,
            'matomo_url' => null,
            'matomo_site_id' => null,
            'matomo_script_url' => null
        ]);
    }
}
