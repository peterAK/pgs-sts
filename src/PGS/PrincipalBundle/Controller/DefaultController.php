<?php

namespace PGS\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PGSPrincipalBundle:Default:index.html.twig', array('name' => $name));
    }
}
