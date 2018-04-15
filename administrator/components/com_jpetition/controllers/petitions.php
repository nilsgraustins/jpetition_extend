<?php

/**
 * 	@version      1.0.0 23.05.2016
 * 	@author       Daniel Rjadko
 * 	@package      JPetition
 * 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
 * 	@license      GNU/GPL
 */
defined('_JEXEC') or die;

class JPetitionControllerPetitions extends JControllerAdmin {

    /**
     * Method to publish a list of petitions.
     */
    public function publish() {
        parent::publish();

        $this->setRedirect('index.php?option=com_jpetition&view=petitions');
    }

    public static function modify() {
        $jinput = JFactory::getApplication()->input;
        $title = $jinput->get('Petitontitle', '', null);
        $text = $jinput->get('Petitontext', '', 'RAW');
        $state = $jinput->get('state', '1', 'INT');
        $needed_signs = $jinput->get('needed_signs', '1', 'INT');
        $id = $jinput->get('id', '0', 'INT');
        $petition = JTable::getInstance('Petition', 'Table');
        $petition->load($id);

        if ($petition->id) {
            $data = array(
                'table' => 'petition',
                'id' => $id,
                'state' => $state,
                'title' => $title,
                'text' => $text,
                'needed_signs' => $needed_signs,
            );
            //          JConfig::sh($petition,$data);//TablePetition Object
            $pet = new JPetitionModelBase;
            $pet->store($data);
        }
    }

    public function saveback() {
        self::modify();
        $this->setRedirect('index.php?option=com_jpetition&view=petitions');
    }

    public function save() {
        self::modify();
        $this->setRedirect('index.php?option=com_jpetition&view=petition&layout=view&id=' . $id);
    }

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  JModel
     */
    public function getModel($name = 'Admin', $prefix = 'JPetitionModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

}
