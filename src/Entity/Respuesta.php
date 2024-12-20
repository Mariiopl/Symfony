<?php

namespace App\Entity;

use App\Repository\RespuestaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RespuestaRepository::class)]
class Respuesta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $marca_temporal = null;

    #[ORM\Column(length: 1)]
    private ?string $respuesta_elegida = null;

    #[ORM\ManyToOne(inversedBy: 'respuestas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pregunta $Pregunta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarcaTemporal(): ?\DateTimeInterface
    {
        return $this->marca_temporal;
    }

    public function setMarcaTemporal(\DateTimeInterface $marca_temporal): static
    {
        $this->marca_temporal = $marca_temporal;

        return $this;
    }

    public function getRespuestaElegida(): ?string
    {
        return $this->respuesta_elegida;
    }

    public function setRespuestaElegida(string $respuesta_elegida): static
    {
        $this->respuesta_elegida = $respuesta_elegida;

        return $this;
    }

    public function getPregunta(): ?Pregunta
    {
        return $this->Pregunta;
    }

    public function setPregunta(?Pregunta $Pregunta): static
    {
        $this->Pregunta = $Pregunta;

        return $this;
    }
}
