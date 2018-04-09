<?php
/**
* 	@version      1.0.4 09.12.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die;

class JPetitionController extends JControllerLegacy
{
	public function __construct($config = array())
	{
        $this->input = JFactory::getApplication()->input;
        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
        
        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
        JModelLegacy::getInstance('Petitions', 'JPetitionModel')->notifyAuthorAboutEndOfCollectionSignatures();
        JModelLegacy::getInstance('Petitions', 'JPetitionModel')->notifyAdminAboutPetitionsWhatCollectedSignatures();
        
        parent::__construct($config);
	}

	/**
	 * Method to display a view.
	 */
    public function display($cachable = false, $urlparams = false)
	{
        $vName = $this->input->getCmd('view', 'panel');
		$this->input->set('view', $vName);
        
		return parent::display();
	}
}