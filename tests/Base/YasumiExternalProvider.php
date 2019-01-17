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

namespace Yasumi\tests\Base;

use IteratorAggregate;
use Yasumi\ProviderInterface;
use Yasumi\TranslationsInterface;

/**
 * Class YasumiExternalProvider.
 *
 * Class for testing the use of an external holiday provider class.
 */
class YasumiExternalProvider implements IteratorAggregate, ProviderInterface
{
    public function __construct($year, $locale = '', TranslationsInterface $globalTranslations = null)
    {
    }

    public function getYear(): int
    {
        return 2000;
    }

    public function isWorkingDay(\DateTimeInterface $date): bool
    {
        return false;
    }

    public function count(): int
    {
        return 0;
    }

    public function getIterator(): Traversable
    {
        return new ArrayObject();
    }
}
