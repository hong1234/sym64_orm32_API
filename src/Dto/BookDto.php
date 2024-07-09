<?php
namespace App\Dto;

class BookDto {

    private ?int $id = null;
    private ?string $title = null;
    private ?string $content = null;
    private ?string $createdOn = null;
    private ?string $updatedOn = null;
    private ?array $reviews = null;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getReviews(): ?array
    {

        return $this->reviews;
    }

    public function setReviews(array $reviews=[]): static
    {
        $this->reviews = $reviews;

        return $this;
    }

}
