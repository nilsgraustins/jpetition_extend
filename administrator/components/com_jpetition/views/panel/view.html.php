<?php 
/**
* 	@version      1.0.2 22.11.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();

class JPetitionViewPanel extends JViewLegacy 
{
    
    public function display($tpl = null)
	{
        $document = JFactory::getDocument();
        
        $document->addStyleSheet(JUri::base() . 'components/com_jpetition/assets/css/font-awesome.min.css');
        $document->addStyleSheet(JUri::base() . 'components/com_jpetition/assets/css/styles.css');
        
        $xml = JFactory::getXML(JPATH_COMPONENT_ADMINISTRATOR .'/jpetition.xml');
        $this->version = (string)$xml->version;

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
        JToolbarHelper::title(JText::_('COM_JPETITION'), 'checkmark');
    }
}