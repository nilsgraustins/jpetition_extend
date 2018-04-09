<?php
/**
* 	@version      1.0.5 13.04.2017
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelVotes extends JModelList
{
	const STATE_PUBLISHED = 1;
	const STATE_PROCESSED = 2;
    
    protected $showStartedSigning = false;
    
	protected $showPublishedAndProcessed = false;
    
    protected $petitionId = null;
	
    public function __construct($config = array())
	{
		parent::__construct($config);
	}
    
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
        $app = JFactory::getApplication();
        
        // Load state from the request.
		$pk = $this->petitionId ? $this->petitionId : $app->input->getInt('id');
		$this->setState('petition.id', $pk);
    }
    
    protected function getListQuery()
	{
        $petitionId = (int) $this->getState('petition.id');
        if (!$petitionId) {
			return JFactory::getApplication()->enqueueMessages(JText::_('COM_JPETITION_PETITION_NOT_FOUND'), 'error');
        }

        $db = $this->getDbo();
		$query = $db->getQuery(true);
			
        $query->select('v.*');
        $query->from('#__jpetition_votes AS v');
		
        // Join on petition table.
        $query->join('LEFT', '#__jpetition AS p ON p.id = v.petition_id');
        
        // Join on user table.
        $query->select('u.name AS author, u.email')
            ->join('LEFT', '#__users AS u ON u.id = v.user_id');
        
        $query->where('p.id = ' . $petitionId);
        
        if ($this->showPublishedAndProcessed){
			$query->where('(p.state = ' . self::STATE_PUBLISHED . ' OR p.state = ' . self::STATE_PROCESSED . ')');
		}
        
        if ($this->showStartedSigning){
            $query->where('p.start_signing <= NOW()');
        }

        // Add the list ordering clause.
		$query->order('v.signed ASC');

        return $query;
    }
	
	public function setShowPublishedAndProcessed($flag)
	{
		$this->showPublishedAndProcessed = $flag;
	}
	
    public function setShowStartedSigning($flag)
	{
        $this->showStartedSigning = $flag;
    }
    
    protected function getStoreId($id = '')
	{
        // Compile the store id.
		$id .= ':' . $this->getState('petition.id');
		$id .= ':' . $this->showStartedSigning;
		$id .= ':' . $this->showPublishedAndProcessed;

        return parent::getStoreId($id);
	}
}