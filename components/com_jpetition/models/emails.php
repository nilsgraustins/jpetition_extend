<?php
/**
* 	@version      1.0.5 13.04.2017
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die(); 
 
class JPetitionModelEmails extends JPetitionModelBase
{
    private $componentParams = null;
    
    public function __construct($config = array())
	{
        $this->componentParams = JComponentHelper::getParams('com_jpetition');

		parent::__construct($config);
	}

    public function notifyAuthorAboutPetitionPublication($petition){
        if ($this->componentParams->get('notification_publication', 0) && $petition->created_by){
            $petitionAuthor = JFactory::getUser($petition->created_by);
            
            if (isset($petitionAuthor->email) && !empty($petitionAuthor->email)){
                $app = JFactory::getApplication();
                
                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');

                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_PUBLICATION'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_PUBLICATION'), $petition);

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($petitionAuthor->email);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(false);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    public function notifyAboutNewPetition($petition)
    {
        if ($this->componentParams->get('notification_new_petition', 0)){
            $emailForNotification = $this->componentParams->get('email_for_notification', '');
            
            if (!empty($emailForNotification)){
                $app = JFactory::getApplication();
                
                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');
 
                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_NEW_PETITION'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_NEW_PETITION'), $petition);
                $message .= "\n\n" . JUri::root() . 'administrator/index.php?option=com_jpetition&view=petition&layout=view&id=' . $petition->id;

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($emailForNotification);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(false);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    public function notifyAuthorAboutEndOfCollectionSignatures($petition)
    {
        if ($this->componentParams->get('notification_author_end_collect_signs', 0)){
            $petitionAuthor = JFactory::getUser($petition->created_by);
            
            if (isset($petitionAuthor->email) && !empty($petitionAuthor->email)){
                JFactory::getLanguage()->load('com_jpetition', JPATH_SITE);
                $app = JFactory::getApplication();
                
                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');
 
                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_AUTHOR_END_COLLECT_SIGNS'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_AUTHOR_END_COLLECT_SIGNS'), $petition);

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($petitionAuthor->email);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(false);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    public function notifyAdminAboutPetitionsWhatCollectedSignatures($petition)
    {
        if ($this->componentParams->get('notification_admin_received_needed_count_signs', 0)){
            $emailForNotification = $this->componentParams->get('email_for_notification', '');
            
            if (!empty($emailForNotification)){
                JFactory::getLanguage()->load('com_jpetition', JPATH_SITE);
                $app = JFactory::getApplication();
                
                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');
 
                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_ADMIN_RECEIVED_SIGNS'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_ADMIN_RECEIVED_SIGNS'), $petition);
                $message .= "\n\n" . JUri::root() . 'administrator/index.php?option=com_jpetition&view=petition&layout=view&id=' . $petition->id;

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($emailForNotification);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(false);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    public function notifyAuthorAboutNewAnswer($petition)
    {
        if ($this->componentParams->get('notification_author_answer', 0)){
            $petitionAuthor = JFactory::getUser($petition->created_by);
            
            if (isset($petitionAuthor->email) && !empty($petitionAuthor->email)){
                JFactory::getLanguage()->load('com_jpetition', JPATH_SITE);
                $app = JFactory::getApplication();
                
                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');
 
                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_AUTHOR_NEW_ANSWER'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_AUTHOR_NEW_ANSWER'), $petition);

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($petitionAuthor->email);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(true);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    public function notifySignatoriesAboutNewAnswer($petition)
    {
        if ($this->componentParams->get('notification_signatories_answer', 0)){
            $votesModel = JModelLegacy::getInstance('Votes', 'JPetitionModel');
            $votesModel->set('petitionId', $petition->id);
            $votes = $votesModel->getItems();

            if (is_array($votes) && count($votes)){
                $recipients = array();
                foreach ($votes as $vote){
                    $recipients[] = $vote->email;
                }

                JFactory::getLanguage()->load('com_jpetition', JPATH_SITE);
                $app = JFactory::getApplication();

                $fromEmail = $app->get('mailfrom');
                $fromName = $app->get('fromname');

                $subject = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_SUBJECT_SIGNATORIES_NEW_ANSWER'), $petition);
                $message = $this->replaceVariables(JText::_('COM_JPETITION_EMAIL_MESSAGE_SIGNATORIES_NEW_ANSWER'), $petition);

                $mailer = JFactory::getMailer();
                $mailer->setSender(array($fromEmail, $fromName));
                $mailer->addRecipient($recipients);
                $mailer->setSubject($subject);
                $mailer->setBody($message);
                $mailer->isHTML(true);
                return $mailer->Send();
            }
            
            return false;
        }
        
        return true;
    }
    
    private function replaceVariables($string, $petition)
    {
        $string = str_replace('{sitename}', JFactory::getApplication()->get('sitename'), $string);
        $string = str_replace('{siteurl}', JUri::getInstance()->getHost(), $string);
        
        $string = str_replace('{username}', JFactory::getUser($petition->created_by)->name, $string);
        $string = str_replace('{petitiontitle}', $petition->title, $string);
        $string = str_replace('{petitiontext}', $petition->text, $string);
        $string = str_replace('{petitionanswer}', $petition->answer, $string);

        $string = str_replace('{countsigns}', $petition->countSigning, $string);
        
        return $string;
    }
}