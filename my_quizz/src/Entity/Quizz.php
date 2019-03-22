<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizzRepository")
 */
class Quizz
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
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quizz_id")
     */
    private $id_user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="quizz")
     */
    private $id_question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="quizz")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    public function __construct()
    {
        $this->id_reponse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getIdReponse(): Collection
    {
        return $this->id_reponse;
    }

    public function addIdReponse(Reponse $idReponse): self
    {
        if (!$this->id_reponse->contains($idReponse)) {
            $this->id_reponse[] = $idReponse;
            $idReponse->setIdQuizz($this);
        }

        return $this;
    }

    public function removeIdReponse(Reponse $idReponse): self
    {
        if ($this->id_reponse->contains($idReponse)) {
            $this->id_reponse->removeElement($idReponse);
            // set the owning side to null (unless already changed)
            if ($idReponse->getIdQuizz() === $this) {
                $idReponse->setIdQuizz(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }
}
