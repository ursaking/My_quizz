<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quizz", mappedBy="id_user")
     */
    private $quizz_id;

    public function __construct()
    {
        $this->quizz_id = new ArrayCollection();
        $this->roles = array('ROLE_USER', 'ROLE_ADMIN');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles(array $roles): self
    {
        // dd($roles);
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
    }
    /**
     * @return Collection|Quizz[]
     */
    public function getQuizzId(): Collection
    {
        return $this->quizz_id;
    }

    public function addQuizzId(Quizz $quizzId): self
    {
        if (!$this->quizz_id->contains($quizzId)) {
            $this->quizz_id[] = $quizzId;
            $quizzId->setIdUser($this);
        }

        return $this;
    }

    public function removeQuizzId(Quizz $quizzId): self
    {
        if ($this->quizz_id->contains($quizzId)) {
            $this->quizz_id->removeElement($quizzId);
            // set the owning side to null (unless already changed)
            if ($quizzId->getIdUser() === $this) {
                $quizzId->setIdUser(null);
            }
        }

        return $this;
    }
}
