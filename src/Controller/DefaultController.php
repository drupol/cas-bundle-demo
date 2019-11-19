<?php

declare(strict_types=1);

namespace App\Controller;

use drupol\psrcas\CasInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{

    /**
     * @param \drupol\psrcas\CasInterface $casProtocol
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function defaultVars(CasInterface $casProtocol, Request $request)
    {
        return [
            'properties' => $casProtocol->getProperties(),
            'server' => $request->server,
            'session' => $request->getSession()->all(),
            'user' => $this->getUser(),
        ];
    }

    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     * @param CasInterface $casProtocol
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, CasInterface $casProtocol)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $this->defaultVars($casProtocol, $request));
    }
}
