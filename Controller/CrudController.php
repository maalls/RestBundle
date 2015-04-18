<?php

namespace Maalls\RestBundle\Controller;

use Maalls\RestBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
abstract class CrudController extends RestController
{

    public function indexAction(Request $request) {

        $pager = parent::indexAction($request);

        return $this->render($this->getRepositoryName() . ':index.html.twig', array('pager' => $pager));

    }

    public function createAction() {

        $form = parent::createAction();
        return $this->render($this->getRepositoryName() . ':edit.html.twig', array('form' => $form->createView()));

    }

    public function editAction($id, Request $request) {

        $form = parent::editAction($id, $request);

        return $this->render($this->getRepositoryName() . ':edit.html.twig', array('form' => $form->createView()));

    }

    public function updateAction(Request $request) {

        $form = parent::updateAction($request);

        if($form->isValid()) {

            $this->addFlash('success', $this->extractClassName() . ' saved.');
            $this->redirect($this->generateUrl($this->getRouting('edit'), array("id" => $form->getData()->getId())));

        }
        else {

            return $this->render($this->getRepositoryName() . ':edit.html.twig', array('form' => $form->createView()));

        }

    }

    protected abstract function getRepositoryName();

    protected function getRepository() {

        return $this->getDoctrine()->getManager()->getRepository($this->getRepositoryName());

    }

    protected function createEntity() {

        $className = $this->extractClassName();

        return new $className;

    }

    protected function createEntityType() {

        $className = $this->extractClassName() . "Type";

        return $className;

    }

    protected function getFormName() {

        $className = $this->extractClassName;

        return strtolower($className);

    }

    private function getRouting($action) {

        $base_routing = $this->camelToDash($this->extractBundleName());

        return $base_routing . "_" . $action;

    }

    private function extractClassName() {

        list($bundleName, $className) = explode(":", $this->getRepositoryName());

        return $className;

    }

    private function extractBundleName() {

        list($bundleName, $className) = explode(":", $this->getRepositoryName());

        return $bundleName;

    }

    private function camelToDash($camel) {

        $dash = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camel));

        return $dash;

    }

}    
