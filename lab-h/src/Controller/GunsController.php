<?php

namespace App\Controller;

use App\Entity\Guns;
use App\Form\GunsType;
use App\Repository\GunsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/guns')]
final class GunsController extends AbstractController
{
    #[Route(name: 'app_guns_index', methods: ['GET'])]
    public function index(GunsRepository $gunsRepository): Response
    {
        return $this->render('guns/index.html.twig', [
            'guns' => $gunsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_guns_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gun = new Guns();
        $form = $this->createForm(GunsType::class, $gun);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gun);
            $entityManager->flush();

            return $this->redirectToRoute('app_guns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guns/new.html.twig', [
            'gun' => $gun,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guns_show', methods: ['GET'])]
    public function show(Guns $gun): Response
    {
        return $this->render('guns/show.html.twig', [
            'gun' => $gun,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_guns_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guns $gun, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GunsType::class, $gun);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_guns_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guns/edit.html.twig', [
            'gun' => $gun,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guns_delete', methods: ['POST'])]
    public function delete(Request $request, Guns $gun, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gun->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($gun);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_guns_index', [], Response::HTTP_SEE_OTHER);
    }
}
