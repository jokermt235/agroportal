<?php

// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller{


    /**
    * @Route("/login", name="login")
    */
    
    public function loginAction(Request $request,  AuthenticationUtils $authUtils){

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        //return new Response($error);

        return $this->render('@App/Security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    public function logincheckAction(Request $request,  AuthenticationUtils $authUtils){

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('@App/User/profile.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function forgotpasswordAction(){
        return $this->render('@App/Security/forgotpassword.html.twig');
    }
}
