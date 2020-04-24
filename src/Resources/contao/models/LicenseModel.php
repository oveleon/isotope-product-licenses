<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

/**
 * Reads and writes members
 *
 * @property integer $id
 * @property string  $title
 * @property string  $product
 * @property string  $listitems
 * @property string  $useditems
 *
 * @method static LicenseModel|null findById($id, array $opt=array())
 * @method static LicenseModel|null findByPk($id, array $opt=array())
 * @method static LicenseModel|null findOneBy($col, $val, array $opt=array())
 * @method static LicenseModel|null findByTitle($val, array $opt=array())
 * @method static LicenseModel|null findOneByProduct($val, array $opt=array())
 *
 * @method static Collection|LicenseModel[]|LicenseModel|null findMultipleByIds($val, array $opt=array())
 * @method static Collection|LicenseModel[]|LicenseModel|null findBy($col, $val, array $opt=array())
 * @method static Collection|LicenseModel[]|LicenseModel|null findAll(array $opt=array())
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class LicenseModel extends \Model
{
	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_license';

}
