<?php

namespace PGS\OfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PGSOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
