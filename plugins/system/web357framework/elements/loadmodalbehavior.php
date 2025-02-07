<?php
/* ======================================================
 # Web357 Framework for Joomla! - v1.9.5 (free version)
 # -------------------------------------------------------
 # For Joomla! CMS (v4.x)
 # Author: Web357 (Yiannis Christodoulou)
 # Copyright: (©) 2014-2024 Web357. All rights reserved.
 # License: GNU/GPLv3, https://www.gnu.org/licenses/gpl-3.0.html
 # Website: https://www.web357.com
 # Support: support@web357.com
 # Last modified: Wednesday 20 November 2024, 10:20:32 PM
 ========================================================= */

 
defined('JPATH_PLATFORM') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\FormField;

class JFormFieldloadmodalbehavior extends FormField 
{
	protected $type = 'loadmodalbehavior';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput() 
	{
		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			HTMLHelper::_('behavior.modal');
		}
	}
}