<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionViewPetitions extends JViewLegacy 
{
    const PETITION_LIST_ACTIVE_LAYOUT = "active";
    const PETITION_LIST_IN_PROCESS_LAYOUT = "inprocess";
	const PETITION_LIST_PROCESSED_LAYOUT = "processed";
	const PETITION_LIST_ALL_LAYOUT = "all";
    
    public function display($tpl = null)
	{
        $layout = $this->getLayout();
        $model = $this->getModel();

		switch ($layout) {
			case self::PETITION_LIST_IN_PROCESS_LAYOUT:
				$model->setStatus(self::PETITION_LIST_IN_PROCESS_LAYOUT);
				$model->setShowPublished(true);
				$model->setShowFinishedSigning(true);
				$model->setShowCollectedSignatures(true);
				$this->showDaysLeft = false;
				
				break;
			
			case self::PETITION_LIST_PROCESSED_LAYOUT:
				$model->setStatus(self::PETITION_LIST_PROCESSED_LAYOUT);
				$model->setShowProcessed(true);
				$model->setShowFinishedSigning(true);
				$model->setShowCollectedSignatures(true);
				$this->showDaysLeft = false;
				
				break;
			
			case self::PETITION_LIST_ALL_LAYOUT:
				$model->setStatus(self::PETITION_LIST_ALL_LAYOUT);
				$model->setShowPublishedAndProcessed(true);
				$this->showDaysLeft = false;
				
				break;
            
            case 'eucollectsigns':
                $model->notifyAuthorAboutEndOfCollectionSignatures();
                exit();
                break;
            
            case 'eacollectsigns':
                $model->notifyAdminAboutPetitionsWhatCollectedSignatures();
                exit();
                break;
				
			default:
				$model->setStatus(self::PETITION_LIST_ACTIVE_LAYOUT);
				$model->setShowPublished(true);
				$model->setShowStartedSigning(true);
				$model->setShowSigningInProcess(true);
				$this->showDaysLeft = true;
				
				break;
		}
        
        $this->getList($model);
        
        parent::display($tpl);
    }
	
    private function getList(JPetitionModelPetitions $model)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filter_order = $model->getState('list.ordering');
        $this->filter_order_Dir = $model->getState('list.direction');
        $this->filter_search = $model->getState('filter.search', '');
        
        $this->setLayout('list');
    }
}