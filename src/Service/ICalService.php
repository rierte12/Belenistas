<?php

namespace App\Service;

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use DateTime;
use DateInterval;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\Alarm\DisplayAction;
use Eluceo\iCal\Domain\ValueObject\Alarm\RelativeTrigger;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class ICalService
{
    private Event $event;
    private Calendar $calendar;
    private string $content;

    /**
     * @param string $summary Event Name
     * @param string $description Event Description
     * @param string $date Event Datetime format
     */
    public function __construct(string $summary, string $description, DateTime $start, DateTime $end)
    {
        $this->event = new Event();
        $this->event->setSummary($summary);
        $this->event->setDescription($description);
        $this->event->setOccurrence(
            new TimeSpan(
                new \Eluceo\iCal\Domain\ValueObject\DateTime($start, true),
                new \Eluceo\iCal\Domain\ValueObject\DateTime($end, true)
            )
        );
    }

    public function addOrganizer(string $name, string $email): void
    {
        $this->event->setOrganizer(
            new Organizer(
                new EmailAddress($email),
                $name
            )
        );
    }

    public function addLocation(string $location): void
    {
        $this->event->setLocation(new Location($location));
    }

    public function addAlarm(string $message, string $time) {
        $this->event->addAlarm(
            new Alarm(
                new DisplayAction($message),
                (new RelativeTrigger(DateInterval::createFromDateString($time)))->withRelationToStart()
            )
        );
    }

    public function create() : void
    {
        $this->calendar = new Calendar([$this->event]);
        $calendarFactory = new CalendarFactory();
        $this->content = $calendarFactory->createCalendar($this->calendar);
    }

    public function getCalendar() : string
    {
        return $this->content;
    }

    public function getEncodedCalendar() : string
    {
        return base64_encode($this->content);
    }

    public function getMimeType() : string
    {
        return 'text/calendar';
    }

}