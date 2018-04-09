<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelBase extends JModelItem 
{

    public function store($data) 
	{
        $row = JTable::getInstance($data['table'], 'Table');
        
        $tableKeyName = $row->getKeyName();
        
        if (!empty($tableKeyName) && isset($data[$tableKeyName]) && $data[$tableKeyName] > 0){
            $row->load($data[$tableKeyName]);
        }
        
        $date = getJoomlaDate();

        if (!$row->bind($data)) {
            $this->dataError = true;
            return $row;
        }

        $row->modified = $date;
        if (!$row->created) {
            $row->created = $date;
        }

        if (!$row->check()) {
            $this->dataError = true;
            return $row;
        }

        if (!$row->store()) {
            $this->dataError = true;
            return $row;
        }

        return $row;
    }
    
    public function set($property, $value = null) 
	{
        $previous = isset($this->$property) ? $this->$property : null;
        $this->$property = $value;

        return $previous;
    }
    
    public function get($property, $default = null) 
	{
        return isset($this->$property) ? $this->$property : $default;
    }
}