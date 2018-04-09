<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();

function getApplicationParams()
{
    return JFactory::getApplication()->getParams();
}

function getJoomlaDate($date = 'now', $format='Y-m-d H:i:s', $local = true)
{
	$config = JFactory::getConfig();
    $dateFormat = JFactory::getDate($date, $config->get('offset'));

    return $dateFormat->format($format, $local);
}

function convertDaysToSeconds($days)
{
	return $days * 24 * 60 * 60;
}

function pluralForm($n, $form1, $form2, $form3)
{
    $n = abs($n) % 100;
    $n1 = $n % 10;
    
    if ($n > 10 && $n < 20) {
        return $form3;
    }
    
    if ($n1 > 1 && $n1 < 5) {
        return $form2;
    }
    
    if ($n1 == 1) {
        return $form1;
    }
    
    return $form3;
}

function getComponentViewLayout($tmpl)
{
    return '<div style="text-align: center;"><a href="http://'.getComponentUrl($tmpl).'" target="_blank" style="font-size: 12px;">'.JText::_('COM_JPETITION_POWERED').' '.JText::_('COM_JPETITION_BY').' '.getComponentUrl($tmpl).'</a></div>';
}

function getComponentUrl($tmpl)
{
    return 'www.'.$tmpl.'.org';
}

function setMetaTitleKeywordsDescr($title, $keyword, $description)
{    
    $params = JFactory::getApplication()->getParams();
    
	$pageTitle = $params->get('page_title');
    if (!empty($pageTitle)){
        $title = $pageTitle;
    }
    
	$menuMetaDescription = $params->get('menu-meta_description');
    if (!empty($menuMetaDescription)){
        $description = $menuMetaDescription;
    }
    
	$menuMetaKeywords = $params->get('menu-meta_keywords');
    if (!empty($menuMetaKeywords)){
        $keyword = $menuMetaKeywords;
    }
    
    if (JFactory::getConfig()->get('sitename_pagetitles') == 2){        
        JFactory::getDocument()->setTitle($title . " - " . JFactory::getConfig()->get('sitename'));
    } else if (JFactory::getConfig()->get('sitename_pagetitles') == 1){        
        JFactory::getDocument()->setTitle(JFactory::getConfig()->get('sitename') . " - " . $title);
    } else {
        JFactory::getDocument()->setTitle($title);
    }
    
    JFactory::getDocument()->setMetadata('description', $description);
    JFactory::getDocument()->setMetadata('keywords', $keyword);  
}