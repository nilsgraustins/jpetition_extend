<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
use Joomla\Registry\Registry;

class JPetitionViewPetitions extends JViewLegacy 
{
    
    const PETITIONS_STATE_NOT_PUBLISHED = 0;
    const PETITIONS_STATE_ACTIVE = 1;
    const PETITIONS_STATE_IN_PROCESS = 2;
    const PETITIONS_STATE_PROCESSED = 3;
    const PETITIONS_STATE_ALL = 4;
    
    public function display($tpl = null)
	{
        $application = JFactory::getApplication();
        $model = $this->getModel();
        $model->setStatus('admin');
        
        $this->loadSearchTools();
        
        $filterState = $application->getUserStateFromRequest('com_jpetition.petition.admin.filter_state', 'filter_state', 0, 'INT');
        
		switch ($filterState) {
            case self::PETITIONS_STATE_ACTIVE:
				$model->setShowPublished(true);
				$model->setShowStartedSigning(true);
				$model->setShowSigningInProcess(true);
                
                break;
            case self::PETITIONS_STATE_IN_PROCESS:
                $model->setShowPublished(true);
				$model->setShowFinishedSigning(true);
				$model->setShowCollectedSignatures(true);
            
                break;
            case self::PETITIONS_STATE_PROCESSED:
                $model->setShowProcessed(true);
				$model->setShowFinishedSigning(true);
				$model->setShowCollectedSignatures(true);
            
                break;
            case self::PETITIONS_STATE_ALL:
                
                break;
            default:
                $model->setShowNotPublished(true);
                
                break;
        }
        
        $this->filterState = $filterState;
        $this->getList($model);
        $this->addToolbar();
        
        parent::display($tpl);
    }
	
    /**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
        JToolbarHelper::preferences('com_jpetition');
        JToolbarHelper::title(JText::_('COM_JPETITION_PETITIONS_LIST'), 'checkmark');
        
        if ($this->filterState == self::PETITIONS_STATE_NOT_PUBLISHED){
            JToolbarHelper::publish('petitions.publish', 'JTOOLBAR_PUBLISH', true);
        }
            JToolbarHelper::addNew('petition.add', 'JTOOLBAR_NEW', false);

        JToolbarHelper::back('COM_JPETITION_CONTROL_PANEL', 'index.php?option=com_jpetition');
        JToolbarHelper::link('http://www.drjadko.org/donate', JText::_('COM_JPETITION_DONATE'), 'help');
    }
    
    private function loadSearchTools()
	{
        $filterOptions = $this->getFilterOptions();
        
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::_('script', 'jui/jquery.searchtools.min.js', false, true);
        JHtml::_('stylesheet', 'jui/jquery.searchtools.css', false, true);
        
        $script = "
				(function($){
					$(document).ready(function() {
						$('#adminForm').searchtools(
							" . $filterOptions->toString() . "
						);
					});
				})(jQuery);
			";

        JFactory::getDocument()->addScriptDeclaration($script);
    }
    
    private function getFilterOptions()
	{
        $filterOptions = new Registry(array(
            'filtersHidden' => true,
            'defaultLimit' => 20,
            'searchFieldSelector' => '#filter_search',
            'orderFieldSelector' => '#list_fullordering',
            'formSelector' => '#adminForm'
        ));
        
        return $filterOptions;
    }
    
    private function getList(JPetitionModelPetitions $model)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filter_order = $model->getState('list.ordering');
        $this->filter_order_Dir = $model->getState('list.direction');
        $this->filter_search = $model->getState('filter.search', '');

        $this->stateOptions = array(
            self::PETITIONS_STATE_NOT_PUBLISHED => JText::_('COM_JPETITION_NOT_PUBLISHED_ITEMS'),
            self::PETITIONS_STATE_ACTIVE => JText::_('COM_JPETITION_ACTIVE'),
            self::PETITIONS_STATE_IN_PROCESS => JText::_('COM_JPETITION_IN_PROCESS'),
            self::PETITIONS_STATE_PROCESSED => JText::_('COM_JPETITION_WITH_ANSWER'),
            self::PETITIONS_STATE_ALL => JText::_('COM_JPETITION_ALL_PETITIONS')
        );
        
        $this->setLayout('list');
    }
}