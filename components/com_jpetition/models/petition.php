<?php
/**
* 	@version      1.0.3 30.11.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelPetition extends JPetitionModelBase
{

    const STATE_NOT_PUBLISHED = 0;
	const STATE_PUBLISHED = 1;
	const STATE_PROCESSED = 2;
	
    /**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $context = 'com_jpetition.petition';
    
    protected $showStartedSigning = false;
    
    protected $showPublished = false;
	
    protected $showPublishedAndProcessed = false;
    
    private $componentParams = null;
    
    public function __construct($config = array())
	{
        $this->componentParams = JComponentHelper::getParams('com_jpetition');

		parent::__construct($config);
	}
    
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
        $app = JFactory::getApplication();
        
        // Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('petition.id', $pk);
    }
    
    /**
	 * Method to get petition data.
	 *
	 * @param   integer  $pk  The id of the petition.
	 *
	 * @return  mixed  petition item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$user = JFactory::getUser();

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('petition.id');

		if ($this->_item === null){
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])){
			try {
				$db = $this->getDbo();
                
                $subQuery = $db->getQuery(true);
                $subQuery->select('COUNT(*)');
                $subQuery->from('#__jpetition_votes AS v');
                $subQuery->where('v.petition_id = p.id');

				$query = $db->getQuery(true);
				$query->select('p.*');
                $query->select($this->componentParams->get('days_count', 92) . '- TIMESTAMPDIFF(DAY, p.start_signing, NOW()) as daysleft');
                $query->select('(' . $subQuery . ') AS countSigning');
                
				$query->from('#__jpetition AS p');

				// Join on user table.
				$query->select('u.name AS author')
					->join('LEFT', '#__users AS u on u.id = p.created_by');
       
                $query->where('p.id = '.$pk);
                
                if ($this->showPublished){
                    $query->where('p.state = ' . self::STATE_PUBLISHED);
                } else if ($this->showPublishedAndProcessed){
                    $query->where('(p.state = ' . self::STATE_PUBLISHED . ' OR p.state = ' . self::STATE_PROCESSED . ')');
                }
				
                if ($this->showStartedSigning){
                    $query->where('p.start_signing <= NOW()');
                }
                
                $db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data)) {
					return JFactory::getApplication()->enqueueMessages(JText::_('COM_JPETITION_PETITION_NOT_FOUND'), 'error');
				}

                $durationInSeconds = convertDaysToSeconds($this->componentParams->get('days_count', 92));
                $data->signingBeforeTime = date('Y-m-d H:i:s', strtotime($data->start_signing) + $durationInSeconds);
                
				$this->_item[$pk] = $data;
			} catch (Exception $e) {
				if ($e->getCode() == 404){
					JFactory::getApplication()->enqueueMessages($e->getMessage(), 'error');
				} else {
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}
    
    public function setShowPublished($flag)
    {
		$this->showPublished = $flag;
	}
    
    public function setShowPublishedAndProcessed($flag)
    {
		$this->showPublishedAndProcessed = $flag;
	}
    
    public function setShowStartedSigning($flag)
    {
        $this->showStartedSigning = $flag;
    }
	
	public function vote($petitionId = null)
    {
		$user = JFactory::getUser();
        if (!$user || $user->guest){
            throw new Exception(JText::_('COM_JPETITION_ERROR_LOGIN'));
        }
        
		$petitionId = (!empty($petitionId)) ? (int) $petitionId : (int) $this->getState('petition.id');
        if (!$petitionId){
            throw new Exception(JText::_('COM_JPETITION_PETITION_NOT_FOUND'));
        }
        
        if ($this->userSignPetition($petitionId)){
            throw new Exception(JText::_('COM_JPETITION_WAS_SIGNED'));
        }
        
        // Create and populate an object.
        $vote = new stdClass();
        $vote->user_id = $user->id;
        $vote->petition_id = $petitionId;
        $vote->signed = getJoomlaDate();

        if (!JFactory::getDbo()->insertObject('#__jpetition_votes', $vote)){
            throw new Exception(JText::_('COM_JPETITION_ERROR_SIGN_PETITION'));
        }
	}
	
	public function userSignPetition($petitionId = null)
    {
		$user = JFactory::getUser();
		$petitionId = (!empty($petitionId)) ? $petitionId : (int) $this->getState('petition.id');
		
		if ($user && !$user->guest && $user->id){
			try {
				$db = $this->getDbo();
				
				$query = $db->getQuery(true);
				$query->select('COUNT(*)');
				$query->from('#__jpetition_votes AS v');
				
				// Join on petition table.
				$query->join('LEFT', '#__jpetition AS p ON p.id = v.petition_id');
				
				$query->where('p.id = ' . $petitionId);
				$query->where('p.state != ' . self::STATE_NOT_PUBLISHED);
				$query->where('v.user_id = ' . $user->id);
				
				$db->setQuery($query);
				return (int)$db->loadResult();
			} catch (Exception $e) {
                if (JFactory::getApplication()->input->get->getInt('xhr', 1)){
                    echo json_encode(array('response' => false, 'message' => $e->getMessage())); die();
                } else {
					JFactory::getApplication()->enqueueMessages($e->getMessage(), 'error');
                }
			}
		}
		
		return false;
	}
}