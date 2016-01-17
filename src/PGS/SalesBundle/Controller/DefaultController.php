<?php

namespace PGS\SalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PGSSalesBundle:Default:index.html.twig', array('name' => $name));
    }
}
