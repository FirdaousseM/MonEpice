<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/category')]
class CategoryController extends AbstractController
{
  #[Route('/', name: 'app_category_index', methods: ['GET'])]
  public function index(CategoryRepository $categoryRepository): Response
  {
    return $this->render('category/index.html.twig', [
      'categories' => $categoryRepository->findAll(),
    ]);
  }

  /**
   * @IsGranted("ROLE_ADMIN") 
   */
  #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
  public function new(Request $request, CategoryRepository $categoryRepository): Response
  {
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $file = $form['imagePath']->getData();

      $extension = $file->guessExtension();
      if (!$extension) {
          // extension cannot be guessed
          $extension = 'bin';
      }
      
      $newFileName = rand(1, 99999) . '.' . $extension;
      $file->move('img/category/', $newFileName);
      
      $newFilePath = 'img/category/' . $newFileName;
      
      $category->setImagePath($newFilePath);

      $categoryRepository->save($category, true);
      return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('category/new.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
  public function show(Category $category): Response
  {
    return $this->render('category/show.html.twig', [
      'category' => $category,
    ]);
  }

  /**
   * @IsGranted("ROLE_ADMIN") 
   */
  #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
  {
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $file = $form['imagePath']->getData();

      $extension = $file->guessExtension();
      if (!$extension) {
          // extension cannot be guessed
          $extension = 'bin';
      }
      
      $newFileName = rand(1, 99999) . '.' . $extension;
      $file->move('img/category/', $newFileName);
      
      $newFilePath = 'img/category/' . $newFileName;
      
      $category->setImagePath($newFilePath);
      
      $categoryRepository->save($category, true);

      return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('category/edit.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  /**
   * @IsGranted("ROLE_ADMIN") 
   */
  #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
  public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
      $categoryRepository->remove($category, true);
    }

    return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
  }
}
