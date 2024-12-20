<?php

namespace App\Controller;

use App\Entity\Pregunta;
use App\Repository\PreguntaRepository;
use App\Repository\RespuestaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response; // Importa la clase correcta
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/pregunta', name: 'api_pregunta', methods: ['GET'])]
    public function getPreguntaActiva(PreguntaRepository $preguntaRepository): Response
    {
        // Obtener la pregunta activa
        $preguntaActiva = $preguntaRepository->createQueryBuilder('p')
            ->where('p.f_inicio <= :now')
            ->andWhere('p.f_fin >= :now')
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$preguntaActiva) {
            return $this->render('api/noPregunta.html.twig', [
                'error' => 'No hay pregunta activa.',
            ]);
        }

        return $this->render('api/preguntaActiva.html.twig', [
            'preguntaActiva' => $preguntaActiva,
        ]);
    }

    #[Route('/api/estadisticas/{id}', name: 'api_estadisticas', methods: ['GET'])]
    public function getEstadisticasRespuestas(Pregunta $pregunta, RespuestaRepository $respuestaRepository): JsonResponse
    {
        // Obtener las respuestas de la pregunta solicitada
        $respuestas = $respuestaRepository->findBy(['pregunta' => $pregunta]);

        // Inicializar contadores de respuestas
        $estadisticas = [
            'a' => 0,
            'b' => 0,
            'c' => 0,
            'd' => 0,
        ];

        // Contar las respuestas seleccionadas
        foreach ($respuestas as $respuesta) {
            $respuestaElegida = $respuesta->getRespuestaElegida();
            if (isset($estadisticas[$respuestaElegida])) {
                $estadisticas[$respuestaElegida]++;
            }
        }

        return new JsonResponse($estadisticas);
    }
}
