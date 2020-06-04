<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\FrontendUser;
use Contao\System;
use Psr\Log\LogLevel;

class LicenseHandler
{
    /**
     * Generate and return the next valid license of a product
     *
     * @param int|object $varProductLicence
     *
     * @return string
     */
    public static function getNextLicense($varProductLicence)
    {
        $logger = \System::getContainer()->get('monolog.logger.contao');

        if(is_int($varProductLicence))
        {
            $varProductLicence = LicenseModel::findOneByProduct($varProductLicence);
        }

        $objLicence = LicenseItemModel::findBy(['pid=?', 'published=?'], [$varProductLicence->id, 0]);

        if($objLicence !== null)
        {
            $intCount = $objLicence->count();

            // Warning when licences are almost exhausted
            if($intCount < 10)
            {
                $logger->log(LogLevel::WARNING, sprintf($GLOBALS['TL_LANG']['ERR']['lowNumberOfLicenses'], $intCount, $varProductLicence->product), array('contao' => new ContaoContext('getNextLicense', 'WARNING')));
            }
            // Warning when licences are exhausted
            elseif($intCount === 0)
            {
                $logger->log(LogLevel::ERROR, sprintf($GLOBALS['TL_LANG']['ERR']['noMoreLicenses'], $varProductLicence->product), array('contao' => new ContaoContext('getNextLicense', 'ERROR')));
                return '';
            }

            $objUser = System::importStatic(FrontendUser::class, 'Member');

            if(FE_USER_LOGGED_IN)
            {
                $objLicence->member = $objUser->id;
            }

            $objLicence->published = 1;
            $objLicence->save();

            return $objLicence->licence;
        }

        $logger->log(LogLevel::ERROR, sprintf($GLOBALS['TL_LANG']['ERR']['noLicenseFound'], $varProductLicence->product), array('contao' => new ContaoContext('getNextLicense', 'ERROR')));

        return '';
    }
}
