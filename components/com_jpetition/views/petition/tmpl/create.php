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
	<form action="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=save'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="form-horizontal">
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('COM_JPETITION_TITLE'); ?>:
						</div>
						<div class="controls">
							<input required="required" class="input-xxlarge required" type="text" name="title" value="<?php echo $this->item->title; ?>" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo JText::_('COM_JPETITION_TEXT'); ?>:
						</div>
						<div class="controls">
							<textarea required="required" class="input-xxlarge required" rows="10" name="text"><?php echo $this->item->text; ?></textarea>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<input class="btn btn-primary" type="submit" name="submit" value="<?php echo JText::_('COM_JPETITION_CREATE_PETITION'); ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<?php echo JFactory::getApplication()->input->get('view-layout', null, "RAW"); ?>
</div>