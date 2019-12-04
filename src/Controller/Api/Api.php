<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\DefaultController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Api.
 */
class Api extends DefaultController
{
    /**
     * @Route("/api", name="page_api")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $variables = $this->defaultVars($request);
        $variables['user'] = (array) $variables['user'];

        return new JsonResponse($variables);
    }
}
