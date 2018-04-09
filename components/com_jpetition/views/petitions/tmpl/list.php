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
    <?php if (is_array($this->items) && count($this->items)) : ?>
        <form action="#" method="post" name="adminForm" id="adminForm">
            <div class="btn-wrapper input-append petition-search">
                <input type="text" name="filter_search" class="petition-search-input" value="<?php echo $this->filter_search; ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" id="filter_search">

                <button title="" class="btn hasTooltip btn-petition-search" type="submit" data-original-title="<?php echo JText::_('JSEARCH_FILTER'); ?>">
                    <span class="icon-search"></span>
                </button>
            </div>
            <div class="petition-sorting">
                <?php if ($this->showDaysLeft) : ?>
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_DAYS_LEFT'), 'daysleft', $this->filter_order_Dir, $this->filter_order); ?>
                <?php endif; ?>
                <?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_COUNT_SIGNING'), 'countSigning', $this->filter_order_Dir, $this->filter_order); ?>
            </div>
            <table cellpadding="0" cellspacing="0" class="table table-striped higher-table table-petition">
                <thead>
                    <tr>
                        <th class="th-title"><?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_TITLE'), 'p.title', $this->filter_order_Dir, $this->filter_order); ?></th>
                        <?php if ($this->showDaysLeft) : ?>
                            <th class="th-days-left"><?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_DAYS_LEFT'), 'daysleft', $this->filter_order_Dir, $this->filter_order); ?></th>
                        <?php endif; ?>
                        <th class="th-count-signing"><?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_COUNT_SIGNING'), 'countSigning', $this->filter_order_Dir, $this->filter_order); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->items as $key => $item) : ?>
                    <tr>
                        <td>
                            <div class="petition-show-mobile">
                                <?php echo JText::_('COM_JPETITION_TITLE'); ?>
                            </div>
                            <a href="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=view&id='.$item->id); ?>">
                                <?php echo $item->title; ?>
                            </a>
                        </td>
                        <?php if ($this->showDaysLeft) : ?>
                            <td class="td-days-left">
                                <div class="petition-show-mobile">
                                    <?php echo JText::_('COM_JPETITION_DAYS_LEFT'); ?>:
                                </div>
                                <?php echo $item->daysleft; ?> <?php echo pluralForm($item->daysleft, JText::_('COM_JPETITION_DAY'), JText::_('COM_JPETITION_DAYS'), JText::_('COM_JPETITION_S_DAYS')); ?>
                            </td>
                        <?php endif; ?>
                        <td class="td-count-signing">
                            <div class="petition-show-mobile">
                                <?php echo JText::_('COM_JPETITION_COUNT_SIGNING'); ?>:
                            </div>
                            <?php echo $item->countSigning; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
            <input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir; ?>" />
            <input type="hidden" name="task" value="" />
        </form>
    <?php else : ?>
        <div class="no-petitions"><?php echo JText::_('COM_JPETITION_PETITIONS_NOT_FOUND'); ?></div>
    <?php endif; ?>
	<?php echo JFactory::getApplication()->input->get('view-layout', null, "RAW"); ?>
</div>