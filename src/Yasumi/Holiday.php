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

namespace Yasumi;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;
use Yasumi\Exception\UnknownLocaleException;

/**
 * Class Holiday.
 */
class Holiday extends DateTime implements JsonSerializable
{
    /**
     * Type definition for Official (i.e. National/Federal) holidays.
     */
    const TYPE_OFFICIAL = 'official';

    /**
     * Type definition for Observance holidays.
     */
    const TYPE_OBSERVANCE = 'observance';

    /**
     * Type definition for seasonal holidays.
     */
    const TYPE_SEASON = 'season';

    /**
     * Type definition for Bank holidays.
     */
    const TYPE_BANK = 'bank';

    /**
     * Type definition for other type of holidays.
     */
    const TYPE_OTHER = 'other';

    /**
     * Tag for religious holidays.
     *
     * Examples: Easter Day, Christmas Eve.
     *
     * Some holidays may historically have been celebrated as religious holidays
     * or coincide with religious holidays, but this tag should only be used
     * if the day is still considered a religious holiday today.
     */
    const TAG_SUBJECT_RELIGION = 'religion';

    /**
     * Tag for holidays celebrating the country.
     *
     * Examples: Constitution Day, National Day.
     */
    const TAG_SUBJECT_COUNTRY = 'country';

    /**
     * Tag for holidays celebrating a specific person.
     *
     * Example: Martin Luther King Day.
     *
     * This tag may be used for historical religious persons, such as bishops.
     * Holidays celebrating deities and others mentioned in the sacret texts
     * (e.g. Jesus, Muhammad etc.) should use TAG_SUBJECT_RELIGION instead.
     */
    const TAG_SUBJECT_PERSON = 'person';

    /**
     * Tag for holidays celebrating or raising awareness about a cause.
     *
     * Example: Mother's Day, Labour Day.
     */
    const TAG_SUBJECT_CAUSE = 'cause';

    /**
     * Tag for holidays commemorating events related to war.
     *
     * Example: Armistice Day (celebrates end of WW1).
     */
    const TAG_SUBJECT_WAR = 'war';

    /**
     * Tag for holidays preceeding important official or observed holidays.
     *
     * Examples: Christmas Eve, New Year's Day's Eve.
     *
     * This tag is used for days that are not considered official holidays in
     * their own right, but are celebrated in the evening on the day preceeding
     * an important holiday.
     */
    const TAG_SUBJECT_EVE = 'eve';

    /**
     * Tag for holidays following important official or observed holidays.
     *
     * Examples: Day after Thanksgiving.
     *
     * This tag is used for days that are not considered official holidays in
     * their own right, but are celebrated on the day after an important holiday.
     *
     * This tag is not used for days that are part of celebrations spanning
     * several days such as Second Day of Christmas, Easter Monday or Carnival.
     */
    const TAG_SUBJECT_DAY_AFTER = 'day_after';

    /**
     * Tag for holidays where banks are closed.
     */
    const TAG_BANK_CLOSED = 'bank_closed';

    /**
     * Tag for holidays where most shops are closed.
     */
    const TAG_SHOP_CLOSED = 'shop_closed_some';

    /**
     * Tag for holidays where a significant number of shops are closed.
     */
    const TAG_SHOP_CLOSED_SOME = 'shop_closed_some';

    /**
     * Tag for holidays where a significant number of shops are closed are closed at least part of the day.
     */
    const TAG_SHOP_CLOSED_PARTIAL = 'shop_closed_partial';

    /**
     * Tag for holidays where the majority of employees have the day off.
     */
    const TAG_DAY_OFF = 'day_off';

    /**
     * Tag for holidays where a significant number of employees have the day off.
     *
     * This tag is only used for days where employees have the day off, because
     * the company/office is closed, not for days that are popular to take a
     * voluntary vacation day.
     */
    const TAG_DAY_OFF_SOME = 'day_off_some';

    /**
     * Tag for holidays where a significant number of employees have the day off at least part of they day.
     */
    const TAG_DAY_OFF_PARTIAL = 'day_off_some';

    /**
     * Tag for holidays that are celebrated in many federal states.
     *
     * This tag is used on holidays that are celebrated in at least half of all
     * federal states.
     */
    const TAG_OFFICIAL_STATE_MANY = 'state_many';

