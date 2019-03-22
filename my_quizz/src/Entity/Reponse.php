<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reponse_exception;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="reponse_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getReponseException(): ?bool
    {
        return $this->reponse_exception;
    }

    public function setReponseException(bool $reponse_exception): self
    {
        $this->reponse_exception = $reponse_exception;

        return $this;
    }


    public function getIdQuizz(): ?Quizz
    {
        return $this->id_quizz;
    }

    public function setIdQuizz(?Quizz $id_quizz): self
    {
        $this->id_quizz = $id_quizz;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}
