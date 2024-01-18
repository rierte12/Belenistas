<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventRepository;
use App\Service\DateFormattingService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;
    #[ORM\Column(type: 'string', length: 50)]
    private string $name;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;
    #[ORM\Column(type: 'string')]
    private string $location;
    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $startDate;
    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $endDate;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Guard::class, orphanRemoval: true)]
    private Collection $guards;

    public function __construct()
    {
        $this->guards = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
    public function getStart(): DateTime
    {
        return $this->startDate;
    }

    public function setStart(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEnd(): DateTime
    {
        return $this->endDate;
    }

    public function getHumanStart(): string
    {
        return DateFormattingService::convertToHumanString($this->startDate);
    }

    public function getHumanEnd(): string
    {
        return DateFormattingService::convertToHumanString($this->endDate);
    }

    public function setEnd(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return Collection<int, Guard>
     */
    public function getGuards(): Collection
    {
        return $this->guards;
    }

    public function addGuard(Guard $guard): static
    {
        if (!$this->guards->contains($guard)) {
            $this->guards->add($guard);
            $guard->setEvent($this);
        }

        return $this;
    }

    public function removeGuard(Guard $guard): static
    {
        if ($this->guards->removeElement($guard)) {
            // set the owning side to null (unless already changed)
            if ($guard->getEvent() === $this) {
                $guard->setEvent(null);
            }
        }

        return $this;
    }


}
