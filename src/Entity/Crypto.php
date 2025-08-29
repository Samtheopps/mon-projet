<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoRepository::class)]
class Crypto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $symbol = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 8)]
    private ?string $currentPrice = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $lastPriceUpdate = null;

    #[ORM\OneToMany(mappedBy: 'crypto', targetEntity: Wallet::class, orphanRemoval: true)]
    private Collection $wallets;

    #[ORM\OneToMany(mappedBy: 'crypto', targetEntity: CryptoPrice::class, orphanRemoval: true)]
    private Collection $priceHistory;

    #[ORM\OneToMany(mappedBy: 'crypto', targetEntity: Transaction::class, orphanRemoval: true)]
    private Collection $transactions;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
        $this->priceHistory = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->lastPriceUpdate = new \DateTimeImmutable();
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

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = strtoupper($symbol);
        return $this;
    }

    public function getCurrentPrice(): ?string
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(string $currentPrice): self
    {
        $this->currentPrice = $currentPrice;
        $this->lastPriceUpdate = new \DateTimeImmutable();
        return $this;
    }

    public function getLastPriceUpdate(): ?\DateTimeImmutable
    {
        return $this->lastPriceUpdate;
    }

    public function setLastPriceUpdate(\DateTimeImmutable $lastPriceUpdate): self
    {
        $this->lastPriceUpdate = $lastPriceUpdate;
        return $this;
    }

    /**
     * @return Collection<int, Wallet>
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets->add($wallet);
            $wallet->setCrypto($this);
        }
        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->removeElement($wallet)) {
            if ($wallet->getCrypto() === $this) {
                $wallet->setCrypto(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CryptoPrice>
     */
    public function getPriceHistory(): Collection
    {
        return $this->priceHistory;
    }

    public function addPriceHistory(CryptoPrice $priceHistory): self
    {
        if (!$this->priceHistory->contains($priceHistory)) {
            $this->priceHistory->add($priceHistory);
            $priceHistory->setCrypto($this);
        }
        return $this;
    }

    public function removePriceHistory(CryptoPrice $priceHistory): self
    {
        if ($this->priceHistory->removeElement($priceHistory)) {
            if ($priceHistory->getCrypto() === $this) {
                $priceHistory->setCrypto(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setCrypto($this);
        }
        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            if ($transaction->getCrypto() === $this) {
                $transaction->setCrypto(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' (' . $this->symbol . ')';
    }
}
