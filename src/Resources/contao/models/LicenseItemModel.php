<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\IsotopeProductLicenses;

/**
 * Reads and writes licence items
 *
 * @property integer $id
 * @property integer $pid
 * @property string  $licence
 * @property integer $member
 * @property string  $published
 *
 * @method static LicenseItemModel|null findById($id, array $opt=array())
 * @method static LicenseItemModel|null findByPk($id, array $opt=array())
 * @method static LicenseItemModel|null findOneBy($col, $val, array $opt=array())
 * @method static LicenseItemModel|null findOneByPid($val, array $opt=array())
 * @method static LicenseItemModel|null findOneByLicence($val, array $opt=array())
 * @method static LicenseItemModel|null findOneByMember($val, array $opt=array())
 * @method static LicenseItemModel|null findOneByPublished($val, array $opt=array())
 *
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findByPid($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findByTstamp($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findMultipleByIds($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findBy($col, $val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findByLicence($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findByMember($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findByPublished($val, array $opt=array())
 * @method static Collection|LicenseItemModel[]|LicenseItemModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByPid($val, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByLicence($val, array $opt=array())
 * @method static integer countByMember($val, array $opt=array())
 * @method static integer countByPublished($val, array $opt=array())
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class LicenseItemModel extends \Model
{
	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_license_item';

}
