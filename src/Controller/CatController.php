<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\Like;
use App\Entity\User;
use App\Form\CatType;
use App\Repository\CatRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Length;

/**
* @Route("/cat")
*/
    class CatController extends AbstractController
{
    /**
     * @Route("/")
     * 
     */
    public function list(CatRepository $catRepository,Request $request): Response{
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $page =$request->get('p',1);
            //Changer cette variable pour changer nb item par page
            $itemCount = 50;

            $entities = $catRepository->findPagination($page,$itemCount);
             $pageCount = \ceil($entities->count() / $itemCount);
             $count = $entities->count();
             $classement=null;
             $entities_enabled = $catRepository->findEnabled();
        }
        else{
            $entities = $catRepository->findAll();
            $entities_enabled = $catRepository->findEnabled();
            $classement = $catRepository->classement();
             $pageCount =0;
             $count = count($entities);
        }
       /* if($this->getUser() instanceof User){
            $entities = $catRepository->findAll();
            $count = $catRepository->count([]);
        } else{
            $entities = $catRepository->findEnabled();
            $count = $catRepository->count(['publish' => true]);
        }
         {% if app.user %}*/
       
       
        return $this->render("cat/list.html.twig", [
            'entities' => $entities,
            'count'=> $count,
            'pageCount' => $pageCount,
            'classement'=> $classement,
            'entities_enabled'=>$entities_enabled,
        ]);
    }
    

    /**
     * @Route("/new")
     * @IsGranted("ROLE_USER")
     */
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new Cat;
        $entity->setUser($this->getUser());
        
        $form = $this->createForm(CatType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('sucess','La fiche du chat a bien été ajouté!');

            return $this->redirectToRoute("app_cat_list");
        }

        return $this->render('cat/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", requirements={"id":"\d+"})
     */
    public function edit(Cat $entity, Request $request, EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('EDIT', $entity);
        

        if(null === $entity->getUser()){
            $entity->setUser($this->getUser());
        }
        
        $form = $this->createForm(CatType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();

            $this->addFlash('sucess','La fiche du chat a bien été modifié!');

            return $this->redirectToRoute("app_cat_list");
        }
        return $this->render("cat/edit.html.twig", [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }


    /**
     * @Route("/{id}/delete", requirements={"id":"\d+"})
     */
    public function delete(Cat $entity, Request $request, EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('EDIT', $entity);

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){
            $entityManager->remove($entity);
            $entityManager->flush();

            $this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("app_cat_list");
        }

        return $this->render("cat/delete.html.twig", [
            'entity' => $entity,
        ]);
    }

    /**
     * @Route("/{id}/like", requirements={"id":"\d+"})
     * IsGranted("ROLE_USER")
     */
    public function like(Cat $entity, LikeRepository $likeRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $like = $likeRepository->findOneByUserAndCat($user, $entity);

        if(null === $like)
        {
            $like = (new Like)
                ->setUser($user)
                ->setCat($entity)
                ->setDate(new \DateTime());
            
            $entityManager->persist($like);
        }else{
            $entityManager->remove($like);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_cat_list');
    }
    /**
     * @Route("/{id}/detail", requirements={"id":"\d+"})
     */
    public function detail(Cat $entity, $id): Response{
        
        
            return $this->render("cat/detail.html.twig", [
            
            'entity' => $entity,
            'id'=>$id,
        ]);
    }
}