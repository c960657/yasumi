<?php

/**
 * This file is part of the Yasumi package.
 *
 * Copyright (c) 2015 - 2018 AzuyaLabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <stelgenhof@gmail.com>
 */

namespace Yasumi\Provider;

use DateTime;
use DateTimeZone;
use Yasumi\Holiday;

/**
 * Provider for all holidays in Denmark.
 */
class Denmark extends AbstractProvider
{
    use CommonHolidays, ChristianHolidays;

    /**
     * Code to identify this Holiday Provider. Typically this is the ISO3166 code corresponding to the respective
     * country or sub-region.
     */
    const ID = 'DK';

    /**
     * Initialize holidays for Denmark.
     *
     * @throws \Yasumi\Exception\InvalidDateException
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function initialize()
    {
        $this->timezone = 'Europe/Copenhagen';


        // Add common holidays
        $tags = [Holiday::TAG_OFFICE_CLOSED, Holiday::TAG_SHOP_CLOSED, self::Holiday::TAG_BANK_CLOSED];
        $this->addHoliday($this->newYearsDay($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));

        // Add common Christian holidays (common in Denmark)
        $tags = [Holiday::TAG_OFFICE_CLOSED, Holiday::TAG_SHOP_CLOSED, self::Holiday::TAG_BANK_CLOSED, Holiday::TAG_SUBJECT_RELIGION];
        $this->addHoliday($this->maundyThursday($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->goodFriday($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->easter($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->easterMonday($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->ascensionDay($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->pentecost($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->pentecostMonday($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->christmasDay($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));
        $this->addHoliday($this->secondChristmasDay($this->year, $this->timezone, $this->locale, self::TYPE_OFFICIAL, $tags));

        // Calculate other holidays
        $this->calculateGreatPrayerDay($tags);

        // According to Lukkeloven (Opening Hours Act), shops are closed on Christmas Eve, Constitution Day,
        // and on New Year's Eve after 15:00.
        $tags = [Holiday::TAG_DAY_OFF_SOME, Holiday::TAG_SHOP_CLOSED, Holiday::TAG_BANK_CLOSED, Holiday::TAG_SUBJECT_EVE, Holiday::TAG_SUBJECT_RELIGION];
        $this->addHoliday($this->christmasEve($this->year, $this->timezone, $this->locale, self::TYPE_OBSERVED, $tags));
        $tags = [Holiday::TAG_DAY_OFF_SOME, Holiday::TAG_SHOP_CLOSED_PARTIAL, Holiday::TAG_BANK_CLOSED, Holiday::TAG_SUBJECT_EVE];
        $this->addHoliday($this->newYearsEve($this->year, $this->timezone, $this->locale, self::TYPE_OBSERVED, $tags));
        $tags = [Holiday::TAG_DAY_OFF_SOME, Holiday::TAG_SHOP_CLOSED, Holiday::TAG_BANK_CLOSED, Holiday::TAG_SUBJECT_COUNTRY];
        $this->addHoliday($this->constitutionDay($this->year, $this->timezone, $this->locale, self::TYPE_OBSERVED, $tags));

        // International Worker's Day is a whole or half day off by collective agreement in many industries.
        $tags = [Holiday::TAG_DAY_OFF_SOME, Holiday::TAG_DAY_OFF_PARTIAL, Holiday::TAG_SUBJECT_CAUSE];
        $this->addHoliday($this->internationalWorkersDay($this->year, $this->timezone, $this->locale, self::TYPE_OBSERVED, $tags));

        // Banks are closed the day after Ascension day.
        $tags = [Holiday::TAG_BANK_CLOSED, Holiday::TAG_DAY_AFTER];
        $this->addHoliday($this->dayAfterAscensionDay($this->year, $this->timezone, $this->locale, self::TYPE_OBSERVED, $tags));
    }

    /**
     * Great Prayer Day
     *
     * Store Bededag, translated literally as Great Prayer Day or more loosely as General Prayer Day, "All Prayers" Day,
     * Great Day of Prayers or Common Prayer Day, is a Danish holiday celebrated on the 4th Friday after Easter. It is a
     * collection of minor Christian holy days consolidated into one day. The day was introduced in the Church of
     * Denmark in 1686 by King Christian V as a consolidation of several minor (or local) Roman Catholic holidays which
     * the Church observed that had survived the Reformation.
     *
     * @link https://en.wikipedia.org/wiki/Store_Bededag
     *
     * @throws \Yasumi\Exception\InvalidDateException
     * @throws \InvalidArgumentException
     * @throws \Yasumi\Exception\UnknownLocaleException
     * @throws \Exception
     */
    public function calculateGreatPrayerDay($tags)
    {
        $easter = $this->calculateEaster($this->year, $this->timezone)->format('Y-m-d');

        if ($this->year >= 1686) {
            $this->addHoliday(new Holiday(
                'greatPrayerDay',
                ['da_DK' => 'Store Bededag'],
                new DateTime("fourth friday $easter", new DateTimeZone($this->timezone)),
                $this->locale,
                self::TYPE_OFFICIAL,
                $tags
            ));
        }
    }
}
