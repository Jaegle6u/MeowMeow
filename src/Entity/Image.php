<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    

    /**
     * @var UploadedFile
     * 
     * @Assert\Image(maxSize = "3M")
     */
    private $file;

    // /**
    //  * @ORM\OneToOne(targetEntity=Cat::class, inversedBy="image")
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $cat;

    /**
     * @var ?string
     */
    private $oldPath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
        $this->path = "";

        return $this;
    }
    
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    
    public function getPublicRootDir(): string{
        return __DIR__ . '/../../public/uploads/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generatePath(): void{
        if($this->file instanceof UploadedFile){
            $this->path = uniqid('img_').'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(): void{
        if (is_file($this->getPublicRootDir().$this->oldPath)) {
            unlink($this->getPublicRootDir().$this->oldPath);
        }

        if($this->file instanceof UploadedFile){
            $this->file->move($this->getPublicRootDir(), $this->path);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function remove(){
        if(is_file($this->getPublicRootDir().$this->path)){
            unlink($this->getPublicRootDir().$this->path);
        }
    }

    public function getWebPath(): string 
    {
        return '/uploads/'.$this->path;
    }
}
