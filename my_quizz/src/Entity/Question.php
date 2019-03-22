<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $question;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quizz", inversedBy="id_question")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizz;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="question")
     */
    private $reponse_id;

    public function __construct()
    {
        $this->reponse_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponseId(): Collection
    {
        return $this->reponse_id;
    }

    public function addReponseId(Reponse $reponseId): self
    {
        if (!$this->reponse_id->contains($reponseId)) {
            $this->reponse_id[] = $reponseId;
            $reponseId->setQuestion($this);
        }

        return $this;
    }

    public function removeReponseId(Reponse $reponseId): self
    {
        if ($this->reponse_id->contains($reponseId)) {
            $this->reponse_id->removeElement($reponseId);
            // set the owning side to null (unless already changed)
            if ($reponseId->getQuestion() === $this) {
                $reponseId->setQuestion(null);
            }
        }

        return $this;
    }
}
