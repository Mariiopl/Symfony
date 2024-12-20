<?php

namespace App\Controller;

use App\Entity\Pregunta;
use App\Form\Pregunta1Type;
use App\Entity\Respuesta;
use App\Repository\RespuestaRepository;
use App\Repository\PreguntaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pregunta')]
final class PreguntaController extends AbstractController
{
    #[Route(name: 'app_pregunta_index', methods: ['GET'])]
    public function index(PreguntaRepository $preguntaRepository): Response
    {
        return $this->render('pregunta/index.html.twig', [
            'preguntas' => $preguntaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pregunta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Restringir a administradores

        $preguntum = new Pregunta();
        $form = $this->createForm(Pregunta1Type::class, $preguntum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($preguntum);
            $entityManager->flush();

            return $this->redirectToRoute('app_pregunta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pregunta/new.html.twig', [
            'preguntum' => $preguntum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pregunta_show', methods: ['GET'])]
    public function show(Pregunta $preguntum): Response
    {
        return $this->render('pregunta/show.html.twig', [
            'preguntum' => $preguntum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pregunta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pregunta $preguntum, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Restringir a administradores

        $form = $this->createForm(Pregunta1Type::class, $preguntum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pregunta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pregunta/edit.html.twig', [
            'preguntum' => $preguntum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pregunta_delete', methods: ['POST'])]
    public function delete(Request $request, Pregunta $preguntum, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Restringir a administradores

        if ($this->isCsrfTokenValid('delete' . $preguntum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($preguntum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pregunta_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/submit', name: 'app_pregunta_submit', methods: ['POST'])]
    public function submit(Request $request, Pregunta $preguntum, EntityManagerInterface $entityManager): Response
    {
        // Obtener la respuesta seleccionada desde el formulario
        $respuestaSeleccionada = $request->request->get('respuesta');

        if (!$respuestaSeleccionada) {
            // Si no se selecciona una respuesta, redirigir con un mensaje de error
            $this->addFlash('error', 'Debes seleccionar una respuesta.');
            return $this->redirectToRoute('app_pregunta_show', ['id' => $preguntum->getId()]);
        }

        // Validar la respuesta seleccionada
        if ($respuestaSeleccionada === $preguntum->getOpcionCorrecta()) {
            $this->addFlash('success', '¡Correcto! Has seleccionado la respuesta correcta.');
        } else {
            $this->addFlash('error', 'Incorrecto. La respuesta correcta era: ' . strtoupper($preguntum->getOpcionCorrecta()));
        }

        // Guardar la respuesta en la base de datos
        $respuesta = new Respuesta();
        $respuesta->setPregunta($preguntum);
        $respuesta->setMarcaTemporal(new \DateTime());
        $respuesta->setRespuestaElegida($respuestaSeleccionada);

        $entityManager->persist($respuesta);
        $entityManager->flush();

        // Redirigir a la lista de preguntas o a otra página
        return $this->redirectToRoute('app_pregunta_index');
    }


    #[Route('/{id}/stats', name: 'app_pregunta_stats', methods: ['GET'])]
    public function stats(Pregunta $preguntum, RespuestaRepository $respuestaRepository): Response
    {
        // Obtener los conteos de respuestas por opción
        $datosGrafica = $respuestaRepository->obtenerConteoPorPregunta($preguntum->getId());

        // Convertir los datos para la vista
        $datos = [];
        foreach ($datosGrafica as $dato) {
            $datos[$dato['respuesta_elegida']] = $dato['total'];
        }

        return $this->render('pregunta/stats.html.twig', [
            'pregunta' => $preguntum,
            'datosGrafica' => $datos,
        ]);
    }
}
