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
    <div class="petition">
        <div class="row-fluid">
            <div class="span8">
                <h1><?php echo $this->item->title; ?></h1>
                <div class="petition_author">
                    <?php echo JText::_('COM_JPETITION_AUTHOR'); ?>: <?php echo $this->item->author; ?>
                </div>
                <div class="petition_state">
                    <?php echo JText::_('COM_JPETITION_STATE'); ?>: 

                    <?php if ($this->item->state == 2) : ?>
                        <?php echo JText::_('COM_JPETITION_WITH_ANSWER'); ?>
                    <?php elseif ($this->item->state == 1 && $this->item->signingBeforeTime <= getJoomlaDate() && $this->item->countSigning >= $this->componentParams->get('needed_signs', 250)) : ?>
                        <?php echo JText::_('COM_JPETITION_IN_PROCESS'); ?>
                    <?php elseif ($this->item->state == 1 && $this->item->signingBeforeTime > getJoomlaDate()) : ?>
                        <?php echo JText::_('COM_JPETITION_ACTIVE'); ?>
                    <?php else : ?>
                        <?php echo JText::_('COM_JPETITION_NO_SIGNING'); ?>
                    <?php endif; ?>
                </div>
                <div class="petition_create">
                    <?php echo JText::_('COM_JPETITION_CREATE_DATE'); ?>: <?php echo getJoomlaDate($this->item->created, 'd.m.Y'); ?>
                </div>
                <div class="petition_start_signing">
                    <?php echo JText::_('COM_JPETITION_START_SIGNING'); ?>: <?php echo getJoomlaDate($this->item->start_signing, 'd.m.Y'); ?>
                </div>

                <h3><?php echo JText::_('COM_JPETITION_PETITION_TEXT'); ?></h3>
                <div class="petition_text">
                    <?php echo $this->item->text;
                    //JConfig::sh($this->item );
                    ?>
                </div>

				<?php if ($this->item->state == 2) : ?>
                    <h3><?php echo JText::_('COM_JPETITION_ANSWER'); ?></h3>
                    <div class="petition_answer">
                        <?php echo $this->item->answer; ?>
                    </div>
                <?php endif; ?>
				
                <div class="petition_signing">
                    <?php if ($this->item->countSigning) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_jpetition&view=votes&id='.$this->item->id); ?>">
                            <?php echo sprintf(JText::_('COM_JPETITION_SIGNING'), getJoomlaDate('now', 'd.m.Y'), '<span id="petition-count-votes">'.$this->item->countSigning.'</span>', $this->componentParams->get('needed_signs', 250)); ?>
                        </a>
                    <?php else : ?>
                        <?php echo sprintf(JText::_('COM_JPETITION_SIGNING'), getJoomlaDate('now', 'd.m.Y'), '<span id="petition-count-votes">'.$this->item->countSigning.'</span>', $this->componentParams->get('needed_signs', 250)); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="span4">
                <div class="count-signed">
                    <?php echo JText::_('COM_JPETITION_COUNT_SIGNED'); ?>
                </div>
                <div class="petition-votes">
                    <canvas id="petition-votes-graph" width="250" height="250" data-needed-signs="<?php echo $this->componentParams->get('needed_signs', 250); ?>" data-signs="<?php echo $this->item->countSigning; ?>"></canvas>
                    <div class="petition-votes-text">
                        <div class="petition-count-votes" id="petition-count-votes-graph">
                            <?php echo $this->item->countSigning; ?>
                        </div>
                        <div class="petition-signs-count">
                            <?php echo sprintf(JText::_('COM_JPETITION_SIGNS_FROM'), $this->componentParams->get('needed_signs', 250)); ?>
                        </div>
                        <div class="petition-signs-needed">
                            <?php echo JText::_('COM_JPETITION_NEEDED'); ?>
                        </div>
                    </div>
                </div>
                <div class="petition-days-left" id="petition-days-left">
                    <?php if ($this->item->daysleft > 0) : ?>
                        <?php echo JText::_('COM_JPETITION_STOP_LEFT'); ?>

                        <?php if ($this->item->daysleft == 1) : ?>
                            <span id="petition-days-left-clock">
                                <span class="hours"></span>:<span class="minutes"></span>:<span class="seconds"></span>
                            </span>
                        <?php else : ?>
                            <span><?php echo $this->item->daysleft; ?> <?php echo pluralForm($this->item->daysleft, JText::_('COM_JPETITION_DAY'), JText::_('COM_JPETITION_DAYS'), JText::_('COM_JPETITION_S_DAYS')); ?></span>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php echo JText::_('COM_JPETITION_SIGNING_FINISH'); ?>
                    <?php endif; ?>
                </div>
                <?php if ($this->item->daysleft > 0) : ?>
                    <div class="petition-sign" id="petition-sign">
                        <?php echo JText::_('COM_JPETITION_SIGN_PETITION'); ?>

                        <?php if ($this->userSignPetition) : ?>
                            <div class="petition-was-signed">
                                <?php echo JText::_('COM_JPETITION_WAS_SIGNED'); ?>
                            </div>
                        <?php elseif (!$this->user || $this->user->guest) : ?>
                            <div class="petition-login">
                                <?php echo JText::_('COM_JPETITION_LOGIN_FOR_SIGN'); ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.$this->returnUrl); ?>">
                                    <?php echo JText::_('COM_JPETITION_LOGIN'); ?>
                                </a>
                            </div>						
                        <?php else : ?>
                            <input id="put-petition" class="btn btn-primary btn-sign-petition" type="button" value="<?php echo JText::_('COM_JPETITION_SIGN'); ?>" data-id="<?php echo $this->item->id; ?>" />
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
	<?php echo JFactory::getApplication()->input->get('view-layout', null, "RAW"); ?>
</div>