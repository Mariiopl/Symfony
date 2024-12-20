<?php

namespace App\Controller;

use App\Entity\Respuesta;
use App\Form\RespuestaType;
use App\Repository\RespuestaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/respuesta')]
final class RespuestaController extends AbstractController
{
    #[Route(name: 'app_respuesta_index', methods: ['GET'])]
    public function index(RespuestaRepository $respuestaRepository): Response
    {
        return $this->render('respuesta/index.html.twig', [
            'respuestas' => $respuestaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_respuesta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $respuestum = new Respuesta();
        $form = $this->createForm(RespuestaType::class, $respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($respuestum);
            $entityManager->flush();

            return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('respuesta/new.html.twig', [
            'respuestum' => $respuestum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_respuesta_show', methods: ['GET'])]
    public function show(Respuesta $respuestum): Response
    {
        return $this->render('respuesta/show.html.twig', [
            'respuestum' => $respuestum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_respuesta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Respuesta $respuestum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RespuestaType::class, $respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('respuesta/edit.html.twig', [
            'respuestum' => $respuestum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_respuesta_delete', methods: ['POST'])]
    public function delete(Request $request, Respuesta $respuestum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$respuestum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($respuestum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
    }
}
