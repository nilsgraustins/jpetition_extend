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

<form action="<?php echo JRoute::_('index.php?option=com_jpetition&view=petitions'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <div class="js-stools clearfix">
            <div class="clearfix">
                <div class="js-stools-container-bar">
                    <div class="btn-wrapper input-append">
                        <input id="filter_search" type="text" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->filter_search; ?>" name="filter_search" />

                        <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
                            <span class="icon-search"></span>
                        </button>
                    </div>

                    <div class="btn-wrapper hidden-phone">
                        <button type="button" class="btn hasTooltip js-stools-btn-filter" title="<?php echo JHtml::tooltipText('JSEARCH_TOOLS_DESC'); ?>">
                            <?php echo JText::_('JSEARCH_TOOLS');?> <span class="caret"></span>
                        </button>
                    </div>

                    <div class="btn-wrapper">
                        <button type="button" class="btn hasTooltip js-stools-btn-clear" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>">
                            <?php echo JText::_('JSEARCH_FILTER_CLEAR');?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="js-stools-container-filters hidden-phone clearfix">
                <div class="js-stools-field-filter">
                    <?php echo JHTML::_('select.genericlist', $this->stateOptions, 'filter_state', 'id="filter_state" class="chzn-done" onchange="this.form.submit();"', 'value', 'text', $this->filterState); ?>    
                </div>
            </div>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="1%" class="center">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_TITLE'), 'p.title', $this->filter_order_Dir, $this->filter_order); ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_NEEDED'), 'p.needed_signs', $this->filter_order_Dir, $this->filter_order); ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', JText::_('COM_JPETITION_STATE'), 'p.state', $this->filter_order_Dir, $this->filter_order); ?>
                    </th>                    
                    <th width="1%" class="nowrap hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'p.id', $this->filter_order_Dir, $this->filter_order); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                //JConfig::sh(get_included_files());
                foreach ($this->items as $i => $item) : ?>
                <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->id; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td class="has-context">
                        <div class="pull-left break-word">
                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=view&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
				<?php echo $this->escape($item->title); ?>
                            </a>
                        </div>
                    </td>
                    <td class="has-context">
                        <div class="pull-left break-word">
                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=view&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
				<?php echo $this->escape($item->needed_signs); ?>
                            </a>
                        </div>
                    </td>
                    <td class="has-context">
                        <div class="pull-left break-word">
                            <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=view&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
				<?php echo $this->escape($item->state); ?>
                            </a>
                        </div>
                    </td>
                    <td class="hidden-phone">
                        <?php echo (int) $item->id; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="pagination">
            <?php echo $this->pagination->getListFooter(); ?>
        </div>
        
        <input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir; ?>" />
        <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>