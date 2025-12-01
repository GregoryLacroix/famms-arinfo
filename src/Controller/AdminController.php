<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AdminController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category/add', name: 'app_admin_category_add')]
    #[Route('/admin/category/update/{id}', name: 'app_admin_category_update')]
    public function adminCategoryForm(?Category $category, Request $request): Response
    {
        if (!$category)
            $category = new Category;

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $txt = "modifiée";
            if (!$category->getId()) {
                $txt = "enregistrée";
                $category->setCreatedAt(new \DateTimeImmutable());
            }
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', "La catégorie a été $txt.");

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin/category.form.html.twig', [
            'formCategory' => $form,
            'categoryId' => $category->getId()
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category', name: 'app_admin_category')]
    public function adminCategory(CategoryRepository $categoryRepository): Response
    {
        $dataCategorys = $categoryRepository->findAll();
        dump($dataCategorys);

        return $this->render('admin/category.html.twig', ['dataCategorys' => $dataCategorys]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category/remove/{id}', name: 'app_admin_category_remove')]
    public function adminCategoryRemove(Category $category)
    {
        if (!$category->getProducts()->isEmpty()) {
            $this->addFlash('danger', "Impossible de supprimer la catégorie " . $category->getName() . ", des produits y sont encore associés.");
            return $this->redirectToRoute('app_admin_category');
        }

        $this->em->remove($category);
        $this->em->flush();

        $this->addFlash('success', "La catégorie a été supprimée");
        return $this->redirectToRoute('app_admin_category');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/product/add', name: 'app_admin_product_add')]
    #[Route('/admin/product/update/{id}', name: 'app_admin_product_update')]
    public function adminProductForm(?Product $product, Request $request, SluggerInterface $slugger): Response
    {
        if (!$product)
            $product = new Product;

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $slugger->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                $currentPath = $this->getParameter('photo_directory');

                try {
                    $pictureFile->move($currentPath, $newFileName);
                } catch (FileException $e) {
                    dump($e->getMessage());
                }

                $product->setPicture($newFileName);
            }

            $txt = "modifié";
            if (!$product->getId()) {
                $txt = "enregistré";
                $product->setCreatedAt(new \DateTimeImmutable());
            }
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', "L'article a bien été $txt.");

            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin/product.form.html.twig', [
            'formProduct' => $form,
            'productId' => $product->getId(),
            'pictureFile' => $product->getPicture()
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/product', name: 'app_admin_product')]
    public function adminProduct(ProductRepository $productRepository): Response
    {
        $dataProducts = $productRepository->findAll();

        return $this->render('admin/product.html.twig', [
            'dataProducts' => $dataProducts
        ]);
    }
}
