<?php

// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller{


    /**
    * @Route("/login", name="login")
    */
    
    public function loginAction(Request $request,  AuthenticationUtils $authUtils){
        if(!empty($this->getUser())){
            return $this->redirect($this->generateUrl('homepage'));
        }
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

    public function sendpasswordAction(Request $request,\Swift_Mailer $mailer){
        $token = $this->getUserToken($request->get('email'));
        if(!empty($token)){
            $email_body = 'Здравствуйте!
            Если дейвствительно вы отправили запрос на восстановление пароля от почтового ящика ***. Для того чтобы задать новый пароль, перейдите по ссылке http://'.$this->container->getParameter('domain').$token.' и следуйте инструкциям на странице.';
            $message = (new \Swift_Message('Password recovery'))
            ->setFrom('info@exesoft.org')
            ->setTo($request->get('email'))
            ->setBody($email_body);

            $mailer->send($message);	
        }
        return $this->render('@App/Security/sendpassword.html.twig', array(
            'status'         => 'ok',
        ));
    }


    public function recoveryAction($token){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByToken($token);
        if(!empty($user)){
            return $this->render('@App/Security/newpassword.html.twig',array('token'=>$token));
        }

        return $this->redirect($this->generateUrl('homepage')); 
    }

    public function newpasswordAction(Request $request,UserPasswordEncoderInterface $passwordEncoder, $token){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByToken($token);
        if(!empty($user)){
            if($request->isMethod('POST')){
                if($request->get('newpassword') === $request->get('newpassword_retype')){
                    $em = $this->getDoctrine()->getManager();
                    $password = $passwordEncoder->encodePassword($user, $request->get('newpassword'));
                    $user->setPassword($password);
                    $user->setToken(md5(time()));
                    $em->persist($user);
                    $em->flush();
                    return $this->redirect($this->generateUrl('login'));
                }else{
                    return $this->render('@App/Security/newpassword.html.twig',array('token'=>$token,'error'=>'not match'));
                }
            }
            return $this->render('@App/Security/newpassword.html.twig',array('token'=>$token));
        }
    }

    private function getUserToken($email)
    {
        $token = '';
        if(!empty($email))
        {
            
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneByEmail($email); 
            
            if(!empty($user)){
                $token = md5(time());
                $em = $this->getDoctrine()->getManager();
                if(!empty($user)){
                    $user->setToken($token);
                    $em->persist($user);
                    $em->flush();
                }
            }
        }

        return $token;
    }
}
