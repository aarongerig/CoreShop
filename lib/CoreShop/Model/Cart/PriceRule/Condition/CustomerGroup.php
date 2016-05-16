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
use CoreShop\Model\CustomerGroup as CustomerGroupModel;

class CustomerGroup extends AbstractCondition
{
    /**
     * @var int
     */
    public $customerGroup;

    /**
     * @var string
     */
    public $type = 'customerGroup';

    /**
     * @return int
     */
    public function getCustomerGroup()
    {
        if (!$this->customerGroup instanceof CustomerGroupModel) {
            $this->customerGroup = CustomerGroupModel::getById($this->customerGroup);
        }

        return $this->customerGroup;
    }

    /**
     * @param int $customerGroup
     */
    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;
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
        $customer = $cart->getUser();

        if (!$customer) {
            if ($throwException) {
                throw new \Exception('Customer in cart is emtpy!');
            } else {
                return false;
            }
        }

        $validCustomerGroupFound = false;

        if ($this->getCustomerGroup() instanceof CustomerGroupModel) {
            foreach ($customer->getGroups() as $customerGroup) {
                $customerGroup = CustomerGroupModel::getByField('name', $customerGroup);

                if ($customerGroup instanceof CustomerGroupModel) {
                    if ($this->getCustomerGroup()->getId() === $customerGroup->getId()) {
                        $validCustomerGroupFound = true;
                        break;
                    }
                }
            }
        }

        if (!$validCustomerGroupFound) {
            if ($throwException) {
                throw new \Exception('You cannot use this voucher.');
            }

            return false;
        }

        return true;
    }
}
