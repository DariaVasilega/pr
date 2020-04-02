<?php

namespace App\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

class FormHelperService
{
    private $formFactory;
    private $router;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->router = $router;

        return $this->formFactory = $formFactory;
    }

    public function newForm($userId, $page, $formType, $entity, $action, $id, $formId)
    {
        return $this->formFactory->create($formType, $entity, [
            'action' => $this->router->generate($action, [
                'userId' => $userId,
                'page' => $page,
                'id' => $id,
            ]),
            'method' => 'POST',
            'attr' => [
                'id' => $formId,
            ],
        ]);
    }
}
