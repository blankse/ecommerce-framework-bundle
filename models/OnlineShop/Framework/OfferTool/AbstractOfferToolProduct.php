<?php
/**
 * Pimcore
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2015 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace OnlineShop\Framework\OfferTool;

/**
 * Abstract base class for pimcore objects who should be used as custom products in the offer tool
 */
class AbstractOfferToolProduct extends \Pimcore\Model\Object\Concrete implements \OnlineShop\Framework\Model\ICheckoutable {

// =============================================
//     ICheckoutable Methods
//  =============================================

    /**
     * should be overwritten in mapped sub classes of product classes
     *
     * @throws \OnlineShop\Framework\Exception\UnsupportedException
     * @return string
     */
    public function getOSName() {
        throw new \OnlineShop\Framework\Exception\UnsupportedException("getOSName is not supported for " . get_class($this));
    }

    /**
     * should be overwritten in mapped sub classes of product classes
     *
     * @throws \OnlineShop\Framework\Exception\UnsupportedException
     * @return string
     */
    public function getOSProductNumber() {
        throw new \OnlineShop\Framework\Exception\UnsupportedException("getOSProductNumber is not supported for " . get_class($this));
    }


    /**
     * defines the name of the availability system for this product.
     * for offline tool there are no availability systems implemented
     *
     * @throws \OnlineShop\Framework\Exception\UnsupportedException
     * @return string
     */
    public function getAvailabilitySystemName() {
        return "none";
    }


    /**
     * checks if product is bookable
     * returns always true in default implementation
     *
     * @return bool
     */
    public function getOSIsBookable($quantityScale = 1) {
        return true;
    }


    /**
     * defines the name of the price system for this product.
     * there should either be a attribute in pro product object or
     * it should be overwritten in mapped sub classes of product classes
     *
     * @return string
     */
    public function getPriceSystemName()
    {
        return "defaultOfferToolPriceSystem";
    }

    /**
     * returns instance of price system implementation based on result of getPriceSystemName()
     *
     * @return \OnlineShop\Framework\PriceSystem\IPriceSystem
     */
    public function getPriceSystemImplementation() {
        return \OnlineShop\Framework\Factory::getInstance()->getPriceSystem($this->getPriceSystemName());
    }

    /**
     * returns instance of availability system implementation based on result of getAvailabilitySystemName()
     *
     * @return \OnlineShop\Framework\AvailabilitySystem\IAvailabilitySystem
     */
    public function getAvailabilitySystemImplementation() {
        return \OnlineShop\Framework\Factory::getInstance()->getAvailabilitySystem($this->getAvailabilitySystemName());
    }

    /**
     * returns price for given quantity scale
     *
     * @param int $quantityScale
     * @return \OnlineShop\Framework\PriceSystem\IPrice
     */
    public function getOSPrice($quantityScale = 1) {
        return $this->getOSPriceInfo($quantityScale)->getPrice();

    }

    /**
     * returns price info for given quantity scale.
     * price info might contain price and additional information for prices like discounts, ...
     *
     * @param int $quantityScale
     * @return \OnlineShop\Framework\PriceSystem\AbstractPriceInfo
     */
    public function getOSPriceInfo($quantityScale = 1) {
        return $this->getPriceSystemImplementation()->getPriceInfo($this,$quantityScale);
    }

    /**
     * returns availability info based on given quantity
     *
     * @param int $quantity
     * @return \OnlineShop\Framework\AvailabilitySystem\IAvailability
     */
    public function getOSAvailabilityInfo($quantity = null) {
        return $this->getAvailabilitySystemImplementation()->getAvailabilityInfo($this, $quantity);

    }

    /**
     * @static
     * @param int $id
     * @return null|\Pimcore\Model\Object\AbstractObject
     */
    public static function getById($id) {
        $object = \Pimcore\Model\Object\AbstractObject::getById($id);

        if ($object instanceof AbstractOfferToolProduct) {
            return $object;
        }
        return null;
    }


    /**
     * @throws \OnlineShop\Framework\Exception\UnsupportedException
     * @return string
     */
    public function getProductGroup() {
        throw new \OnlineShop\Framework\Exception\UnsupportedException("getProductGroup is not implemented for " . get_class($this));
    }



}
