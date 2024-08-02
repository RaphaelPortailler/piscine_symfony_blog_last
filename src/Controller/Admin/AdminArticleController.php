<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{

    #[Route('/admin/articles', 'admin_articles')]
    public function adminListArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('admin/articles.html.twig', [
            'articles' => $articles
        ]);
    }


}