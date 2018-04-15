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
	<script src="/site/media/editors/tinymce/tinymce.min.js?e81298d66a7123667798b6c3a739bef0"></script>
	<script src="/site/media/editors/tinymce/js/tinymce.min.js?e81298d66a7123667798b6c3a739bef0"></script>
        
        <script>
            tinymce.init({
    selector: "#jform_description",
    theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
    ],
	});
        </script>
<form action="<?php echo JRoute::_('index.php?option=com_jpetition&view=petition&layout=view&id='.$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row-fluid">

        <div class="btn-wrapper" id="toolbar-apply">
            <!--button onclick="this.form.submit()" class="btn btn-small button-apply btn-success">
                <span class="icon-apply icon-white" aria-hidden="true"></span>
                Save</button-->
        </div>
        <div class="span8">
            <div class="form-inline form-inline-header administrator-components-com_jpetition-views-petition-tmpl-view-php">               
                <HR/>
                <div>
                    <strong><?php echo JText::_('COM_JPETITION_STATE'); ?></strong>
                </div>
                <div>
                    
                    <select  name="state" />
                    <option value ="0"><?php echo JText::_('COM_JPETITION_NOT_PUBLISHED'); ?></option>
                    <option value ="1" <?php echo $this->item->state==1 ? 'selected':null; ?> ><?php echo JText::_('COM_JPETITION_PUBLISHED'); ?></option>
                    <option value ="2" <?php echo $this->item->state==2 ? 'selected':null; ?> ><?php echo JText::_('COM_JPETITION_STATE_PROCESSED'); ?></option>
                    </select>
                </div>
                <hr/>
                <div>
                    <strong><?php echo JText::_('COM_JPETITION_NEEDED'); ?></strong>
                </div>
                <div>
                    <input value="<?php echo $this->item->needed_signs; ?>" name="needed_signs" />
                </div>

                <hr/>
                <div>
                    <strong><?php echo JText::_('COM_JPETITION_TITLE'); ?></strong>
                </div>
                <div>
                    <?php
                    //$this->form->renderField('title'); 
                   // JConfig::sh($this->item);
                //   JConfig::sh(get_included_files() );//die;//extends JModelItem
                    //$this->form = $this->get('Form'); ///
                    //JConfig::sh( $this->form );//NULL///
                    // pubblisklas puses petiotion modelis def param
                    ?>
                    <input value="<?php echo $this->item->title; ?>"  name="Petitontitle" />
                </div>
                <hr />
                <div>
                    <strong><?php echo JText::_('COM_JPETITION_TEXT'); ?></strong>
                </div>
                <div>
                    <?php //echo $this->item->text; ?>
                    <textarea name="Petitontext" 
                              
        id="jform_description"
	cols=""
	rows=""
	style="width: 100%; height: 500px;"
	class="mce_editable joomla-editor-tinymce"
        ><?php echo $this->item->text; ?></textarea> 
                </div>
                <hr />
                <?php if ($this->item->state == 1 && $this->item->signingBeforeTime <= getJoomlaDate() && $this->item->countSigning >= $this->componentParams->get('needed_signs', 250)) : ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label class="required" for="petition-text"><strong><?php echo JText::_('COM_JPETITION_ANSWER'); ?></strong></label>
                        </div>
                        <div class="controls">
                            <?php echo JFactory::getEditor()->display('answer', $this->item->answer, '100%', '400', '100', '30'); ?>
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