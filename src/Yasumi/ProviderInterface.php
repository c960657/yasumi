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

namespace Yasumi;

/**
 * Interface ProviderInterface - Holiday provider interface.
 *
 * This interface class defines the standard functions that any country provider needs to define.
 *
 * @see     AbstractProvider
 */
interface ProviderInterface
// These two interfaces will be added in Yasumi 3.0.
// extends \Countable, \Traversable
{
    // This method will be added to the interface in Yasumi 3.0.
    // public function __construct($year, $locale = 'en_US', TranslationsInterface $globalTranslations = null);

    // This method will be added to the interface in Yasumi 3.0.
    // public function getYear(): int;

    // This method will be added to the interface in Yasumi 3.0.
    // public function isWorkingDay(\DateTimeInterface $date): bool;
}
