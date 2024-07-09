<?php
namespace App\Dto;

class ReviewDto {

    private ?int $id = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $content = null;
    private ?string $createdOn = null;
    private ?string $updatedOn = null;
    private ?int $bookid = null;

    public function __construct(){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedOn(): ?string
    {
        return $this->createdOn;
    }

    public function setCreatedOn(string $createdOn): static
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getUpdatedOn(): ?string
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(string $updatedOn): static
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    public function getBookid(): ?int
    {
        return $this->bookid;
    }

    public function setBookid(int $bookid): static
    {
        $this->bookid = $bookid;

        return $this;
    }
}