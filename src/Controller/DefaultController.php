<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function defaultVars(Request $request)
    {
        /** @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag $parameter_bag */
        $parameter_bag = $this->container->get('parameter_bag');

        /** @var \EcPhp\CasBundle\Security\Core\User\CasUser $user */
        $user = $this->getUser();

        return [
            'properties' => $parameter_bag->get('cas'),
            'server' => $request->server->all(),
            'session' => $request->getSession()->all(),
            'user' => $user,
        ];
    }

    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', $this->defaultVars($request));
    }
}
