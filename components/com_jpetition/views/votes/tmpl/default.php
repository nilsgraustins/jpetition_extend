<?php
/**
* 	@version      1.0.0 23.05.2016
* 	@author       Daniel Rjadko
* 	@package      JPetition
* 	@copyright    Copyright (C) 2016 www.drjadko.org. All rights reserved.
* 	@license      GNU/GPL
*/

defined('_JEXEC') or die();
?>

<div class="jpetition">
    <div class="petition-vote">
        <h1><?php echo JText::_('COM_JPETITION_PETITION_VOTES'); ?>: <?php echo $this->item->title; ?></h1>
        <table class="table table-striped petition-votes">
            <thead>
                <tr>
                    <th><?php echo JText::_('COM_JPETITION_SIGNED'); ?></th>
                    <th><?php echo JText::_('COM_JPETITION_SIGNED_DATE'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $item) : ?>
                    <tr>
                        <td class="span8"><?php echo $item->author; ?></td>
                        <td class="span4"><?php echo getJoomlaDate($item->signed, JText::_('DATE_FORMAT_LC2')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php echo JFactory::getApplication()->input->get('view-layout', null, "RAW"); ?>
</div>