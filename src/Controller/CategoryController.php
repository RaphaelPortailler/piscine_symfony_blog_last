<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
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
    }}