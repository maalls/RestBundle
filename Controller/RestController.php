<?php

namespace Maalls\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maalls\Pager\Request\Pager;
use Symfony\Component\HttpFoundation\Request;

abstract class RestController extends Controller
{
    
    public function indexAction(Request $request)
    {
        
        $pager = new Pager($request, 5, array("page" => 1, "limit" => 50, "search" => ""), $this->createQueryBuilder());
        
        return $pager;

    }

    public function createQueryBuilder() 
    {

        return $this->getRepository()->createQueryBuilder("e");

    }

    public function createAction() {

        $partner = $this->createEntity();
        $form = $this->createForm($this->createEntityType(), $partner);
        
        return $form;

    }

    public function editAction($id, Request $request) {

        $partner = $this->getRepository()->find($id);
        $form = $this->createForm($this->createEntityType(), $partner);
        
        return $form;

    }

    public function updateAction(Request $request) 
    {

        $params = $request->request->get($this->getFormName());

        if($params["id"]) {

            $partner = $this->getRepository()->find($params["id"]);

        }
        else {

            $partner = $this->createEntity();

        }

        $form = $this->createForm($this->createEntityType(), $partner);
        $form->handleRequest($request);

        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($partner);
            $em->flush();
            
        }
        
        return $form;

    }

    abstract protected function getRepository();
    abstract protected function createEntity();
    abstract protected function createEntityType();
    abstract protected function getFormName();

}
