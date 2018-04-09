<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();

class JPetitionController extends JControllerLegacy
{
	public function __construct($config = array())
	{
        $this->input = JFactory::getApplication()->input;
        
        require_once JPATH_COMPONENT.'/assets/lib/functions.php';        
        
		//Load JQuery
		JPetitionHelper::loadJQuery();
		
        //Load javascripts
        JPetitionHelper::loadJavaScripts();
        
        //Load styles
        JPetitionHelper::loadStyleSheet();
        
        parent::__construct($config);
	}

	/**
	 * Method to display a view.
	 */
    public function display($cachable = false, $urlparams = false)
	{
		$vName = $this->input->getCmd('view', 'petitions');
		$this->input->set('view', $vName);
		$this->input->set('view-layout', JPetitionHelper::getViewLayout());

		$safeurlparams = array(
			'id' => 'INT',
			'cid' => 'ARRAY',
			'limit' => 'UINT',
			'limitstart' => 'UINT',
			'filter_order' => 'CMD',
			'filter_order_Dir' => 'CMD',
			'print' => 'BOOLEAN',
			'lang' => 'CMD',
			'Itemid' => 'INT'
        );

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}