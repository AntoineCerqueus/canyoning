<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceRepository::class)
 */
class Price
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $normal;

    /**
     * @ORM\Column(type="integer")
     */
    private $reduced;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity=Canyon::class, inversedBy="prices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $canyon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNormal(): ?int
    {
        return $this->normal;
    }

    public function setNormal(int $normal): self
    {
        $this->normal = $normal;

        return $this;
    }

    public function getReduced(): ?int
    {
        return $this->reduced;
    }

    public function setReduced(int $reduced): self
    {
        $this->reduced = $reduced;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCanyon(): ?Canyon
    {
        return $this->canyon;
    }

    public function setCanyon(?Canyon $canyon): self
    {
        $this->canyon = $canyon;

        return $this;
    }
}
