<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Serializer;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends Serializer implements UserInterface, NormalizableInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 50)]
    private string $email;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $birthdate;

    #[ORM\Column(type: 'string', length: 9, nullable: false)]
    private string $idNumber;

    #[ORM\Column(type: 'integer', length: 9)]
    private int $phone;

    #[ORM\ManyToMany(targetEntity: Guard::class, mappedBy: 'volunteers')]
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

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getIdNumber(): string
    {
        return $this->idNumber;
    }

    public function setIdNumber(string $idNumber): void
    {
        $this->idNumber = $idNumber;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): void
    {
        $this->phone = $phone;
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
            $guard->addVolunteer($this);
        }

        return $this;
    }

    public function removeGuard(Guard $guard): static
    {
        if ($this->guards->removeElement($guard)) {
            $guard->removeVolunteer($this);
        }

        return $this;
    }
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function getUserIdentifier(): string
    {
        return $this->idNumber;
        // TODO: Implement getUserIdentifier() method.
    }
}
