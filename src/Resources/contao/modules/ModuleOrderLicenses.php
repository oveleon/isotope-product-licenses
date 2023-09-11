<?php
/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

use Contao\BackendTemplate;
use Contao\Input;
use Contao\Module;
use Contao\System;
use Isotope\Model\ProductCollection\Order;

class ModuleOrderLicenses extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_orderLicenses';

    /**
     * ProductCollection
     * @var null
     */
    protected $objOrder = null;

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . ($GLOBALS['TL_LANG']['FMD']['orderLicenses'][0] ?? '') . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!Input::get('uid') || ($this->objOrder = Order::findOneBy('uniqid', Input::get('uid'))) === null) {
            return '';
        }

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile()
    {
        $objLicenses = LicenseItemModel::findByOrder($this->objOrder->id, ['order' => 'pid']);
        $arrProducts = null;

        if(null != $objLicenses)
        {
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

        $this->Template->empty = $GLOBALS['TL_LANG']['MSC']['msgNoLicenses'];
        $this->Template->products = $arrProducts;
    }
}
