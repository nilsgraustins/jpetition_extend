<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die;

class JPetitionControllerPetitions extends JControllerAdmin
{
	/**
	 * Method to publish a list of petitions.
	 */
	public function publish()
	{
        parent::publish();
        
        $this->setRedirect('index.php?option=com_jpetition&view=petitions');
	}
    
    /**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  JModel
	 */
    public function getModel($name = 'Admin', $prefix = 'JPetitionModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
