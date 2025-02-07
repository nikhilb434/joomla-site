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

 
defined('_JEXEC') or die;

// Registers Web357 framework's namespace
JLoader::registerNamespace('Web357Framework', __DIR__ . '/Web357Framework/', false, false, 'psr4' );

JLoader::registerAlias('Functions', '\\Web357Framework\\Functions');
JLoader::registerAlias('VersionChecker', '\\Web357Framework\\VersionChecker');