<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/operation')]
final class OperationController extends AbstractController
{
    #[Route(name: 'app__index', methods: ['GET'])]
    public function index(OperationRepository $operationRepository): Response
    {
        return $this->render('/index.html.twig', [
            'operations' => $operationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app__new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('app__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/new.html.twig', [
            'operation' => $operation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app__show', methods: ['GET'])]
    public function show(Operation $operation): Response
    {
        return $this->render('/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app__edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app__index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/edit.html.twig', [
            'operation' => $operation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app__delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app__index', [], Response::HTTP_SEE_OTHER);
    }
}
