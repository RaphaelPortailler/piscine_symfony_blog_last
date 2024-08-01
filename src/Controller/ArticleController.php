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
    public function GuestArticle(ArticleRepository $ArticleRepository){

        $articles = $ArticleRepository->findAll();

        return $this->render('guest/articles.html.twig', [
            'articles' => $articles
        ]);
    }


    #[Route('/show-article/{id}', name: 'show_article')]
    public function showArticle(INT $id, ArticleRepository $ArticleRepository):Response
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


    #[Route('/category', name: 'category')]
    public function category(CategoryRepository $CategoryRepository): Response
    {
        $category = $CategoryRepository->findAll();
        return $this->render('guest/category.html.twig', [
            'categories' => $category
        ]);
    }

    #[Route('/show-category/{id}', name: 'show_category')]
    public function showCategory(INT $id, CategoryRepository $CategoryRepository):Response
    {
        $category = $CategoryRepository->find($id);

        if(!$category)
        {
            $html404 = $this->renderView('guest/404.html.twig');
            return new Response($html404, 404);
        }

        $title = $category->getTitle();
        return $this->render('guest/showCategory.html.twig', [
            'categories' => $category,
            'title' => $title
        ]);
    }
}