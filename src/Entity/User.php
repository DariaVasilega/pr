<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="`user`")
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
     * @ORM\Column(type="json")
     * @Assert\NotBlank()
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="5", max="120")
     * @Assert\Email(checkHost="true", mode="html5")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "must be at least 5 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 65,
     *      minMessage = "must be at least 8 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "must be at least 3 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "must be at least 3 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $surname;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Status", inversedBy="user", cascade={"persist", "remove"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Friend", mappedBy="user")
     */
    private $friends;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", mappedBy="user")
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Media", mappedBy="user")
     */
    private $media;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Media", inversedBy="owner", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verification;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friend $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->setUser($this);
        }

        return $this;
    }

    public function removeFriend(Friend $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
            // set the owning side to null (unless already changed)
            if ($friend->getUser() === $this) {
                $friend->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeUser($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name.'';
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->addUser($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            $medium->removeUser($this);
        }

        return $this;
    }

    public function getAvatar(): ?Media
    {
        return $this->avatar;
    }

    public function setAvatar(?Media $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getVerification(): ?bool
    {
        return $this->verification;
    }

    public function setVerification(bool $verification): self
    {
        $this->verification = $verification;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
