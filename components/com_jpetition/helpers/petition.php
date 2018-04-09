<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();

class JPetitionHelper
{
	public static function loadJavaScripts() 
    {
		JFactory::getDocument()->addScript(JURI::base().'components/com_jpetition/assets/js/script.js');
	}
    
    public static function loadStyleSheet() 
    {
		JFactory::getDocument()->addStylesheet(JURI::base().'components/com_jpetition/assets/css/style.css');
	}
	
	public static function loadJQuery()
    {
		JHtml::_('jquery.framework');
	}
    
    public static function checkLogin($return = null)
    {
        $user = JFactory::getUser();
        if (empty($return)){
            $return = 'index.php?option=com_users&view=profile';
        }
        
        if (!$user || $user->guest){            
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($return), false));
        }
    }
	
	public static function getViewLayout()
	{
        return getComponentViewLayout('drjadko');
    }
}