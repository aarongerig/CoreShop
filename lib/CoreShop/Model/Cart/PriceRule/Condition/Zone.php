<?php
/**
 * CoreShop.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015 Dominik Pfaffenbauer (http://dominik.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */
namespace CoreShop\Model\Cart\PriceRule\Condition;

use CoreShop\Model\Cart\PriceRule;
use CoreShop\Model\Cart;
use CoreShop\Model\Zone as ZoneModel;
use CoreShop\Tool;

class Zone extends AbstractCondition
{
    /**
     * @var int
     */
    public $zone;

    /**
     * @var string
     */
    public $type = 'zone';

    /**
     * @return ZoneModel
     */
    public function getZone()
    {
        if (!$this->zone instanceof ZoneModel) {
            $this->zone = ZoneModel::getById($this->zone);
        }

        return $this->zone;
    }

    /**
     * @param int $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * Check if Cart is Valid for Condition.
     *
     * @param Cart       $cart
     * @param PriceRule  $priceRule
     * @param bool|false $throwException
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function checkCondition(Cart $cart, PriceRule $priceRule, $throwException = false)
    {
        if ($this->getZone()->getId() !== Tool::getCountry()->getZoneId()) {
            if ($throwException) {
                throw new \Exception('You cannot use this voucher in your zone of delivery');
            } else {
                return false;
            }
        }

        return true;
    }
}
