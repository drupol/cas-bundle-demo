<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\DefaultController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Api.
 */
class Api extends DefaultController
{
    /**
     * @Route("/api/test", name="api_test")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function apiTest()
    {
        if (null === $this->getUser()) {
            return new Response('Denied', 403);
        }

        return new JsonResponse(['data' => 'It works!', 'auth' => (null !== $this->getUser()) ? 'yes' : 'no']);
    }
}
