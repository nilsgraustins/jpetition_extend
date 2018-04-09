<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();

//load tables
JTable::addIncludePath(JPATH_COMPONENT . '/tables');

//load classes
JLoader::register('JPetitionHelper', JPATH_COMPONENT . '/helpers/petition.php');
JLoader::register('JPetitionModelBase', JPATH_COMPONENT . '/models/base.php');

$input = JFactory::getApplication()->input;
$controller = JControllerLegacy::getInstance('JPetition');
$controller->execute($input->get('task'));
$controller->redirect();