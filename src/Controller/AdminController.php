<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN") 
 */
#[Route('/admin')]
class AdminController extends AbstractController
{

  #[Route('/', name: 'app_admin_index', methods: ['GET'])]
  public function index(UserRepository $userRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
  {
    return $this->render('admin/index.html.twig', [
      'products' => $productRepository->findAll(),
      'categories' => $categoryRepository->findAll(),

    ]);
  }

  #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, User $user, UserRepository $userRepository): Response
  {
    $form = $this->createForm(User1Type::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $userRepository->save($user, true);

      return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin/edit.html.twig', [
      'user' => $user,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
  public function delete(Request $request, User $user, UserRepository $userRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
      $userRepository->remove($user, true);
    }

    return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
  }
}
