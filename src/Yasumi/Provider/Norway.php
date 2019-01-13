<?php
/**
 * This file is part of the Yasumi package.
 *
 * Copyright (c) 2015 - 2019 AzuyaLabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <me@sachatelgenhof.com>
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
     * Code to identify this Holiday Provider. Typically this is the ISO3166 code corresponding to the respective
     * country or sub-region.
     */
    public const ID = 'NO';

    /**
     * Initialize holidays for Norway.
     *
     * @throws \Yasumi\Exception\InvalidDateException
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function initialize(): void
    {
        $this->timezone = 'Europe/Oslo';

        // Add common holidays
        $this->addHoliday($this->newYearsDay($this->year, $this->timezone));
        $this->addHoliday($this->internationalWorkersDay($this->year, $this->timezone));

        // Add common Christian holidays (common in Norway)
        $this->addHoliday($this->maundyThursday($this->year, $this->timezone));
        $this->addHoliday($this->goodFriday($this->year, $this->timezone));
        $this->addHoliday($this->easter($this->year, $this->timezone));
        $this->addHoliday($this->easterMonday($this->year, $this->timezone));
        $this->addHoliday($this->ascensionDay($this->year, $this->timezone));
        $this->addHoliday($this->pentecost($this->year, $this->timezone));
        $this->addHoliday($this->pentecostMonday($this->year, $this->timezone));
        $this->addHoliday($this->christmasDay($this->year, $this->timezone));
        $this->addHoliday($this->secondChristmasDay($this->year, $this->timezone));

        // Calculate other holidays
        $this->calculateConstitutionDay();
    }

    /**
     * Constitution Day
     *
     * Norway’s Constitution Day is May 17 and commemorates the signing of Norways's constitution at Eidsvoll on
     * May 17, 1814. It’s usually referred to as Grunnlovsdag(en) ((The) Constitution Day), syttende mai (May 17) or
     * Nasjonaldagen (The National Day) in Norwegian.
     *
     * Norway adopted its constitution on May 16 1814 and it was signed on May 17, 1814, ending almost 100 years of a
     * coalition with Sweden, proceeded by nearly 400 years of Danish rule. The Norwegian Parliament, known as
     * Stortinget, held the first May 17 celebrations in 1836, and since it has been regarded as Norway’s National Day.
     *
     * @link https://en.wikipedia.org/wiki/Norwegian_Constitution_Day
     *
     * @throws \Yasumi\Exception\InvalidDateException
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function calculateConstitutionDay(): void
    {
        if ($this->year >= 1836) {
            $this->addHoliday(new Holiday(
                'constitutionDay',
                ['nb_NO' => 'Grunnlovsdagen'],
                new DateTime("$this->year-5-17", new DateTimeZone($this->timezone))
            ));
        }
    }
}