    /**
     * Tag for holidays that are only celebrated in few federal states.
     *
     * This tag is used on holidays that are celebrated in less than half of all
     * federal states.
     */
    const TAG_OFFICIAL_STATE_FEW = 'state_few';

    /**
     * Tag for holidays that are only celebrated in some regions.
     */
    const TAG_OFFICIAL_REGION = 'region';

    /**
     * The default locale. Used for translations of holiday names and other text strings.
     */
    const DEFAULT_LOCALE = 'en_US';

    /**
     * @var array list of all defined locales
     */
    private static $locales;

    /**
     * @var string short name (internal name) of this holiday
     */
    public $shortName;

    /**
     * @var array list of translations of this holiday
     */
    public $translations;

    /**
     * @var string identifies the type of holiday
     */
    private $type;

    /**
     * @var string Locale (i.e. language) in which the holiday information needs to be displayed in. (Default 'en_US')
     */
    private $displayLocale;

    /**
     * Creates a new Holiday.
     *
     * If a holiday date needs to be defined for a specific timezone, make sure that the date instance
     * (DateTimeInterface) has the correct timezone set. Otherwise the default system timezone is used.
     *
     * @param string             $shortName     The short name (internal name) of this holiday
     * @param array              $names         An array containing the name/description of this holiday in various
     *                                          languages. Overrides global translations
     * @param \DateTimeInterface $date          A DateTimeInterface instance representing the date of the holiday
     * @param string             $displayLocale Locale (i.e. language) in which the holiday information needs to be
     *                                          displayed in. (Default 'en_US')
     * @param string             $type          The type of holiday. Use the following constants: TYPE_OFFICIAL,
     *                                          TYPE_OBSERVANCE, TYPE_SEASON, TYPE_BANK or TYPE_OTHER. By default an
     *                                          official holiday is considered.
     *
     * @throws \Yasumi\Exception\InvalidDateException
     * @throws UnknownLocaleException
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $shortName,
        array $names,
        \DateTimeInterface $date,
        string $displayLocale = self::DEFAULT_LOCALE,
        string $type = self::TYPE_OFFICIAL
    ) {
        // Validate if short name is not empty
        if (empty($shortName)) {
            throw new InvalidArgumentException('Holiday name can not be blank.');
        }

        // Load internal locales variable
        if (null === self::$locales) {
            self::$locales = Yasumi::getAvailableLocales();
        }

        // Assert display locale input
        if (! \in_array($displayLocale, self::$locales, true)) {
            throw new UnknownLocaleException(\sprintf('Locale "%s" is not a valid locale.', $displayLocale));
        }

        // Set additional attributes
        $this->shortName     = $shortName;
        $this->translations  = $names;
        $this->displayLocale = $displayLocale;
        $this->type          = $type;

        // Construct instance
        parent::__construct($date->format('Y-m-d'), $date->getTimezone());
    }

    /**
     * Returns what type this holiday is.
     *
     * @return string the type of holiday (official, observance, season, bank or other).
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @return $this
     */
    public function jsonSerialize()
    {
        return $this;
    }

    /**
     * Returns the name of this holiday.
     *
     * The name of this holiday is returned translated in the given locale. If for the given locale no translation is
     * defined, the name in the default locale ('en_US') is returned. In case there is no translation at all, the short
     * internal name is returned.
     */
    public function getName(): string
    {
        if (isset($this->translations[$this->displayLocale])) {
            return $this->translations[$this->displayLocale];
        }

        if (isset($this->translations[self::DEFAULT_LOCALE])) {
            return $this->translations[self::DEFAULT_LOCALE];
        }

        return $this->shortName;
    }

    /**
     * Merges local translations (preferred) with global translations.
     *
     * @param TranslationsInterface $globalTranslations global translations
     */
    public function mergeGlobalTranslations(TranslationsInterface $globalTranslations)
    {
        $holidayGlobalTranslations = $globalTranslations->getTranslations($this->shortName);
        $this->translations        = \array_merge($holidayGlobalTranslations, $this->translations);
    }

    /**
     * Format the instance as a string using the set format.
     *
     * @return string this instance as a string using the set format.
     */
    public function __toString(): string
    {
        return (string)$this->format('Y-m-d');
    }
}
