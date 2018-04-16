<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Section;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;
class AdvertController extends Controller
{

    public function addAction(Request $request,FileUploader $fileUploader)
    {
        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();
            $advert = new Advert();
            $advert->setTitle($request->get('title'));
            $advert->setUserId($this->getUser()->getId());
            $advert->setPrice($request->get('price'));
            $advert->setSectionId($request->get('section_id'));
            $advert->setCategoryId($request->get('category_id'));    
            $advert->setCityId($request->get('city_id'));
            $advert->setCountryId($request->get('country_id'));
            $advert->setPhone($request->get('phone'));
            $advert->setDescription($request->get('description'));
            
            if(!empty($fileUploader)){
                $this->targetDirectory = $fileUploader->getTargetDirectory();
                $files = $this->moveUploadedFiles($request->get('session_id'),$this->getUser()->getId());

                $advert->setImages(base64_encode(serialize($files)));
            }
            
            $em->persist($advert);
            $em->flush();
            
            $session->getFlashBag()->add("success", "This is a success message");
        }

        return $this->render('@App/Advert/add.html.twig', 
            array(
                'session_id' => $session->getId(),
                'categories' => $this->getCategories(),
                'sections' => $this->getSections()
            )
        );
    }

    private function getSections(){
        return $this->getDoctrine()
                ->getRepository(Section::class)
                ->findAll();
    }

    private function getCategories(){
        return $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
    }

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
            str_replace("tmp","images",$tmpDir);
            $dirname = str_replace("tmp","images",$tmpDir).'/advert/'.$id;
            $fileSystem->mkdir($dirname);
            $finder = new Finder();

            $finder->files()->in($this->targetDirectory.'/'.$session_id);

            foreach($finder as $file){
                $files[] = $file->getRelativePathname();
                $cp_file_name = $dirname.'/'.$file->getRelativePathname(); 
                $fileSystem->copy($file->getRealPath(),$cp_file_name);
            }

        } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        return $files;

    }

    public function viewAction($id){
        $advert = $this->getDoctrine()
            ->getRepository(Advert::class)
            ->findAdvertById($id);
        if(!empty($this->getUser())){
            if($advert->getUserId() !== $this->getUser()->getId()){
                $advert->setViews();
            }
        }else{
            $advert->setViews();
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();
        $images = array();
        $previews = array();

        foreach(unserialize(base64_decode($advert->getImages())) as $image){
            if(strpos($image,'small') === 0){
                $previews[] = $image;
            }else{
                $images[] = $image;
            }
        }
        return $this->render('@App/Advert/view.html.twig',
            array(
            'advert'=>$advert,
            'images'=> $images,
            'previews'=>$previews
            )
        );
    }

}
