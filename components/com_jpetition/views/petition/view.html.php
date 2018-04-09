<?php 
/**
* 	@version      1.0.3 30.11.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionViewPetition extends JViewLegacy 
{
    
	const PETITION_CREATE_LAYOUT = "create";
    const PETITION_SAVE_LAYOUT = "save";
    const PETITION_VIEW_LAYOUT = "view";
	const PETITION_VOTE_LAYOUT = "vote";
    
    public $componentParams = null;
    
    public function __construct($config = array())
	{
        $this->componentParams = JComponentHelper::getParams('com_jpetition');

		parent::__construct($config);
	}
    
    public function display($tpl = null)
	{
        $layout = $this->getLayout();
        $model = $this->getModel();

		switch ($layout) {
			case self::PETITION_CREATE_LAYOUT:
                $this->create();
                
				break;

            case self::PETITION_SAVE_LAYOUT:
                $this->save($model);
                
				break;
            
            case self::PETITION_VIEW_LAYOUT:
                $this->view($model);
                
                break;
				
			case self::PETITION_VOTE_LAYOUT:
                $this->vote($model);
                
                break;
		}
        
        parent::display($tpl);
    }
	
	private function vote(JPetitionModelPetition $model)
	{
        $result = array(
            'response' => false
        );
        
		try {
			$model->vote();
            
            $result['response'] = true;
            $result['message'] = JText::_('COM_JPETITION_YOUR_SIGN_ACCEPTED');
		} catch (Exception $e) {
			$result['message'] = $e->getMessage();
		}
        
        echo json_encode($result); die();
	}
    
    private function view(JPetitionModelPetition $model)
	{
		$document = JFactory::getDocument();
        $model->setShowPublishedAndProcessed(true);
        $model->setShowStartedSigning(true);
        
		$this->user = JFactory::getUser();
        $this->item = $this->get('Item');
		$this->returnUrl = base64_encode('index.php?option=com_jpetition&view=petition&layout=view&id='.$this->item->id);
		$this->userSignPetition = $model->userSignPetition();

        setMetaTitleKeywordsDescr($this->item->title, $this->item->title, $this->item->title);
        JFactory::getApplication()->getPathway()->addItem($this->item->title);
    
		$document->addScriptDeclaration('petitionSigningBeforeTime = "' . $this->item->signingBeforeTime . '"');
		$document->addScriptDeclaration('petitionSigningFinishText = "' . JText::_('COM_JPETITION_SIGNING_FINISH') . '"');
    }
    
    private function save(JPetitionModelPetition $model)
	{
        JPetitionHelper::checkLogin('index.php?option=com_jpetition&view=petition&layout=create');

        // Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        $user = JFactory::getUser();
        $session = JFactory::getSession();
        $application = JFactory::getApplication();
        $data = array(
            'table'             => 'petition',
            'title'             => $application->input->post->getString('title', ''),
            'text'              => $application->input->post->getString('text', ''),
            'created_by'        => $user->id,
            'state'             => $model::STATE_NOT_PUBLISHED
        );
        
        $petition = $model->store($data);

        if (!$petition || $model->get('dataError')){
            $message = JText::_('COM_JPETITION_ERROR_ADD_PETITION');
            if ($petition->getError()){
                $message = (string)$petition->getError();
            }

            $session->set('petitionSavedData', (object)get_object_vars($petition));
            $application->enqueueMessage($message, 'error');
            $application->redirect(JRoute::_('index.php?option=com_jpetition&view=petition&layout=create'));
        }
        
        $emailsModel = JModelLegacy::getInstance('Emails', 'JPetitionModel');
        $emailsModel->notifyAboutNewPetition($petition);
        
        $application->enqueueMessage(JText::_('COM_JPETITION_SAVED_PUBLISH_AFTER_VERIFICATION'), 'message');
        $session->set('petitionSavedData', null);
        $application->redirect(JRoute::_('index.php?option=com_jpetition&view=petitions'));
    }
        
	private function create()
	{
        JPetitionHelper::checkLogin('index.php?option=com_jpetition&view=petition&layout=create');
        $object = JTable::getInstance('petition', 'Table');
        
        $session = JFactory::getSession();
        $this->item = $session->get('petitionSavedData', $object);
	}
}