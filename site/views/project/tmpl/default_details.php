<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->project->manager && $this->params->get('show_manager')) : ?>
    <dl class="project-position dl-horizontal">
        <dd itemprop="jobTitle">
            <?php echo $this->project->manager; ?>
        </dd>
    </dl>
<?php endif; ?>

<?php if ($this->params->get('allow_vcard')) :	?>
    <?php echo JText::_('COM_PROJECTLOG_DOWNLOAD_INFORMATION_AS');?>
    <a href="<?php echo JRoute::_('index.php?option=com_projectlog&amp;view=project&amp;id='.$this->project->id . '&amp;format=vcf'); ?>">
    <?php echo JText::_('COM_PROJECTLOG_VCARD');?></a>
<?php endif; ?>

<dl class="project-address dl-horizontal" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<?php if (($this->params->get('address_check') > 0) &&
		($this->project->specific_loc || $this->project->project_type  || $this->project->client || $this->project->release_id || $this->project->job_id)) : ?>
		<?php if ($this->params->get('address_check') > 0) : ?>
			<dt>
				<span class="<?php echo $this->params->get('marker_class'); ?>" >
					<?php echo $this->params->get('marker_address'); ?>
				</span>
			</dt>
		<?php endif; ?>

		<?php if ($this->project->specific_loc && $this->params->get('show_street_address')) : ?>
			<dd>
				<span class="project-street" itemprop="streetAddress">
					<?php echo $this->project->specific_loc .'<br/>'; ?>
				</span>
			</dd>
		<?php endif; ?>

		<?php if ($this->project->project_type && $this->params->get('show_project_type')) : ?>
			<dd>
				<span class="project-project_type" itemprop="addressLocality">
					<?php echo $this->project->project_type .'<br/>'; ?>
				</span>
			</dd>
		<?php endif; ?>
		<?php if ($this->project->client && $this->params->get('show_client')) : ?>
			<dd>
				<span class="project-client" itemprop="addressRegion">
					<?php echo $this->project->client . '<br/>'; ?>
				</span>
			</dd>
		<?php endif; ?>
		
		
	<?php endif; ?>

<?php if ($this->project->email_to && $this->params->get('show_email')) : ?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>" itemprop="email">
			<?php echo nl2br($this->params->get('marker_email')); ?>
		</span>
	</dt>
	<dd>
		<span class="project-emailto">
			<?php echo $this->project->email_to; ?>
		</span>
	</dd>
<?php endif; ?>



<?php if ($this->project->webpage && $this->params->get('show_webpage')) : ?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
		</span>
	</dt>
	<dd>
		<span class="project-webpage">
			<a href="<?php echo $this->project->webpage; ?>" target="_blank" itemprop="url">
			<?php echo JStringPunycode::urlToUTF8($this->project->webpage); ?></a>
		</span>
	</dd>
<?php endif; ?>
</dl>
