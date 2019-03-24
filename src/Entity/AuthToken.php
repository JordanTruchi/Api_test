<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthTokenRepository")
 */
class AuthToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"auth-token"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"auth-token"})
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"auth-token"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="authTokens")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"auth-token"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): ?self
    {
        $this->id = $id;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
