<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $targetDirectory;

    public function registrationAction(Request $request, UserPasswordEncoderInterface $passwordEncoder,FileUploader $fileUploader)
    {
        if(!empty($this->getUser())){
            return $this->redirect($this->generateUrl('homepage'));
        }

        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){
            $user = 
                $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByUsername($request->get('username'));
            if($user){
                return $this->render('@App/User/registration.html.twig',array('session_id' => $session->getId()));
            }
            
            $user = 
                $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByEmail($request->get('email'));
            if($user){
                return $this->render('@App/User/registration.html.twig',array('session_id' => $session->getId()));
            }

            $em = $this->getDoctrine()->getManager();
            $user = new User();
            $user->setUsername($request->get('username'));
            if($request->get('password') === $request->get('password_retype')){
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
            }
            $user->setPassword($password);
            $user->setFio($request->get('fio'));
            $user->setEmail($request->get('email'));
            $user->setPhone($request->get('phone'));
            
            $em->persist($user);
            $em->flush();

            $this->targetDirectory = $fileUploader->getTargetDirectory();
            $this->moveUploadedFiles($request->get('session_id'),$user->getId());
            $session->getFlashBag()->add("success", "This is a success message");

            return $this->redirect($this->generateUrl('login'));
        }
        
        return $this->render('@App/User/registration.html.twig', array('session_id' => $session->getId()));
    }

    public function uploadAction(Request $request, FileUploader $fileUploader){
        $file = $request->files->get('image');
        $session_id = $request->headers->get('UserSession');
        return new Response($fileUploader->getFilePreview($file,$session_id));
    }

    public function profileAction(){
        $myadverts = $this->getDoctrine()
                    ->getRepository(Advert::class)
                    ->findAdvertByUserId($this->getUser()->getId());
        foreach($myadverts as $advert){
            $images = unserialize(base64_decode($advert->getImages()));
            if(!empty($images)){
                foreach($images as $image){
                    if(strpos($image,'small') >= 0){
                       $advert->setAvatar($image);
                       break;
                    }
                }
            }
        }
        return  $this->render('@App/User/profile.html.twig',
            array(
                'myadvert_count' => 
                    $this->getDoctrine()
                    ->getRepository(Advert::class)
                    ->getAdvertCountByUserId($this->getUser()->getId()),
                'myadverts' =>$myadverts
                    
            )
        );
    }
    
    private function moveUploadedFiles($session_id,$id)
    {
        $fileSystem = new Filesystem();
        try {
            $tmpDir = $this->targetDirectory;
            str_replace("tmp","images",$tmpDir);
            $dirname = str_replace("tmp","images",$tmpDir).'/user/'.$id;
            $fileSystem->mkdir($dirname);
            $finder = new Finder();

            $finder->files()->in($this->targetDirectory.'/'.$session_id);

            foreach($finder as $file){
                $cp_file_name = $dirname.'/'.$file->getRelativePathname(); 
                $fileSystem->copy($file->getRealPath(),$cp_file_name);
            }

        } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
        }

    }

    function logoutAction() {
        return $this->redirect($this->generateUrl('login'));
    } 

    public function myadvertAction(Request $request){

        return $this->render('@App/User/myadvert.html.twig',
            array(
                'adverts' => $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAdvertByUserId($this->getUser()->getId())
            )
        );
    }
}
