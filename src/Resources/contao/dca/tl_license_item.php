<?php

/*
 * This file is part of Oveleon Isotope Product License.
 *
 * (c) https://www.oveleon.de/
 */
$GLOBALS['TL_DCA']['tl_license_item'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
        'ptable'                      => 'tl_license',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'licence' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('licence','tstamp'),
            'panelLayout'             => 'limit',
            'headerFields'            => array('title', 'product'),
            'child_record_callback'   => array('tl_license_item', 'listLicence'),
            'disableGrouping'         => true
        ),
		'label' => array
		(
            'fields'                  => array('title', 'product'),
            'showColumns'             => true
		),
		'global_operations' => array
		(
			'all' => array
			(
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			),
            'toggle' => array
            (
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_license_item', 'toggleIcon'),
                'showInHeader'        => true
            )
		)
	),

	// Palettes
	'palettes' => array
	(
	    '__selector__'                => ['published'],
		'default'                     => '{licence_legend},licence;{publish_legend},published',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'published'                     => 'member',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
        'pid' => array
        (
            'foreignKey'              => 'tl_license.title',
            'sql'                     => "int(10) unsigned NOT NULL default 0",
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
		'licence' => array
		(
			'exclude'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'unique'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'member' => array(
            'exclude'                 => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.CONCAT(firstname," ",lastname)',
            'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default 0",
        ),
        'published' => array
        (
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true, 'submitOnChange' => true),
            'sql'                     => "char(1) NOT NULL default ''"
        )
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class tl_license_item extends Contao\Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Contao\BackendUser', 'User');
	}

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (Contao\Input::get('tid'))
        {
            $this->toggleVisibility(Contao\Input::get('tid'), (Contao\Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_license_item::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . (!$row['published'] ? '' : 1);

        if ($row['published'])
        {
            $icon = 'invisible.svg';
        }

        $objPage = Contao\PageModel::findById($row['pid']);

        if (!$this->User->isAllowed(Contao\BackendUser::CAN_EDIT_ARTICLES, $objPage->row()))
        {
            if (!$row['published'])
            {
                $icon = preg_replace('/\.svg$/i', '_.svg', $icon); // see #8126
            }

            return Contao\Image::getHtml($icon) . ' ';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . Contao\StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml($icon, $label, 'data-state="' . (!$row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    /**
     * List a single licence
     *
     * @param array $row
     *
     * @return string
     */
    public function listLicence($row)
    {
        $len = strlen($row['licence']);
        $rep = 0;
        $cut = 0;

        if($len > 4)
        {
            $rep = $len - 4;
            $cut = 4;
        }

        return '<div class="tl_content_left" style="letter-spacing: .1em;">' . substr_replace($row['licence'], str_repeat('▪', $rep), 0, -$cut) . '</div>';
    }

    /**
     * Disable/enable a user group
     *
     * @param integer              $intId
     * @param boolean              $blnVisible
     * @param Contao\DataContainer $dc
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function toggleVisibility($intId, $blnVisible, Contao\DataContainer $dc=null)
    {
        // Set the ID and action
        Contao\Input::setGet('id', $intId);
        Contao\Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_license_item']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_license_item']['config']['onload_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_license_item::published', 'alexf'))
        {
            throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish article ID "' . $intId . '".');
        }

        // Set the current record
        if ($dc)
        {
            $objRow = $this->Database->prepare("SELECT * FROM tl_license_item WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows)
            {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Contao\Versions('tl_license_item', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_license_item']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_license_item']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_license_item SET tstamp=$time, published='" . (!$blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = (!$blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_license_item']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_license_item']['config']['onsubmit_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}
