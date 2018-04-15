<?php
/**
* 	@version      1.0.4 09.12.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelAdmin extends JPetitionModelBase
{
    /*
     *  State 1: Petition published
     */
    const STATE_PUBLISHED = 1;
    
    /*
     *  State 2: Petition with answer
     */
	const STATE_PROCESSED = 2;

    private $componentParams = null;
    
    public function __construct($config = array())
	{
        $this->componentParams = JComponentHelper::getParams('com_jpetition');
		parent::__construct($config);
	}
        
    public function publish($cid, $value)
	{
       // JConfig::sh($this); die;//JPetitionModelAdmi
        foreach ($cid as $petitionId){
            $petition = JTable::getInstance('Petition', 'Table');
            $petition->load($petitionId);

            if ($petition->id && $petition->state != $value){
                $data = array(
                    'table'             => 'petition',
                    'id'                => $petitionId,
                    'state'             => $value
                );

                if ($value == self::STATE_PUBLISHED){
                    $data['start_signing'] = getJoomlaDate();
                }

                if (!$this->store($data)){
                    throw new Exception(JText::_('COM_JPETITION_ERROR_PUBLISH'));
                } else {
                    if ($value == self::STATE_PUBLISHED){
                        // notify author about publication of the petition
                        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
                        $emailsModel = JModelLegacy::getInstance('Emails', 'JPetitionModel');
                        $emailsModel->notifyAuthorAboutPetitionPublication($petition);
                    }
                }
            }
        }
    }
	
	public function answer(&$item)
	{
		$application = JFactory::getApplication();
		
		if ($item->state == self::STATE_PUBLISHED && $item->signingBeforeTime <= getJoomlaDate() && $item->countSigning >= $this->componentParams->get('needed_signs', 250)) {
			$answer = $application->input->post->get('answer', '', 'RAW');
			
			if (!empty($answer)){
				$data = array(
                    'table'             => 'petition',
                    'id'                => $item->id,
                    'answer'            => $answer,
                    'state'     	=> self::STATE_PROCESSED
                );
                
                $object = $this->store($data);
                if (!$object){
                    throw new Exception(JText::_('COM_JPETITION_ERROR_SAVE_ANSWER'));
                }
                $item->answer = $object->answer;
			} else {
				throw new Exception(JText::_('COM_JPETITION_ENTER_ANSWER'));
			}
		} else if ($item->state == 2) {
			throw new Exception(JText::_('COM_JPETITION_ANSWER_WAS_ADD'));
		} else {
			throw new Exception(JText::_('COM_JPETITION_ERROR_REQUEST'));
		}
	}
}