<?php

namespace App\Controller;

use App\Entity\CommandLine;
use App\Form\CommandLineType;
use App\Repository\CommandLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/command/line')]
class CommandLineController extends AbstractController
{
    #[Route('/', name: 'app_command_line_index', methods: ['GET'])]
    public function index(CommandLineRepository $commandLineRepository): Response
    {
        return $this->render('command_line/index.html.twig', [
            'command_lines' => $commandLineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_command_line_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandLineRepository $commandLineRepository): Response
    {
        $commandLine = new CommandLine();
        $form = $this->createForm(CommandLineType::class, $commandLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandLineRepository->save($commandLine, true);

            return $this->redirectToRoute('app_command_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_line/new.html.twig', [
            'command_line' => $commandLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_line_show', methods: ['GET'])]
    public function show(CommandLine $commandLine): Response
    {
        return $this->render('command_line/show.html.twig', [
            'command_line' => $commandLine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_command_line_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandLine $commandLine, CommandLineRepository $commandLineRepository): Response
    {
        $form = $this->createForm(CommandLineType::class, $commandLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandLineRepository->save($commandLine, true);

            return $this->redirectToRoute('app_command_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_line/edit.html.twig', [
            'command_line' => $commandLine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_line_delete', methods: ['POST'])]
    public function delete(Request $request, CommandLine $commandLine, CommandLineRepository $commandLineRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandLine->getId(), $request->request->get('_token'))) {
            $commandLineRepository->remove($commandLine, true);
        }

        return $this->redirectToRoute('app_command_line_index', [], Response::HTTP_SEE_OTHER);
    }
}
