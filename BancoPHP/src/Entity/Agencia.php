<?php

namespace App\Entity;

use App\Repository\AgenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenciaRepository::class)]
class Agencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomeAgencia ;

    #[ORM\Column(length: 255)]
    private ?string $codigo ;

    #[ORM\Column(length: 255)]
    private ?string $endereco ;

    #[ORM\OneToOne(inversedBy: 'agencia', cascade: ['persist', 'remove'])]
    private ?Gerente $gerente ;

    #[ORM\OneToMany(mappedBy: 'agencia', targetEntity: Conta::class)]
    private Collection $contas;

    public function __construct()
    {
        $this->contas = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->nomeAgencia;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomeAgencia(): ?string
    {
        return $this->nomeAgencia;
    }

    public function setNomeAgencia(string $nomeAgencia): self
    {
        $this->nomeAgencia = $nomeAgencia;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    public function setEndereco(string $endereco): self
    {
        $this->endereco = $endereco;

        return $this;
    }

    public function getGerente(): ?Gerente
    {
        return $this->gerente;
    }

    public function setGerente(?Gerente $gerente): self
    {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * @return Collection<int, Conta>
     */
    public function getContas(): Collection
    {
        return $this->contas;
    }

    public function addConta(Conta $conta): self
    {
        if (!$this->contas->contains($conta)) {
            $this->contas->add($conta);
            $conta->setAgencia($this);
        }

        return $this;
    }

    public function removeConta(Conta $conta): self
    {
        if ($this->contas->removeElement($conta)) {
            // set the owning side to null (unless already changed)
            if ($conta->getAgencia() === $this) {
                $conta->setAgencia(null);
            }
        }

        return $this;
    }
}
