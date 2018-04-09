<?php
/**
* 	@version      1.0.5 13.04.2017
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die;

class JPetitionControllerPetition extends JControllerAdmin
{
	/**
	 * Method to answer to petition.
	 */
	public function answer()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$application = JFactory::getApplication();
		
		JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
		$model = JModelLegacy::getInstance('Petition', 'JPetitionModel');

		$item = $model->getItem();

		try {
			$modelAdmin = $this->getModel();
			$modelAdmin->answer($item);
			
            $emailsModel = JModelLegacy::getInstance('Emails', 'JPetitionModel');
            $emailsModel->notifyAuthorAboutNewAnswer($item);
            $emailsModel->notifySignatoriesAboutNewAnswer($item);
            
			$application->enqueueMessage(JText::_('COM_JPETITION_ANSWER_SAVED'));
			$this->setRedirect('index.php?option=com_jpetition&view=petitions');
		} catch (Exception $ex) {
			$application->enqueueMessage($ex->getMessage(), 'error');
			$this->setRedirect('index.php?option=com_jpetition&view=petition&layout=view&id=' . $application->input->getInt('id', 0));
		}        
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
