<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ConfirmDeletionFormType;
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

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie enregistrée.');
        }

        return $this->render('admin_category/edit.html.twig', [
            'category' => $category,
            'category_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ConfirmDeletionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a été supprimé.');
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin_category/delete.html.twig', [
            'category' => $category,
            'deletion_form' => $form->createView()
        ]);
    }
}
