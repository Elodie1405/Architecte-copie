<?php

namespace App\Entity;

use App\Repository\DisponibilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibilityRepository::class)
 */
class Disponibility
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\OneToOne(targetEntity=Rdv::class, mappedBy="dispo", cascade={"persist", "remove"})
     */
    private $rdv;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart()
    {
            return $this->start;
    }
        
    

    public function setStart(\DateTime $start): self
    {
        $this->start = $start;

        return $this;
    }


    public function __toString()
    {
    return $this->start->format('d-m-Y H:i');
    }

    public function getRdv(): ?Rdv
    {
        return $this->rdv;
    }

    public function setRdv(Rdv $rdv): self
    {
        // set the owning side of the relation if necessary
        if ($rdv->getDispo() !== $this) {
            $rdv->setDispo($this);
        }

        $this->rdv = $rdv;

        return $this;
    }

    public function isReserved(): ?bool
    {
        return $this->reserved;
    }

    public function setReserved(bool $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

}
