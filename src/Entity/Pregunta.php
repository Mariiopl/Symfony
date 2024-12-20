<?php

namespace App\Entity;

use App\Repository\PreguntaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreguntaRepository::class)]
class Pregunta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $enunciado = null;

    #[ORM\Column(length: 255)]
    private ?string $opcion_a = null;

    #[ORM\Column(length: 255)]
    private ?string $opcion_b = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $opcion_c = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $opcion_d = null;

    #[ORM\Column(length: 1)]
    private ?string $opcion_correcta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $f_inicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $f_fin = null;

    /**
     * @var Collection<int, Respuesta>
     */
    #[ORM\OneToMany(targetEntity: Respuesta::class, mappedBy: 'Pregunta')]
    private Collection $respuestas;

    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnunciado(): ?string
    {
        return $this->enunciado;
    }

    public function setEnunciado(string $enunciado): static
    {
        $this->enunciado = $enunciado;

        return $this;
    }

    public function getOpcionA(): ?string
    {
        return $this->opcion_a;
    }

    public function setOpcionA(string $opcion_a): static
    {
        $this->opcion_a = $opcion_a;

        return $this;
    }

    public function getOpcionB(): ?string
    {
        return $this->opcion_b;
    }

    public function setOpcionB(string $opcion_b): static
    {
        $this->opcion_b = $opcion_b;

        return $this;
    }

    public function getOpcionC(): ?string
    {
        return $this->opcion_c;
    }

    public function setOpcionC(?string $opcion_c): static
    {
        $this->opcion_c = $opcion_c;

        return $this;
    }

    public function getOpcionD(): ?string
    {
        return $this->opcion_d;
    }

    public function setOpcionD(?string $opcion_d): static
    {
        $this->opcion_d = $opcion_d;

        return $this;
    }

    public function getOpcionCorrecta(): ?string
    {
        return $this->opcion_correcta;
    }

    public function setOpcionCorrecta(string $opcion_correcta): static
    {
        $this->opcion_correcta = $opcion_correcta;

        return $this;
    }

    public function getFInicio(): ?\DateTimeInterface
    {
        return $this->f_inicio;
    }

    public function setFInicio(\DateTimeInterface $f_inicio): static
    {
        $this->f_inicio = $f_inicio;

        return $this;
    }

    public function getFFin(): ?\DateTimeInterface
    {
        return $this->f_fin;
    }

    public function setFFin(\DateTimeInterface $f_fin): static
    {
        $this->f_fin = $f_fin;

        return $this;
    }

    /**
     * @return Collection<int, Respuesta>
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Respuesta $respuesta): static
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas->add($respuesta);
            $respuesta->setPregunta($this);
        }

        return $this;
    }

    public function removeRespuesta(Respuesta $respuesta): static
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getPregunta() === $this) {
                $respuesta->setPregunta(null);
            }
        }

        return $this;
    }
}
