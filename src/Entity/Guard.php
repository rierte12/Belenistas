<?php

namespace App\Entity;

use App\Repository\GuardRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;


#[ORM\Entity(repositoryClass: GuardRepository::class)]
class Guard
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[SerializedName('start')]
    private DateTime $startTime;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[SerializedName('end')]
    private DateTime $endTime;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $totalUsers;

    #[ORM\ManyToOne(inversedBy: 'guards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'guards')]
    private Collection $volunteers;

    public function __construct()
    {
        $this->volunteers = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): static
    {
        $this->startTime = $startTime;

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

    /**
     * @return Collection<int, User>
     */
    public function getVolunteers(): Collection
    {
        return $this->volunteers;
    }

    public function addVolunteer(User $volunteer): static
    {
        if (!$this->volunteers->contains($volunteer)) {
            $this->volunteers->add($volunteer);
        }

        return $this;
    }

    public function removeVolunteer(User $volunteer): static
    {
        $this->volunteers->removeElement($volunteer);

        return $this;
    }
}
