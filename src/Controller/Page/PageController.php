<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Controller\DefaultController;
use App\Form\Type\PgtRequest;
use App\Form\Type\PgtValidate;
use drupol\psrcas\CasInterface;
use drupol\psrcas\Introspection\Contract\Proxy;
use drupol\psrcas\Introspection\Contract\ProxyValidate;
use drupol\psrcas\Introspection\Introspector;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PageController extends DefaultController
{
    /**
     * @Route("/page/denied", name="page_denied")
     *
     * @param Request $request
     * @param CasInterface $casProtocol
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageDeniedAction(Request $request, CasInterface $casProtocol)
    {
        if (null === $this->getUser()) {
            return new Response('Denied', 403);
        }

        return $this->render('page/denied.html.twig', $this->defaultVars($casProtocol, $request));
    }

    /**
     * @Route("/page/forcelogin", name="page_forcelogin")
     *
     * @param Request $request
     * @param CasInterface $cas
     * @param TokenStorageInterface $tokenStorage
     *
     * @return \Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response|null
     */
    public function pageForceLoginAction(Request $request, CasInterface $cas, TokenStorageInterface $tokenStorage)
    {
        if (null !== $response = $cas->login(['service' => $request->getUri(), 'renew' => true])) {
            $tokenStorage->setToken();

            return $response;
        }

        return $this->render('page/forcelogin.html.twig', $this->defaultVars($cas, $request));
    }

    /**
     * @Route("/page/gateway", name="page_gateway")
     *
     * @param Request $request
     * @param CasInterface $cas
     * @param TokenStorageInterface $tokenStorage
     *
     * @return \Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response|null
     */
    public function pageGatewayAuth(Request $request, CasInterface $cas, TokenStorageInterface $tokenStorage)
    {
        if (null !== $response = $cas->login(['service' => $request->getUri(), 'gateway' => true])) {
            $tokenStorage->setToken();

            return $response;
        }

        return $this->render('page/gateway.html.twig', $this->defaultVars($cas, $request));
    }

    /**
     * @Route("/page/restricted", name="page_restricted")
     *
     * @param Request $request
     * @param CasInterface $casProtocol
     *
     * @return \Psr\Http\Message\ResponseInterface|\Symfony\Component\HttpFoundation\Response|null
     */
    public function pageRestrictedAction(Request $request, CasInterface $casProtocol)
    {
        if (null === $this->getUser()) {
            if (null !== $response = $casProtocol->login(['service' => $request->getUri()])) {
                return $response;
            }
        }

        return $this->render('page/restricted.html.twig', $this->defaultVars($casProtocol, $request));
    }

    /**
     * @Route("/page/simple", name="page_simple")
     *
     * @param Request $request
     * @param CasInterface $casProtocol
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageSimpleAction(Request $request, CasInterface $casProtocol)
    {
        return $this->render('page/simple.html.twig', $this->defaultVars($casProtocol, $request));
    }

    /**
     * @Route("/page/pgtrequest", name="page_pgtrequest")
     *
     * @param Request $request
     * @param CasInterface $cas
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pgtForm(Request $request, CasInterface $cas)
    {
        $pt = null;

        /** @var \drupol\EuloginBundle\Security\Core\User\EuloginUser|null $user */
        $user = $this->getUser();

        $pgt = (null !== $user) ? $user->getPgt() : '';
        $targetService = $request->getUri();

        $formRequest = $this->createForm(PgtRequest::class, ['pgt' => $pgt, 'targetService' => $targetService]);
        $formRequest->handleRequest($request);

        if ($formRequest->isSubmitted()) {
            if (null !== $response = $cas->requestProxyTicket($formRequest->getData())) {
                $instrospect = Introspector::detect($response);
                if ($instrospect instanceof Proxy) {
                    $pt = $instrospect->getProxyTicket();
                }

            }
        }

        $parameters = [
            'service' => $formRequest->get('targetService')->getData(),
            'ticket' => $pt,
        ];

        $formValidate = $this->createForm(PgtValidate::class, $parameters);
        $formValidateResult = $this->createFormBuilder();
        $formValidate->handleRequest($request);

        if ($formValidate->isSubmitted()) {
            $parameters = $formValidate->getData();

            $response = $cas->requestProxyValidate(['service' => $parameters['service'], 'ticket' => $parameters['ticket']]);
            if (null !== $response) {
                $introspect = Introspector::detect($response);
                if ($introspect instanceof ProxyValidate) {
                    $formValidateResult
                        ->add('response', TextareaType::class, ['data' => print_r($introspect->getParsedResponse(), true), 'label' => 'Raw response']);
                }
            }
        }

        $vars['formRequest'] = $formRequest->createView();
        $vars['formValidate'] = $formValidate->createView();
        $vars['formValidateResult'] = $formValidateResult->getForm()->createView();

        $vars = $this->defaultVars($cas, $request) + $vars;

        return $this->render('page/pgtrequest.html.twig', $vars);
    }
}