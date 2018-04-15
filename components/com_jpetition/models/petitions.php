<?php
/**
* 	@version      1.0.4 09.12.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelPetitions extends JModelList
{
    const STATE_NOT_PUBLISHED = 0;
    
    /*
     *  State 1: Petition published
     */
	const STATE_PUBLISHED = 1;
    
    /*
     *  State 2: Petition with answer
     */
	const STATE_PROCESSED = 2;
	
    /**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $context = 'com_jpetition.petition';
	
    protected $status = 'active';
    
    protected $showStartedSigning = false;
    
    protected $showFinishedSigning = false;
    
    protected $showSigningInProcess = false;
    
	protected $showCollectedSignatures = false;
	
    protected $showNotPublished = false;
    
	protected $showPublished = false;
	
	protected $showProcessed = false;
	
	protected $showPublishedAndProcessed = false;
    
    protected $showAuthorWasNotNotifyAboutEndCollectSigns = false;
    
    protected $showAdminWasNotNotifyAboutEndCollectedSigns = false;
    
    private $componentParams = null;
	
    public function __construct($config = array())
	{
		if (empty($config['filter_fields'])){
			$config['filter_fields'] = array(
				'p.title',
                'p.id',
                'daysleft',
				'countSigning'
			);
		}
        
        $this->componentParams = JComponentHelper::getParams('com_jpetition');

		parent::__construct($config);
	}
    
    protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
        $app = JFactory::getApplication();
        
        $orderCol = $app->getUserStateFromRequest($this->context . '.' . $this->status . '.filter_order', 'filter_order', 'p.title', 'string');
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = 'p.title';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->getUserStateFromRequest($this->context . '.' . $this->status . '.filter_order_Dir', 'filter_order_Dir', 'ASC', 'cmd');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))){
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);
        
        $filterSearch = $app->getUserStateFromRequest($this->context . '.' . $this->status . '.filter_search', 'filter_search', '', 'string');
		$this->setState('filter.search', strip_tags(trim($filterSearch)));

        $limit = $app->getUserStateFromRequest($this->context . '.' . $this->status . '.limit', 'limit', JFactory::getConfig()->get('list_limit'), 'uint');
		$this->setState('list.limit', $limit);
        $this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
        $this->setState('list.links', 0);
    }
    
    protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->showStartedSigning;
		$id .= ':' . $this->showFinishedSigning;
		$id .= ':' . $this->showSigningInProcess;
		$id .= ':' . $this->showCollectedSignatures;
		$id .= ':' . $this->showNotPublished;
        $id .= ':' . $this->showPublished;
        $id .= ':' . $this->showProcessed;
        $id .= ':' . $this->showPublishedAndProcessed;

		return parent::getStoreId($id);
	}
    
    protected function getListQuery()
	{
		$durationInSeconds = convertDaysToSeconds($this->componentParams->get('days_count', 92));
		
        $db = $this->getDbo();
		$subQuery = $db->getQuery(true);
		$subQuery->select('COUNT(*)');
		$subQuery->from('#__jpetition_votes AS v');
		$subQuery->where('v.petition_id = p.id');
		
		$query = $db->getQuery(true);
        $query->select('*');
        $query->select($this->componentParams->get('days_count', 92) . '- TIMESTAMPDIFF(DAY, p.start_signing, NOW()) as daysleft');
        $query->select('(' . $subQuery . ') AS countSigning');
		
        $query->from('#__jpetition AS p');
        
        if ($this->showNotPublished){
            $query->where('p.state = ' . self::STATE_NOT_PUBLISHED);
        } else if ($this->showPublished){
			$query->where('p.state = ' . self::STATE_PUBLISHED);
		} else if ($this->showProcessed){
			$query->where('p.state = ' . self::STATE_PROCESSED);
		} else if ($this->showPublishedAndProcessed){
			$query->where('(p.state = ' . self::STATE_PUBLISHED . ' OR p.state = ' . self::STATE_PROCESSED . ')');
		}
        
        if ($this->showStartedSigning){
            $query->where('p.start_signing <= NOW()');
        }
        
        if ($this->showSigningInProcess){
            $query->where('TIMESTAMPDIFF(SECOND, p.start_signing, NOW()) < ' . $durationInSeconds);
        }
        
        if ($this->showFinishedSigning){
            $query->where('TIMESTAMPDIFF(SECOND, p.start_signing, NOW()) >= ' . $durationInSeconds);
        }

		if ($this->showCollectedSignatures){
			$query->having('countSigning >= needed_signs ' );//. $this->componentParams->get('needed_signs', 250)
		}
        
        if ($this->showAuthorWasNotNotifyAboutEndCollectSigns){
            $query->where('p.notify_end_collect_signs = 0');
        }
        
        if ($this->showAdminWasNotNotifyAboutEndCollectedSigns){
            $query->where('p.notify_admin_collected_signs = 0');
        }
        
        $filterSearch = $this->getState('filter.search', '');
        if ($filterSearch != ""){
            $query->where('(p.title LIKE "%'.$filterSearch.'%" OR p.text LIKE "%'.$filterSearch.'%")');
        }
        
        // Add the list ordering clause.
	$query->order($this->getState('list.ordering', 'p.title') . ' ' . $this->getState('list.direction', 'ASC'));
        
        return $query;
    }
    
    public function setStatus($status)
	{
        $this->status = $status;
    }
    
	public function setShowPublished($flag)
	{
		$this->showPublished = $flag;
	}
	
    public function setShowNotPublished($flag)
	{
        $this->showNotPublished = $flag;
    }
    
	public function setShowProcessed($flag)
	{
		$this->showProcessed = $flag;
	}
	
	public function setShowPublishedAndProcessed($flag)
	{
		$this->showPublishedAndProcessed = $flag;
	}
	
    public function setShowStartedSigning($flag)
	{
        $this->showStartedSigning = $flag;
    }
    
    public function setShowFinishedSigning($flag)
	{
        $this->showFinishedSigning = $flag;
    }
    
    public function setShowSigningInProcess($flag)
	{
        $this->showSigningInProcess = $flag;
    }
	
	public function setShowCollectedSignatures($flag)
	{
		$this->showCollectedSignatures = $flag;
	}
    
    public function setShowAuthorWasNotNotifyAboutEndCollectSigns($flag)
    {
        $this->showAuthorWasNotNotifyAboutEndCollectSigns = $flag;
    }
    
    public function setShowAdminWasNotNotifyAboutEndCollectedSigns($flag){
        $this->showAdminWasNotNotifyAboutEndCollectedSigns = $flag;
    }
    
    public function notifyAuthorAboutEndOfCollectionSignatures()
    {
        if ($this->componentParams->get('notification_author_end_collect_signs', 0)){
            $emailsModel = JModelLegacy::getInstance('Emails', 'JPetitionModel');
            
            $this->setShowPublished(true);
            $this->setShowFinishedSigning(true);
            $this->setShowAuthorWasNotNotifyAboutEndCollectSigns(true);

            $items = $this->getItems();
            
            foreach ($items as $petition){
                if ($emailsModel->notifyAuthorAboutEndOfCollectionSignatures($petition)){
                    $data = array(
                        'table'                     => 'petition',
                        'id'                        => $petition->id,
                        'notify_end_collect_signs'  => 1
                    );

                    if (!JModelLegacy::getInstance('Base', 'JPetitionModel')->store($data)){
                        // TODO log save notify author about end of collection signatures
                    }
                } else {
                    // TODO log mail error
                }                
            }
        }
    }
    
    public function notifyAdminAboutPetitionsWhatCollectedSignatures()
    {
        if ($this->componentParams->get('notification_admin_received_needed_count_signs', 0)){
            $emailsModel = JModelLegacy::getInstance('Emails', 'JPetitionModel');
            $this->setShowPublished(true);
            $this->setShowFinishedSigning(true);
            $this->setShowCollectedSignatures(true);
            $this->setShowAdminWasNotNotifyAboutEndCollectedSigns(true);

            $items = $this->getItems();

            foreach ($items as $petition){
                if ($emailsModel->notifyAdminAboutPetitionsWhatCollectedSignatures($petition)){
                    $data = array(
                        'table'                         => 'petition',
                        'id'                            => $petition->id,
                        'notify_admin_collected_signs'  => 1
                    );

                    if (!JModelLegacy::getInstance('Base', 'JPetitionModel')->store($data)){
                        // TODO log save notify author about end of collection signatures
                    }
                } else {
                    // TODO log mail error
                }                
            }
        }
    }
}