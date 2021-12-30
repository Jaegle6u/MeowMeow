<?php

namespace App\Entity;

use App\Repository\CatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ORM\Entity(repositoryClass=CatRepository::class)
 */
class Cat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $breed;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $location;

   

    /**
     * @ORM\Column(type="boolean")
     */
    private $publish = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

     /**
     * 
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $image;

    /**
     * @var bool
     */
    private $deleteImage = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cats")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="cat", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->image = new Image();
        $this->likes = new ArrayCollection();
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

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): self
    {
        $this->breed = $breed;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
    public function date_ToString()
    {
        return $this->birthDate->format('d/m/Y');
        
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

   
    public function getImage(): ?Image
    {
        return $this->image;
    }
    
    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    // public function addImage(Image $image): self
    // {
    //     if (!$this->image->contains($image)) {
    //         $this->image[] = $image;
    //         $image->setCat($this);
    //     }

    //     return $this;
    // }

    // public function removeImage(Image $image): self
    // {
    //     if ($this->image->removeElement($image)) {
    //         // set the owning side to null (unless already changed)
    //         if ($image->getCat() === $this) {
    //             $image->setCat(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * Get the value of deleteImage
     *
     * @return  bool
     */ 
    public function getDeleteImage()
    {
        return $this->deleteImage;
    }

    /**
     * Set the value of deleteImage
     *
     * @param  bool  $deleteImage
     *
     * @return  self
     */ 
    public function setDeleteImage(bool $deleteImage)
    {
        $this->deleteImage = $deleteImage;

        if ($deleteImage) {
            $this->image = null;
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setCat($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getCat() === $this) {
                $like->setCat(null);
            }
        }

        return $this;
    }
    
}
