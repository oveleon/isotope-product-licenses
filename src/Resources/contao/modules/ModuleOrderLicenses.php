<?php
/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

use Contao\Input;
use Contao\Module;
use Isotope\Model\ProductCollection\Order;

class ModuleOrderLicenses extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_orderLicenses';

    /**
     * Generate the content element
     */
    protected function compile()
    {
        if (!Input::get('uid') || ($order = Order::findOneBy('uniqid', Input::get('uid'))) === null) {
            return '';
        }

        $objLicenses = LicenseItemModel::findByOrder($order->id, ['order' => 'pid']);

        if(null != $objLicenses)
        {
            $arrProducts = [];
            $intCurrentProduct = 0;

            while($objLicenses->next())
            {
                if($objLicenses->pid != $intCurrentProduct)
                {
                    $intCurrentProduct = $objLicenses->pid;

                    $arrProducts[$intCurrentProduct] = [
                        'label'  => $objLicenses->getRelated('pid')->title,
                        'licenses' => null
                    ];
                }

                $arrProducts[$intCurrentProduct]['licenses'][] = $objLicenses->licence;
            }
        }

        $this->Template->products = $arrProducts;
    }
}
