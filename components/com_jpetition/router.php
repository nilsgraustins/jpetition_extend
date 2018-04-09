<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 

class JPetitionRouter extends JComponentRouterBase
{
    private $menuItems = null;
    
    private $listPetitionItemsId = array();
    
    private $petitionCreate = null;
    
    public function __construct($app = null, $menu = null)
	{
        parent::__construct();

        $this->getMenuItems();
    }
    
    public function build(&$query)
	{
        $segments = array();

        $menu = JFactory::getApplication()->getMenu();
        $mainComponentMenuItem = $menu->getItems('link', 'index.php?option=com_jpetition&view=petitions', 1);

        if (isset($query['view']) && $query['view'] == 'petition' && isset($query['layout']) && $query['layout'] == 'view' && isset($query['id']) && $query['id']){
            $segments[] = $query['layout'];
            $segments[] = $query['id'];

            unset($query['view']);
            unset($query['layout']);
            unset($query['id']);

            if (isset($mainComponentMenuItem->id) && $mainComponentMenuItem->id){
                $query['Itemid'] = $mainComponentMenuItem->id;
            }
        }

        if (isset($query['view']) && $query['view'] == 'votes' && isset($query['id']) && $query['id']){
            $segments[] = $query['view'];
            $segments[] = $query['id'];

            unset($query['view']);
            unset($query['id']);

            if (isset($mainComponentMenuItem->id) && $mainComponentMenuItem->id){
                $query['Itemid'] = $mainComponentMenuItem->id;
            }
        }

        if (isset($query['view']) && $query['view'] == "petitions" && !isset($query['layout']) && array_key_exists('active', $this->listPetitionItemsId)){
            $query['Itemid'] = $this->listPetitionItemsId['active'];
            unset($query['view']);
        }
        
        if (isset($query['view']) && $query['view'] == "petitions" && isset($query['layout']) && $query['layout'] == 'inprocess' && array_key_exists('inprocess', $this->listPetitionItemsId)){
            $query['Itemid'] = $this->listPetitionItemsId['inprocess'];
            unset($query['view']);
            unset($query['layout']);
        }
        
        if (isset($query['view']) && $query['view'] == "petitions" && isset($query['layout']) && $query['layout'] == 'processed' && array_key_exists('processed', $this->listPetitionItemsId)){
            $query['Itemid'] = $this->listPetitionItemsId['processed'];
            unset($query['view']);
            unset($query['layout']);
        }
        
        if (isset($query['view']) && $query['view'] == "petitions" && isset($query['layout']) && $query['layout'] == 'all' && array_key_exists('all', $this->listPetitionItemsId)){
            $query['Itemid'] = $this->listPetitionItemsId['all'];
            unset($query['view']);
            unset($query['layout']);
        }
        
        if (isset($query['view']) && $query['view'] == "petition" && isset($query['layout']) && $query['layout'] == 'create' && $this->petitionCreate){
            $query['Itemid'] = $this->petitionCreate;
            unset($query['view']);
            unset($query['layout']);
        }

        return $segments;
    }
    
    public function parse(&$segments)
	{
        $vars = array();
    
        if (isset($segments[0]) && $segments[0] == 'view' && isset($segments[1]) && is_numeric($segments[1])){
            $vars['view'] = 'petition';
            $vars['layout'] = 'view';
            $vars['id'] = $segments[1];
        }

        if (isset($segments[0]) && $segments[0] == 'votes' && isset($segments[1]) && is_numeric($segments[1])){
            $vars['view'] = 'votes';
            $vars['id'] = $segments[1];
        }

        return $vars;
    }
    
    private function getMenuItems()
    {        
        if ($this->menuItems === null){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*')
                    ->from('#__menu')
                    ->where('type = "component"')
                    ->where('published = 1')
                    ->where('link LIKE "%option=com_jpetition%"')
                    ->where('client_id = 0')
                    ->where('(language = "*" OR language = "'.JFactory::getLanguage()->getTag().'")')
                    ->where('access IN ('.implode(',', JFactory::getUser()->getAuthorisedViewLevels()).')');
            
            $db->setQuery($query);

            $this->menuItems = $db->loadObjectList('id');

            foreach ($this->menuItems as $item){
                $url = str_replace('index.php?', '', $item->link);
                $url = str_replace('&amp;', '&', $url);

                parse_str($url, $item->query);
                $this->getMenuItemsId($item);
            }
        }
    }
    
    private function getMenuItemsId($item)
    {
        if (count($item->query) == 2 && $item->query['view'] == 'petitions'){
            $this->listPetitionItemsId['active'] = $item->id;
        }

        if (count($item->query) == 3 && $item->query['view'] == 'petitions' && $item->query['layout'] == 'inprocess'){
            $this->listPetitionItemsId['inprocess'] = $item->id;
        }

        if (count($item->query) == 3 && $item->query['view'] == 'petitions' && $item->query['layout'] == 'processed'){
            $this->listPetitionItemsId['processed'] = $item->id;
        }

        if (count($item->query) == 3 && $item->query['view'] == 'petitions' && $item->query['layout'] == 'all'){
            $this->listPetitionItemsId['all'] = $item->id;
        }

        if (count($item->query) == 3 && $item->query['view'] == 'petition' && $item->query['layout'] == 'create'){
            $this->petitionCreate = $item->id;
        }
    }
}

function jPetitionBuildRoute(&$query)
{
	$router = new JPetitionRouter;

	return $router->build($query);
}

function jPetitionParseRoute($segments)
{
	$router = new JPetitionRouter;

	return $router->parse($segments);
}