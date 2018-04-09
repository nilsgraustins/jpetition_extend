<?php
/**
* 	@version      1.0.2 22.11.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();
?>

<div class="row-fluid">
    <div class="span8">
        <div class="jp-icon">
            <a href="index.php?option=com_jpetition&view=petitions"><i class="fa fa-list-alt fa-4x" aria-hidden="true"></i></a>
            <a href="index.php?option=com_jpetition&view=petitions"><?php echo JText::_("COM_JPETITION_PETITIONS"); ?></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="span4">
        <strong style="font-size: 16px;">JPetition <?php echo $this->version; ?></strong><br /><br />
        <?php echo JText::_('COM_JPETITION_DONATION_TEXT'); ?>
        
        <br /><br />
        <a href="http://www.drjadko.org/donate" class="btn btn-success btn-large" target="_blank"><?php echo JText::_('COM_JPETITION_DONATE');?></a>
        
        <br /><br /><br />
        <?php echo JText::_("COM_JPETITION_RATING_AND_REVIEW"); ?> <a href="https://extensions.joomla.org/extensions/extension/contacts-and-feedback/polls/jpetition/" target="_blank">Joomla! Extensions Directory</a>
    </div>
</div>