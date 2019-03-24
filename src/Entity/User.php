<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     *@Groups({"auth-token"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @Groups({"users", "auth-token"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Groups({"users", "auth-token"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Groups({"users", "auth-token"})
     * @ORM\Column(type="json_array")
     */
    private $roles = array();


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AuthToken", mappedBy="user")
     */
    private $authTokens;

    public function __construct()
    {
        $this->authTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt()
    {
        return null;
    }
    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
         $this->password = null;

    }

    /**
     * @return Collection|AuthToken[]
     */
    public function getAuthTokens(): Collection
    {
        return $this->authTokens;
    }

    public function addAuthToken(AuthToken $authToken): self
    {
        if (!$this->authTokens->contains($authToken)) {
            $this->authTokens[] = $authToken;
            $authToken->setUser($this);
        }

        return $this;
    }

    public function removeAuthToken(AuthToken $authToken): self
    {
        if ($this->authTokens->contains($authToken)) {
            $this->authTokens->removeElement($authToken);
            // set the owning side to null (unless already changed)
            if ($authToken->getUser() === $this) {
                $authToken->setUser(null);
            }
        }

        return $this;
    }
}
