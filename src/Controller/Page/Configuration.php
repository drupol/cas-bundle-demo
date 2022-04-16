<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Controller\DefaultController;
use EcPhp\CasLib\CasInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

final class Configuration extends DefaultController
{
    /**
     * @Route("/configuration", name="configuration")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(CasInterface $cas, Request $request) {
        $casConfig = $cas->getProperties()->all();
        $parameter_bag = $this->container->get('parameter_bag');

        $form = $this->createForm(\App\Form\Type\Configuration::class, ['configuration' => Yaml::dump($casConfig, 10, 2)]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                $request->getSession()->remove('configuration');
                return new RedirectResponse('configuration');
            }

            $data = $form->getData();

            $request->getSession()->set('configuration', Yaml::parse($data['configuration']));
        }

        $vars = [
            'form' => $form->createView(),
        ];

        $vars = $this->defaultVars($request) + $vars;

        return $this->render('page/configuration.html.twig', $vars);
    }
}
