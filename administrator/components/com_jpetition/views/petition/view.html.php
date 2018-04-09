<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 

class JPetitionViewPetition extends JViewLegacy 
{
    
	const PETITION_VIEW_LAYOUT = "view";
    
    public function display($tpl = null)
	{
		$layout = $this->getLayout();
		
		switch ($layout) {				
			case self::PETITION_VIEW_LAYOUT:
                $this->view();
                
				break;
		}
        
        parent::display($tpl);
    }
	
	private function view()
	{
		$this->item = $this->get('Item');
		
		$this->addToolbarView();
	}
    /**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbarView()
	{
        $this->componentParams = JComponentHelper::getParams('com_jpetition');
        JToolbarHelper::preferences('com_jpetition');
        JToolbarHelper::title(JText::_('COM_JPETITION_PETITION'), 'checkmark');
		if ($this->item->state == 1 && $this->item->signingBeforeTime <= getJoomlaDate() && $this->item->countSigning >= $this->componentParams->get('needed_signs', 250)) {
			JToolbarHelper::save('petition.answer', 'COM_JPETITION_ADD_ANSWER');
		}
        
		JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_jpetition&view=petitions');
        JToolbarHelper::link('http://www.drjadko.org/donate', JText::_('COM_JPETITION_DONATE'), 'help');
    }
}