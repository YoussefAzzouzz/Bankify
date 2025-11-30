<?php

namespace App\Entity;

use App\Repository\CalendarEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
class CalendarEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $event_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $event_start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $event_end_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->event_name;
    }

    public function setEventName(?string $event_name): static
    {
        $this->event_name = $event_name;

        return $this;
    }

    public function getEventStartDate(): ?\DateTimeInterface
    {
        return $this->event_start_date;
    }

    public function setEventStartDate(?\DateTimeInterface $event_start_date): static
    {
        $this->event_start_date = $event_start_date;

        return $this;
    }

    public function getEventEndDate(): ?\DateTimeInterface
    {
        return $this->event_end_date;
    }

    public function setEventEndDate(?\DateTimeInterface $event_end_date): static
    {
        $this->event_end_date = $event_end_date;

        return $this;
    }
}
