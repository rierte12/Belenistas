<?php

namespace App\Entity;

use App\Repository\GuardRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints\Date;


#[ORM\Entity(repositoryClass: GuardRepository::class)]
class Guard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\CustomIdGenerator(class: Uuid::class)]
    private Uuid $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTime $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTime $endTime;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $totalUsers;

    #[ORM\ManyToOne(inversedBy: 'guards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTotalUsers(): int
    {
        return $this->totalUsers;
    }

    public function setTotalUsers(int $totalUsers): static
    {
        $this->totalUsers = $totalUsers;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}
