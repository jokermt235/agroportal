<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Section;
use AppBundle\Entity\Category;
use AppBundle\Entity\Country;
use AppBundle\Entity\User;
use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
class AdvertController extends Controller
{
    
    public function indexAction(Request $request){
        if(!empty($request->get('search_text'))){
            $adverts = $this->getAdvertsBySearchText($request->get('search_text'));
            if(!empty($request->get('search_category_id'))){
                $adverts = $this->getAdvertsBySearchTextAndCategoryId(
                    $request->get('search_text'),
                    $request->get('search_category_id')
                );
            }
        }else{
            if(empty($request->get('search_category_id'))){
                if(!empty($request->get('category_id'))){
                    if(!empty($request->get('country_id'))){
                        $adverts = $this->getAdvertsByCategoryIdAndCountryId(
                            $request->get('category_id'),$request->get('country_id')
                        );
                    }else{
                        $adverts = $this->getAdvertsByCategoryId($request->get('category_id'));
                    }
                }else{
                    if(!empty($request->get('country_id'))){
                        $adverts = $this->getAdvertsByCountryId($request->get('country_id'));
                    }else{
                        $adverts = $this->getAdverts();
                    }
                    if(!empty($request->get('section_id'))){
                        $adverts = $this->getAdvertsBySectionId($request->get('section_id'));
                    }else{
                        if(empty($request->get('country_id'))){
                            $adverts = $this->getAdverts();
                        }
                    }
                }
            }else{
                $adverts = $this->getAdvertsByCategoryId($request->get('search_category_id'));
            }
        }
        return $this->render('@App/Advert/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'categories'=>$this->getCategories(),
            'sections'=> $this->getSections(),
            'adverts' => $adverts,
            'countries' => $this->getDoctrine()
                ->getRepository(Country::class)
                ->findAll()
        ]);
    }

    public function addAction(Request $request,FileUploader $fileUploader,Translit $translit)
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
            $advert->setCompanyId($request->get('company_id'));

            $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findCompanyById($request->get('company_id'));
            
                
            $phone = "";


            if(!empty($company)){
                $phone = $company->getPhone();
                $advert->setCountryId($company->getCountryId());
            }else{
                $advert->setCountryId($this->getUser()->getCountryId());
            }

            if(empty($phone)){
                $phone  = $this->getUser()->getPhone();
            }

            $advert->setPhone($phone);
            $advert->setDescription($request->get('description'));
            $advert->setCurrency($request->get('currency'));
            $advert->setStatus(1);   
            
           
            
            $em->persist($advert);
            $em->flush();
            if(!empty($fileUploader))
            {
                $this->targetDirectory = $fileUploader->getTargetDirectory();
                $files = $this->moveUploadedFiles($request->get('session_id'),$advert->getId());

                $advert->setImages(base64_encode(serialize($files)));
            
            }

            $advert->setUrl($translit->createUrl($request->get('title').' id '.$advert->getId()));
            
            $em->persist($advert);
            
            $em->flush();

            

            $fileSystem = new Filesystem();

            $fileSystem->remove($this->targetDirectory);
            
            $session->getFlashBag()->add("success", "This is a success message");
           
            return $this->forward('AppBundle:User:profile');
        }

        return $this->render('@App/Advert/add.html.twig', 
            array(
                'session_id' => $session->getId(),
                'categories' => $this->getCategories(),
                'sections' => $this->getSections()
            )
        );
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
            
            if($fileSystem->exists($this->targetDirectory.'/'.$session_id)){
                $finder->files()->in($this->targetDirectory.'/'.$session_id);
            }else{
                return [];
            }

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

    public function viewAction($url){
        $pattern = "/id-[0-9]+$/"; 
        preg_match($pattern,$url,$matches);
        if(!empty($matches)){
            $mat = explode("-",$matches[0]);
            $id = $mat[1];
        }
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
        if(!empty(unserialize(base64_decode($advert->getImages())))){ 
            foreach(unserialize(base64_decode($advert->getImages())) as $image){
                if(strpos($image,'small') === 0){
                    $previews[] = $image;
                }else{
                    $images[] = $image;
                }
            }
        }
        return $this->render('@App/Advert/view.html.twig',
            array(
            'advert'=>$advert,
            'images'=> $images,
            'previews'=>$previews,
            'author' => $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneById($advert->getUserId()),
            'country'=>$this->getDoctrine()
            ->getRepository(Country::class)
            ->findOneById($advert->getCountryId()),
            'company'=>$this->getDoctrine()
            ->getRepository(Company::class)
            ->findCompanyById($advert->getCompanyId())
            )
        );
    }

    public function editAction($id=null,Request $request ,FileUploader $fileUploader){
        $session = $this->get('session');
        $session->start();
        $advert = $this->getDoctrine()
            ->getRepository(Advert::class)
            ->findAdvertById($id);
        
        $previews = [];
        $images = [] ;
        if(!empty(unserialize(base64_decode($advert->getImages())))){ 
            foreach(unserialize(base64_decode($advert->getImages())) as $image){
                if(strpos($image,'small') === 0){
                    $previews[] = $image;
                }else{
                    $images[] = $image;
                }
            }
        }

        if(empty($previews)){
            $previews = $images;
        }
        
        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();
            $advert->setTitle($request->get('title'));
            $advert->setPrice($request->get('price'));
            $advert->setSectionId($request->get('section_id'));
            $advert->setCategoryId($request->get('category_id'));    
            $advert->setCityId($request->get('city_id'));
            $advert->setCountryId($request->get('country_id'));
            $advert->setCompanyId($request->get('company_id'));
            $advert->setPhone($request->get('phone'));
            $advert->setDescription($request->get('description'));
            $advert->setCurrency($request->get('currency'));
            
            if(!empty($fileUploader))
            {
                $this->targetDirectory = $fileUploader->getTargetDirectory();
                $files = $this->moveUploadedFiles($request->get('session_id'),$advert->getId());
                $advert->setImages(base64_encode(serialize(array_merge($images,$files))));
            }else{
                if(empty($images)){
                    $advert->setImages(base64_encode(serialize('')));
                }
            }
            
            $em->persist($advert);
            $em->flush();

            $fileSystem = new Filesystem();

            $fileSystem->remove($this->targetDirectory);
            
            $session->getFlashBag()->add("success", "This is a success message");

            return $this->forward('AppBundle:User:profile');
        }
        
        return $this->render('@App/Advert/edit.html.twig',
            array(
                'advert'=>$advert,
                'session_id' => $session->getId(),
                'categories' => $this->getCategories(),
                'sections' => $this->getSections(),
                'images' => $images,
                'previews' => $previews
            )
        );
    }

    public function deleteImageAction(Request $request){
        $data = json_decode($request->getContent(),true);
        $params = $data;
        unlink($this->get('kernel')->getProjectDir().'/web/'.$params['image']);
        unlink(
            str_replace(
                'small_',
                '',
                $this->get('kernel')->getProjectDir().'/web/'.$params['image']
            )
        );

        unlink($this->get('kernel')->getProjectDir().'/web/uploads/tmp/'.$params['session_id'].'/'.basename($params['image']));
        unlink(
            str_replace(
                'small_',
                '',
                $this->get('kernel')->getProjectDir().'/web/uploads/tmp/'.$params['session_id'].'/'.basename($params['image'])
            )
        );
        return new Response($request->getContent());
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $advert = $this->getDoctrine()
            ->getRepository(Advert::class)
            ->findAdvertById($id);
        $em->remove($advert);
        $em->flush();
        $path = $this->get('kernel')->getProjectDir().'/web/uploads/images/advert/'.$advert->getId();
        $fileSystem = new Filesystem();
        $fileSystem->remove($path);
        return $this->forward('AppBundle:User:profile');
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

    private function getAdverts(){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAll();
    }

    private function getAdvertsByCategoryId($category_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCategoryId($category_id);
    }

    private function getAdvertsByCountryId($country_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCountryId($country_id);
    }

    private function getAdvertsBySectionId($section_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllBySectionId($section_id);
    }

    private function getAdvertsByCategoryIdAndCountryId($category_id, $country_id){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllByCategoryIdAndCountryId($category_id, $country_id);
    }

    private function getAdvertsBySearchText($search_text){
        return $this->getDoctrine()
                ->getRepository(Advert::class)
                ->findAllBySearchText($search_text);
    }

    private function  getAdvertsBySearchTextAndCategoryId($search_text, $search_category_id){
            
        return $this->getDoctrine()
            ->getRepository(Advert::class)
            ->findAllBySearchTextAndCategoryId($search_text, $search_category_id);
    }

}
