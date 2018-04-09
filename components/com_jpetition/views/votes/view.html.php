<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionViewVotes extends JViewLegacy 
{
    
    public function display($tpl = null)
	{
        $model = $this->getModel();
        $model->setShowPublishedAndProcessed(true);
        $model->setShowStartedSigning(true);
        
        $this->items = $this->get('Items');
        
        $petitionModel = JModelLegacy::getInstance('Petition', 'JPetitionModel');
        $this->item = $petitionModel->getItem();
        
        parent::display($tpl);
    }
}