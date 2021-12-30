<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $articleRepository,Request $request): Response
    {
        // $articles = $articleRepository->findAll();

        $page =$request->get('p',1);
        //Changer cette variable pour changer nb item par page
        $itemCount = 5;
        
        $articles = $articleRepository->findPagination($page,$itemCount);
         $pageCount = \ceil($articles->count() / $itemCount);
         $count = $articles->count();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'count'=> $count,
            'pageCount' => $pageCount,
        ]);
    }


 /**
     * @Route("/article/new")
     */
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new Article;
       
        $entity->setAuteur($this->getUser());
       
        $form = $this->createForm(ArticleType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $entityManager->persist($entity);
            $entityManager->flush();

            // $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("app_core_index");
        }

        return $this->render('article/new.html.twig',[
            'form' => $form->createView(),
            
        ]);
    }
    
     /**
     * @Route("/article/{id}/detail", requirements={"id":"\d+"})
     */
    public function detail(Article $entity, $id): Response{
        
        
        return $this->render("article/detail.html.twig", [
        
        'entity' => $entity,
        'id'=>$id,
    ]);
}
 /**
     * @Route("/article/{id}/edit", requirements={"id":"\d+"})
     */
    public function edit(Article $entity, Request $request, EntityManagerInterface $entityManager): Response{
        
        

       
        
        $form = $this->createForm(ArticleType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();

            $this->addFlash('sucess','Article a bien été modifié!');

            return $this->redirectToRoute("article");
        }
        return $this->render("article/edit.html.twig", [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }

    /**
     * @Route("/{id}/delete", requirements={"id":"\d+"})
     */
    public function delete(Article $entity, Request $request, EntityManagerInterface $entityManager): Response{
        

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){
            $entityManager->remove($entity);
            $entityManager->flush();

            $this->addFlash('success', 'Article a bien été supprimé!');

            return $this->redirectToRoute("article");
        }

        return $this->render("article/delete.html.twig", [
            'entity' => $entity,
        ]);
    }
}