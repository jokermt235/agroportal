<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Publication;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use AppBundle\Service\Translit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class PublicationController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Publication/index.html.twig',
            ['publications' => $this->getPublications()]
        );
    }

    private function getPublications(){
        return $this->getDoctrine()
                ->getRepository(Publication::class)
                ->findAll();
    }

    public function addAction(Request $request,FileUploader $fileUploader,Translit $translit){
        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();
            $publication = new Publication();
            $publication->setTitle($request->get('title'));
            $publication->setUserId($this->getUser()->getId());
            $publication->setDescription($request->get('description'));

            $em->persist($publication);
            $em->flush();
            if(!empty($fileUploader))
            {
                $this->targetDirectory = $fileUploader->getTargetDirectory();
                $files = $this->moveUploadedFiles($request->get('session_id'),$publication->getId());

                $publication->setImages(base64_encode(serialize($files)));
            
            }

            $publication->setUrl($translit->createUrl($request->get('title').' id '.$publication->getId()));
            
            $em->persist($publication);
            
            $em->flush(); 

            $fileSystem = new Filesystem();

            $fileSystem->remove($this->targetDirectory);
            
            $session->getFlashBag()->add("success", "This is a success message");
           
            return $this->forward('AppBundle:Publication:index');
            
        }

        return $this->render('@App/Publication/add.html.twig',
            array(
                'session_id' => $session->getId(),
            )
        );
    }

    public function viewAction($url=null, Request $request){
        $pattern = "/id-[0-9]+$/"; 
        preg_match($pattern,$url,$matches);
        if(!empty($matches)){
            $mat = explode("-",$matches[0]);
            $id = $mat[1];
        }

        $response = new Response();

        

        $publication = $this->getDoctrine()
                ->getRepository(Publication::class)
                ->findOneById($id);

        if(empty($request->cookies->get('UserSession'))){
            $session = $this->get('session');
            $session->start();
            $session_id = $session->getId();
            $response->headers->setCookie(new Cookie('UserSession', $session_id));
            $publication->setViews();
        }else{
            if(!empty($request->cookies->get('VisitedPublication'))){
                if(!$this->isInCookie($publication->getId(), $request)){
                    $publication->setViews();
                    $this->writeCookie($publication->getId(),$response, $request);
                }
            }else{
                $this->writeCookie($publication->getId(), $response, $request);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($publication);
        $em->flush();

        $images = $publication->getImages();

        $files = [];

        if(!empty($images)){
            $files = unserialize(base64_decode($images));
        } 
        
        return $this->render('@App/Publication/view.html.twig',
            ['publication' =>
                $this->getDoctrine()
                ->getRepository(Publication::class)
                ->findOneById($id) ,
            'images'=> $files],
            $response
        );
    }

    private function isInCookie($id=null, $request)
    {
        $visitedPublications = unserialize(base64_decode($request->cookies->get('VisitedPublication')));
        foreach($visitedPublications as $visitedPublication){
            if($visitedPublication == $id){
                return true;
            }
        }

        return false;
    }

    private function writeCookie($id=null, &$response, $request)
    {
        $cookieBase64 = $request->cookies->get('VisitedPublication');
        $visitedPublications = [];
        if(!empty($cookieBase64)){
            $visitedPublications = unserialize(base64_decode($cookieBase64));
        }
        
        $visitedPublications[] = $id;

        $visitedBase64 = base64_encode(serialize($visitedPublications));
        $response->headers->setCookie(new Cookie('VisitedPublication',$visitedBase64));
    }

    #Publication End

    public function uploadAction(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('image');
        $session_id = $request->headers->get('UserSession');
        return new Response($fileUploader->getFilePreview($file,$session_id));
    }

    private function moveUploadedFiles($session_id,$id)
    {
        $fileSystem = new Filesystem();
        $files = [];
        try {
            $tmpDir = $this->targetDirectory;
            $dirname = str_replace("tmp","images",$tmpDir).'/publication/'.$id;
            $fileSystem->mkdir($dirname);
            $finder = new Finder();
            
            if($fileSystem->exists($this->targetDirectory.'/'.$session_id)){
                $finder->files()->in($this->targetDirectory.'/'.$session_id);
            }else{
                return [];
            }

            foreach($finder as $file){
                if(strpos($file->getRelativePathname(), 'small') === false){
                    $files[] = $file->getRelativePathname();
                }
                $cp_file_name = $dirname.'/'.$file->getRelativePathname(); 
                $fileSystem->copy($file->getRealPath(),$cp_file_name);
            }

        } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        return $files;

    }
}
