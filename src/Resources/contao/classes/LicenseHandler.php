<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

class LicenseHandler
{
    /**
     * Generate and return the next valid license of a product
     *
     * @param int|object $product
     *
     * @return string
     */
    public static function getNextLicense($product)
    {
        if(is_int($product))
        {
            $product = LicenseModel::findOneByProduct($product);
        }

        if($product !== null)
        {
            $arrValidLicenses = array_filter(\StringUtil::deserialize($product->validLicenses));
            $arrUsedLicenses  = array_filter(\StringUtil::deserialize($product->usedLicenses));

            if(empty($arrValidLicenses))
            {
                return 'NO MORE LICENSES';
            }

            $strNewLicense = array_shift($arrValidLicenses);
            $arrUsedLicenses[] = $strNewLicense;

            $product->validLicenses = serialize($arrValidLicenses);
            $product->usedLicenses = serialize($arrUsedLicenses);

            $product->save();

            return $strNewLicense;
        }

        return 'LICENSE NOT FOUND';
    }
}
