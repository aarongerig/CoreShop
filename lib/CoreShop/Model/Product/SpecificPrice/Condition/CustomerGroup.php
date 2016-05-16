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
namespace CoreShop\Model\Product\SpecificPrice\Condition;

use CoreShop\Model;
use CoreShop\Model\CustomerGroup as CustomerGroupModel;
use CoreShop\Tool;

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
     * @return CustomerGroupModel
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
     * Check if Product is Valid for Condition.
     *
     * @param Model\Product               $product
     * @param Model\Product\SpecificPrice $specificPrice
     *
     * @return bool
     */
    public function checkCondition(Model\Product $product, Model\Product\SpecificPrice $specificPrice)
    {
        $cart = Tool::prepareCart();
        $customer = $cart->getUser();

        if ($customer) {
            if ($customer->isInGroup($this->getCustomerGroup())) {
                return true;
            }
        }

        return false;
    }
}
