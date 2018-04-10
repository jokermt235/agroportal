<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use  AppBundle\Entity\Arecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class ArecordController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */

    private $targetDirectory;

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return 'arecord';
    }


    public function uploadAction(Request $request, FileUploader $fileUploader)
    {
        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){
            $response = new Response();
            $file = $request->files->get('image');
            
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            //print_r($file->getRealPath());

            //$response = $fileUploader->getFilePreview($response);

            $session_id = $request->headers->get('UserSession');

            return new Response($fileUploader->getFilePreview($file,$session_id));
        }

        return $this->render('@App/Arecord/upload.html.twig', array('session_id' => $session->getId()));
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
         return md5(uniqid());
    }

    public function saveAction(Request $request,FileUploader $fileUploader)
    {
         $entityManager = $this->getDoctrine()->getManager();
         $arecord = new Arecord();
         $arecord->setName($request->get('name'));
         $entityManager->persist($arecord);
         $entityManager->flush();
         $this->targetDirectory = $fileUploader->getTargetDirectory();
         $this->moveUploadedFiles($request->get('session_id'),$arecord->getId());

         return new Response('OK');
    }

    private function moveUploadedFiles($session_id,$id)
    {
        $fileSystem = new Filesystem();
        try {
            $tmpDir = $this->targetDirectory;
            $dirname = $this->targetDirectory.'/'.$id;
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

}
