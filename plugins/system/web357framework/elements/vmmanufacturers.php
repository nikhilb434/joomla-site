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

 
defined('JPATH_BASE') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;

class JFormFieldVmmanufacturers extends FormField
{
	var $type = 'vmmanufacturers';

    function getInput() 
    {
		if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		$model = VmModel::getModel('Manufacturer');
		$manufacturers = $model->getManufacturers(true, true, false);
		return HTMLHelper::_('select.genericlist', $manufacturers, $this->name, 'class="inputbox"  size="1" multiple="multiple"', 'virtuemart_manufacturer_id', 'mf_name', $this->value, $this->id);
	}
}