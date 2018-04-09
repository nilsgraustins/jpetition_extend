<?php 
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 

class TablePetition extends JTable 
{
    
    protected $requiredFields = array(
        'title',
        'text'
    );
    
    /**
    * Constructor
    *
    * @param object Database connector object
    */
    public function __construct(&$db) 
	{
        parent::__construct('#__jpetition', 'id', $db);
    }
    
    public function check() 
	{
        parent::check();
        
        if (!$this->checkForEmpty()){
            return false;
        }

        return true;
    }
    
    private function checkForEmpty()
	{
        foreach ($this->requiredFields as $fieldName){
            if (!isset($this->$fieldName) || $this->$fieldName === ""){
                $this->setError(JText::_('COM_JPETITION_ERROR_REQUIRED'));
                return false;
            }
        }
        
        return true;
    } 
}