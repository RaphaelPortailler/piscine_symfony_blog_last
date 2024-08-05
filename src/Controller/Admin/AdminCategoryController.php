<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{

    #[Route('/admin/categories', 'admin_category')]
    public function adminListCategory(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/list_categories.html.twig', [
            'categories' => $categories
        ]);
    }



    #[Route('/admin/categories/delete/{id}', name: 'delete_category')]
    public function deleteArticle(int $id,CategoryRepository  $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $categories = $categoryRepository->find($id);

        if (!$categories) {
            $html = $this->renderView('admin/404.html.twig');
            return new Response($html, 404);
        }

        try{
            // j'utilise la classe entity manager
            // pour préparer la requête SQL de suppression
            // cette requête n'est pas executée tout de suite
            $entityManager->remove($categories);
            // j'execute la / les requête SQL préparée
            $entityManager->flush();

            $this->addFlash('success', 'Categorie bien supprimé');
        } catch(\Exception $exception){
            return $this->render('admin/error.html.twig', [
                'errorMessage' => $exception->getMessage()
            ]);
        }

        return $this->redirectToRoute('admin_category');
    }




    #[Route('/admin/categories/insert', name: 'insert_category')]
    public function insertCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $categoryCreateForm = $this->createForm(CategoryType::class ,$category);

        $categoryCreateForm->handleRequest($request);

        if ($categoryCreateForm->isSubmitted() && $categoryCreateForm->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categorie Créer');
        }

        $categoryCreateFormView = $categoryCreateForm->createView();

        return $this->render('admin/insert_categories.html.twig', [
            'categoryForm' => $categoryCreateFormView
        ]);
    }





    #[Route('/admin/categories/update/{id}', 'update_category')]
    public function updateArticle(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        $categoryCreateForm = $this->createForm(CategoryType::class, $category);

        $categoryCreateForm->handleRequest($request);

        if ($categoryCreateForm->isSubmitted() && $categoryCreateForm->isValid()) {
            $category->setUpdatedAt(new \DateTime('NOW'));
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Categorie enregistré');
        }

        $categoryCreateFormView = $categoryCreateForm->createView();

        return $this->render('admin/update_category.html.twig', [
            'categoryForm' => $categoryCreateFormView
        ]);

    }








}