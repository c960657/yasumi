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

namespace Yasumi\Provider\Spain;

use Yasumi\Holiday;
use Yasumi\Provider\ChristianHolidays;
use Yasumi\Provider\Spain;

/**
 * Provider for all holidays in Navarre (Spain).
 *
 * Navarre, officially the Chartered Community of Navarre, is an autonomous community in northern Spain, bordering the
 * Basque Country, La Rioja, and Aragon in Spain and Aquitaine in France. The capital city is Pamplona (or Iruña in
 * Basque).
 *
 * @link http://en.wikipedia.org/wiki/Navarre
 */
class Navarre extends Spain
{
    use ChristianHolidays;

    /**
     * Initialize holidays for Navarre (Spain).
     */
    public function initialize()
    {
        parent::initialize();

        // Add custom Christian holidays
        $this->addHoliday($this->stJosephsDay($this->year, $this->timezone, $this->locale, Holiday::TYPE_OBSERVANCE));
        $this->addHoliday($this->maundyThursday($this->year, $this->timezone, $this->locale, Holiday::TYPE_OBSERVANCE));
        $this->addHoliday($this->easterMonday($this->year, $this->timezone, $this->locale, Holiday::TYPE_OBSERVANCE));
    }
}
