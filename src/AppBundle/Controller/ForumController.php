<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Forum;
use AppBundle\Entity\Category;
use AppBundle\Entity\Fmessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use AppBundle\Service\FileUploader;
class ForumController extends Controller
{

    public function indexAction(){
        $response = new JsonResponse('<h1>Hello World</h1>');
        return $this->render(
            '@App/Forum/index.html.twig',
            [   'categories' => $this->getCategories(),
                'forums' => $this->getForums()
            ]
        );
    }

    public function addAction(Request $request, FileUploader $fileUploader){
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $forum = new Forum();
            $forum->setTitle($request->get('title'));
            $forum->setUserId($this->getUser()->getId());
            $forum->setDescription($request->get('description'));
            $forum->setCategoryId($request->get('category_id'));    
            $forum->setAvatar($request->get('phone'));
     
            $em->persist($forum);
            $em->flush();    

            return $this->forward('AppBundle:Forum:index');
        }
        return $this->render('@App/Forum/add.html.twig',['categories' => $this->getCategories()]);
    }


    public function historyAction($forum_id=null, Request $request){
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $fmessage = new Fmessage();
            $params = json_decode($request->getContent(),true);
            $fmessage->setMessage($params['message']);
            $fmessage->setForumId($forum_id);
            $fmessage->setUserId($this->getUser()->getId());
            $fmessage->setCategoryId(@$params['category_id']);    
            $em->persist($fmessage);
            $em->flush();
        }
        return $this->render('@App/Forum/history.html.twig',
            [
                'messages' => $this->getHistory($forum_id),
                'forum' => $this->getForumById($forum_id)
            ]
        );
    }

    private function getCategories(){
        return $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
    }

    private function getForums(){
        return $this->getDoctrine()
                ->getRepository(Forum::class)
                ->findAll();
    }   

    private function getHistory($forum_id){

        return $this->getDoctrine()
                ->getRepository(Fmessage::class)
                ->findAllByForumId($forum_id);
        
    }

    private function getForumById($id){
        return $this->getDoctrine()
                ->getRepository(Forum::class)
                ->findById($id);
    }
    
}
