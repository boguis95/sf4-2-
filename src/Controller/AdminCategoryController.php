<?php

namespace App\Controller;

use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCategoryController extends AbstractController
{
    /**
     * @Route("-list", name="list")
     */
    public function index(CategoryRepository $repository)
    {
        $categories = $repository->findAll();

        return $this->render('admin_category/index.html.twig', [
            'category_list' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle catégorie enregistrée.');
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin_category/add.html.twig', [
            'category_form' => $form->createView()
        ]);
    }
}
