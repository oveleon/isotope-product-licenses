<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

array_insert($GLOBALS['BE_MOD']['isotope']['iso_license'], 0, array
(
    'tables' => array('tl_license', 'tl_license_item')
));

// Register Model
$GLOBALS['TL_MODELS']['tl_license'] = 'Oveleon\IsotopeProductLicenses\LicenseModel';
$GLOBALS['TL_MODELS']['tl_license_item'] = 'Oveleon\IsotopeProductLicenses\LicenseItemModel';

// Front end modules
$GLOBALS['FE_MOD']['isotope']['orderLicenses'] = 'Oveleon\IsotopeProductLicenses\ModuleOrderLicenses';

// Register hooks
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('isotope_product_licenses.listener.insert_tags', 'onReplaceInsertTags');
