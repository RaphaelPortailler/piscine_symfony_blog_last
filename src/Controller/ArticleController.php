<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController{

    #[Route('/articles', name: 'articles')]
    public function GuestArticle(ArticleRepository $ArticleRepository):Response{

        $articles = $ArticleRepository->findAll();

        return $this->render('guest/articles.html.twig', [
            'articles' => $articles
        ]);
    }


    #[Route('/show-article/{id}', name: 'show_article')]
    public function showArticle(int $id, ArticleRepository $ArticleRepository):Response
    {
        $article = $ArticleRepository->find($id);

        if (!$article || !$article->getIsPublished()) {
            $html404 = $this->renderView('guest/404.html.twig');
            return new Response($html404, 404);
        }

        return $this->render('guest/showArticle.html.twig', [
            'article' => $article
        ]);

    }



}