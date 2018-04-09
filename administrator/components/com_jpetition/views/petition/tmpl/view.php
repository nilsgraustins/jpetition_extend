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

<form action="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="row-fluid">
		<div class="span8">
			<div class="form-inline form-inline-header">
				<div>
					<strong><?php echo JText::_('COM_JPETITION_TITLE'); ?></strong>
				</div>
				<div>
					<?php echo $this->item->title; ?>
				</div>
				<hr />
				<div>
					<strong><?php echo JText::_('COM_JPETITION_TEXT'); ?></strong>
				</div>
				<div>
					<?php echo $this->item->text; ?>
				</div>
				<hr />
				<?php if ($this->item->state == 1 && $this->item->signingBeforeTime <= getJoomlaDate() && $this->item->countSigning >= $this->componentParams->get('needed_signs', 250)) : ?>
					<div class="control-group">
						<div class="control-label">
							<label class="required" for="petition-text"><strong><?php echo JText::_('COM_JPETITION_ANSWER'); ?></strong></label>
						</div>
						<div class="controls">
							<?php echo JFactory::getEditor()->display('answer', $this->item->answer, '100%', '400', '100', '30') ;?>
						</div>
					</div>
				<?php elseif ($this->item->state == 2) : ?>
					<div>
						<strong><?php echo JText::_('COM_JPETITION_ANSWER'); ?></strong>
					</div>
					<div>
						<?php echo $this->item->answer; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>