<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil, affichage des nouveaux produits
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        // On utilise notre propre méthode pour récupérer les nouveautés
        $new_products = $productRepository->findNews();

        $category_list = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'new_products' => $new_products,
            'category_list' => $category_list
        ]);
    }
}
