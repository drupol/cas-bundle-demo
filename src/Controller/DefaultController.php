<?php

declare(strict_types=1);

namespace App\Controller;

use drupol\psrcas\CasInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function defaultVars(CasInterface $casProtocol, Request $request)
    {
        $welcome = 'Welcome, guest !';

        if (null !== $user = $this->getUser()) {
            /** @var \drupol\EuloginBundle\Security\Core\User\EuloginUser $user */
            $welcome = sprintf(
                'Welcome back, %s !',
                null === $user->getFirstName() ? $user->getUsername() : $user->getFirstName() . ' ' . ucfirst(mb_strtolower($user->getLastName()))
            );
        }

        return [
            'welcome' => $welcome,
            'properties' => $casProtocol->getProperties(),
            'server' => $request->server,
            'session' => $request->getSession()->all(),
            'auth' => null !== $this->getUser(),
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
