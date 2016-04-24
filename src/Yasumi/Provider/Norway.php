<?php
/**
 *  This file is part of the Yasumi package.
 *
 *  Copyright (c) 2015 - 2016 AzuyaLabs
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <stelgenhof@gmail.com>
 */

namespace Yasumi\Provider;

use DateTime;
use DateTimeZone;
use Yasumi\Holiday;

/**
 * Provider for all holidays in Norway.
 */
class Norway extends AbstractProvider
{
    use CommonHolidays, ChristianHolidays;

    /**
     * Initialize holidays for Norway.
     */
    public function initialize()
    {
        $this->timezone = 'Europe/Oslo';

        // Add common holidays
        $this->addHoliday($this->newYearsDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->internationalWorkersDay($this->year, $this->timezone, $this->locale));

        // Add common Christian holidays (common in Norway)
        $this->addHoliday($this->maundyThursday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->goodFriday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->easter($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->easterMonday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->ascensionDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->pentecost($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->pentecostMonday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->christmasDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->secondChristmasDay($this->year, $this->timezone, $this->locale));

        // Calculate other holidays
        $this->calculateConstitutionDay();
    }

    /*
     * Constitution Day
     *
     * Norway’s Constitution Day is May 17 and commemorates the signing of Norways's constitution at Eidsvoll on
     * May 17, 1814. It’s usually referred to as syttende mai (May 17) or Nasjonaldagen (The National Day) in Norwegian.
     *
     * Norway adopted its constitution on May 16 1814 and it was signed on May 17, 1814, ending almost 100 years of a
     * coalition with Sweden, proceeded by nearly 400 years of Danish rule. The Norwegian Parliament, known as
     * Stortinget, held the first May 17 celebrations in 1836, and since it has been regarded as Norway’s National Day.
     *
     * @link https://en.wikipedia.org/wiki/Store_Bededag
     */
    public function calculateConstitutionDay()
    {
        if ($this->year >= 1836) {
            $this->addHoliday(new Holiday('constitutionDay', ['nb_NO' => 'Nasjonaldagen'],
                new DateTime("$this->year-5-17", new DateTimeZone($this->timezone)), $this->locale));
        }
    }
}
