<?php

namespace App\Entity;

use App\Repository\ContaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContaRepository::class)]
class Conta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?float $saldo = null;

    #[ORM\Column]
    private ?int $numeroDaConta ;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?Agencia $agencia;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?User $user ;

    #[ORM\Column]
    private ?bool $status ;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?TipoConta $tipos ;

    #[ORM\OneToMany(mappedBy: 'contaDestino', targetEntity: Transacao::class)]
    private Collection $transacaos;

    public function __construct()
    {
        $this->transacaos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->numeroDaConta;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(float $saldo): self
    {
        $this->saldo = $saldo;

        return $this;
    }

    public function getNumeroDaConta(): ?int
    {
        return $this->numeroDaConta;
    }

    public function setNumeroDaConta(int $numeroDaConta): self
    {
        $this->numeroDaConta = $numeroDaConta;

        return $this;
    }

    public function getAgencia(): ?Agencia
    {
        return $this->agencia;
    }

    public function setAgencia(?Agencia $agencia): self
    {
        $this->agencia = $agencia;

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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTipos(): ?TipoConta
    {
        return $this->tipos;
    }

    public function setTipos(?TipoConta $tipos): self
    {
        $this->tipos = $tipos;

        return $this;
    }

    /**
     * @return Collection<int, Transacao>
     */
    public function getTransacaos(): Collection
    {
        return $this->transacaos;
    }

    public function addTransacao(Transacao $transacao): self
    {
        if (!$this->transacaos->contains($transacao)) {
            $this->transacaos->add($transacao);
            $transacao->setContaDestino($this);
        }

        return $this;
    }

    public function removeTransacao(Transacao $transacao): self
    {
        if ($this->transacaos->removeElement($transacao)) {
            // set the owning side to null (unless already changed)
            if ($transacao->getContaDestino() === $this) {
                $transacao->setContaDestino(null);
            }
        }

        return $this;
    }
}
