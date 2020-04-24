<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

use Contao\CoreBundle\Monolog\ContaoContext;
use Psr\Log\LogLevel;

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

        $logger = \System::getContainer()->get('monolog.logger.contao');

        if($product !== null)
        {
            $arrValidLicenses = array_filter(\StringUtil::deserialize($product->listitems, true));
            $arrUsedLicenses  = array_filter(\StringUtil::deserialize($product->useditems, true));

            // ToDo: The number from when the warning is to be output should come from a configuration.
            // ToDo: Send a warning e-mail to the administrator
            if(count($arrValidLicenses) < 10 && !empty($arrValidLicenses))
            {
                $logger->log(LogLevel::WARNING, sprintf($GLOBALS['TL_LANG']['ERR']['lowNumberOfLicenses'], $product->product), array('contao' => new ContaoContext('getNextLicense', 'WARNING')));
            }
            elseif(empty($arrValidLicenses))
            {
                $logger->log(LogLevel::ERROR, sprintf($GLOBALS['TL_LANG']['ERR']['noMoreLicenses'], $product->product), array('contao' => new ContaoContext('getNextLicense', 'ERROR')));
                return '';
            }

            $strNewLicense = array_shift($arrValidLicenses);
            $arrUsedLicenses[] = $strNewLicense;

            $product->listitems = serialize($arrValidLicenses);
            $product->useditems = serialize($arrUsedLicenses);

            $product->save();

            return $strNewLicense;
        }

        $logger->log(LogLevel::ERROR, $GLOBALS['TL_LANG']['ERR']['noLicenseFound'], array('contao' => new ContaoContext('getNextLicense', 'ERROR')));
        return '';
    }
}
