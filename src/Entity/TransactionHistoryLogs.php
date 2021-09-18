<?php

namespace App\Entity;

use App\Repository\TransactionHistoryLogsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionHistoryLogsRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class TransactionHistoryLogs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $send;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $receive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="logs")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSend(): ?string
    {
        return $this->send;
    }

    /**
     * @param string|null $send
     * @return $this
     */
    public function setSend(?string $send): self
    {
        $this->send = $send;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceive(): ?string
    {
        return $this->receive;
    }

    /**
     * @param string|null $receive
     * @return $this
     */
    public function setReceive(?string $receive): self
    {
        $this->receive = $receive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime('now');
        }
    }
}
