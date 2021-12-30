<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(CatRepository $catRepository,ArticleRepository $articleRepository,Request $request): Response
    {
        
        $entity = $catRepository->findBestCat();
        $articles = $articleRepository->findAll();
        return $this->render('app/home.html.twig', [
            'controller_name' => 'CoreController',
            'entity' => $entity[0],
            'articles' => $articles,
        ]);
    }
    
}
