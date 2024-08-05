<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            $html = $this->renderView('admin/404.html.twig');
            return new Response($html, 404);
        }

    try{
        // j'utilise la classe entity manager
        // pour préparer la requête SQL de suppression
        // cette requête n'est pas executée tout de suite
        $entityManager->remove($articles);
        // j'execute la / les requête SQL préparée
        $entityManager->flush();

        $this->addFlash('success', 'Article bien supprimé');
    } catch(\Exception $exception){
            return $this->render('admin/page/error.html.twig', [
                'error' => $exception->getMessage()
            ]);
    }

        return $this->redirectToRoute('admin_articles');
    }

    #[Route('/admin/articles/insert', name: 'insert_articles')]
    public function insertArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        $articleCreateForm = $this->createForm(ArticleType::class ,$article);

        $articleCreateForm->handleRequest($request);

        if ($articleCreateForm->isSubmitted() && $articleCreateForm->isValid()) {
            $article->setUpdatedAt(new \DateTime('NOW'));
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article Créer');
        }

        $articleCreateFormView = $articleCreateForm->createView();

        return $this->render('admin/insert.html.twig', [
            'articleCreateForm' => $articleCreateFormView
        ]);
    }

    #[Route('/admin/articles/update/{id}', 'admin_update_article')]
    public function updateArticle(int $id, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        $articleCreateForm = $this->createForm(ArticleType::class, $article);

        $articleCreateForm->handleRequest($request);

        if ($articleCreateForm->isSubmitted() && $articleCreateForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'article enregistré');
        }

        $articleCreateFormView = $articleCreateForm->createView();

        return $this->render('admin/update.html.twig', [
            'articleForm' => $articleCreateFormView
        ]);

    }


}