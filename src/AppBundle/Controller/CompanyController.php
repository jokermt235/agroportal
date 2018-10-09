<?php
namespace AppBundle\Controller;
use AppBundle\Entity\User;
use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Category;
use AppBundle\Entity\Advert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
use AppBundle\Service\Translit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class CompanyController extends Controller
{

    public function indexAction(){
        return $this->render('@App/Company/index.html.twig',[
            'categories'=>$this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll(),
            'countries' => $this->getDoctrine()
                ->getRepository(Country::class)
                ->findAll(),
            'companies' => 
                $this->getDoctrine()
                ->getRepository(Company::class)
                ->findAll()
        ]);
    }

    public function usercompanyAction(Request $request){
        return $this->render('@App/Company/usercompany.html.twig',[
            'companies'=>$this->getDoctrine()
                        ->getRepository(Company::class)
                        ->findCompanyByUserId($this->getUser()->getId()),
            'selected'=>$request->get('id')
            ]
        );
    }

    public function viewAction($url=null){
        /*$pattern = "/id-[0-9]+$/"; 
        preg_match($pattern,$url,$matches);
        if(!empty($matches)){
            $mat = explode("-",$matches[0]);
            $id = $mat[1];
        }*/

        $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findCompanyByUrl($url);

        if(!empty($company)){

            return $this->render('@App/Company/view.html.twig',[
                'company'=> $company,
                'country' => $this->getDoctrine()
                            ->getRepository(Country::class)
                            ->findCountryById($company->getCountryId()),
                'adverts'=> $this->getDoctrine()
                            ->getRepository(Advert::class)
                            ->findAdvertByCompanyId($company->getId())
            ]);
        }else{
            //throw $this->createNotFoundException('Страница не найдена');
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                   # User is a ROLE_ADMIN
                   return $this->render('@App/Admin/index.html.twig');
            }
        }

        return $this->redirect($this->generateUrl('homepage'));
    }
        
    public function addAction(Request $request, FileUploader $fileUploader,Translit $translit){
        $session = $this->get('session');
        $session->start();
        if($request->isMethod('POST')){
            if(!empty($translit->createUrl($request->get('suburl')))){
                $url = $translit->createUrl($request->get('suburl'));
                $company = $this->getDoctrine()
                ->getRepository(Company::class)
                ->findCompanyByUrl($url);
                if(!empty($company)){
                    $session->getFlashBag()->add("error", "Уже есть ссылка с таким именем, придумайте другое название!");
                    return $this->render('@App/Company/add.html.twig'); 
                }
            }
            $em = $this->getDoctrine()->getManager();
            $company = new Company();
            $company->setName($request->get('name'));
            $company->setUserId($this->getUser()->getId());
            $company->setCountryId($request->get('country_id'));
            
            $phone = "";
            if($request->get("phonecode") && $request->get('phone_without_code')){
                $phone = $request->get("phonecode").$request->get('phone_without_code');
            }

            $targetDir = $this->get('kernel')->getProjectDir()."/web/uploads/images/company/";

            $file_name = $fileUploader->upload($request->files->get('image'),$targetDir);

            $company->setPhone($phone);
            $company->setAvatar($file_name);
            $company->setDescription($request->get('description'));
            
            $company->setUrl($translit->createUrl($request->get('suburl')));

            $em->persist($company);

            $em->flush();


            if(empty($request->get('suburl'))){
                $company->setUrl($translit->createUrl($request->get('suburl').' id '.$company->getId()));
            }
            
            $em->persist($company);

            $em->flush();
            
            $session->getFlashBag()->add("success", "This is a success message");
           
            return $this->forward('AppBundle:User:profile');
        }
        return $this->render('@App/Company/add.html.twig');
    }
}
