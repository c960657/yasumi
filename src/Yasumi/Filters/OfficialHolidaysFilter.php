<?php declare(strict_types=1);
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

namespace Yasumi\Filters;

use Countable;
use FilterIterator;
use Yasumi\Holiday;

/**
 * OfficialHolidaysFilter is a class for filtering all official holidays.
 *
 * OfficialHolidaysFilter is a class that returns all holidays that are considered official of any given
 * holiday provider.
 *
 * Example usage:
 * $holidays = Yasumi::create('Netherlands', 2015);
 * $official = new OfficialHolidaysFilter($holidays->getIterator());
 */
class OfficialHolidaysFilter extends FilterIterator implements Countable
{
    /**
     * Checks whether the current element of the iterator is an official holiday.
     *
     * @return bool
     */
    public function accept(): bool
    {
        return $this->getInnerIterator()->current()->getType() === Holiday::TYPE_OFFICIAL;
    }

    /**
     * @return int Returns the number of filtered holidays.
     */
    public function count(): int
    {
        return Holiday::count($this);
    }
}
