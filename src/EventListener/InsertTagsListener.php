<?php

declare(strict_types=1);

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Isotope\Model\ProductCollectionItem;

use Oveleon\IsotopeProductLicenses\LicenseModel;
use Oveleon\IsotopeProductLicenses\LicenseHandler;

/**
 * Handles insert tags for licenses.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class InsertTagsListener
{
    private const SUPPORTED_TAGS = [
        'license_product',
        'license_collection'
    ];

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * Constructor.
     *
     * @param ContaoFramework $framework
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Replaces license insert tags.
     *
     * @param string $tag
     *
     * @return string|false
     */
    public function onReplaceInsertTags(string $tag)
    {
        $elements = explode('::', $tag);
        $key = strtolower($elements[0]);

        if (\in_array($key, self::SUPPORTED_TAGS, true)) {
            return $this->replaceLicenseInsertTags($key, $elements[1]);
        }

        return false;
    }

    /**
     * Replaces a license insert tag.
     *
     * @param string $insertTag
     * @param string $intId
     *
     * @return string
     */
    private function replaceLicenseInsertTags($insertTag, $intId)
    {
        $arrProductIds = [];
        $arrProductLicenses = [];

        if($insertTag === 'license_collection')
        {
            $productsInCollection = ProductCollectionItem::findBy(['pid=?'], $intId, array());

            $objMember = Database::getInstance()->prepare("SELECT member FROM tl_iso_product_collection WHERE id=?")->limit(1)->execute($intId);
            $intMember = $objMember->member ?: null;

            while($productsInCollection->next())
            {
                for($i=0; $i<$productsInCollection->quantity; $i++)
                {
                    $arrProductIds[] = [$productsInCollection->product_id, $intMember, $intId];
                }
            }
        }else{
            $arrProductIds[] = [$intId, null, null];
        }

        foreach ($arrProductIds as $product)
        {
            list($productId, $memberId, $orderId) = $product;
            $objLicences = LicenseModel::findOneByProduct($productId);

            if($newLicence = LicenseHandler::getNextLicense($objLicences, $memberId, $orderId))
            {
                $arrProductLicenses[] = sprintf("%s: %s", $objLicences->title, $newLicence);
            }
        }

        return implode("\n", $arrProductLicenses);
    }
}
