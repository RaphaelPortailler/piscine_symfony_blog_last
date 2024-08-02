<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
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


    #[Route('/admin/articles/delete/{id}', name: 'delete_articles')]
    public function deleteArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $articles = $articleRepository->find($id);

        if (!$articles) {
            $html = $this->renderView('admin/page/404.html.twig');
            return new Response($html, 404);
        }

        // j'utilise la classe entity manager
        // pour préparer la requête SQL de suppression
        // cette requête n'est pas executée tout de suite
        $entityManager->remove($articles);
        // j'execute la / les requête SQL préparée
        $entityManager->flush();

        return $this->redirectToRoute('admin_articles');
    }


}