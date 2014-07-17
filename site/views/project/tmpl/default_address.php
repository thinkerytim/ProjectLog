<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<dl class="project-address dl-horizontal" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<?php if (($this->params->get('address_check') > 0) &&
		($this->project->address || $this->project->project_type  || $this->project->client || $this->project->release_id || $this->project->job_id)) : ?>
		<?php if ($this->params->get('address_check') > 0) : ?>
			<dt>
				<span class="<?php echo $this->params->get('marker_class'); ?>" >
					<?php echo $this->params->get('marker_address'); ?>
				</span>
			</dt>
		<?php endif; ?>

		<?php if ($this->project->address && $this->params->get('show_street_address')) : ?>
			<dd>
				<span class="project-street" itemprop="streetAddress">
					<?php echo $this->project->address .'<br/>'; ?>
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
		<?php if ($this->project->job_id && $this->params->get('show_job_id')) : ?>
			<dd>
				<span class="project-job_id" itemprop="postalCode">
					<?php echo $this->project->job_id .'<br/>'; ?>
				</span>
			</dd>
		<?php endif; ?>
		<?php if ($this->project->release_id && $this->params->get('show_release_id')) : ?>
		<dd>
			<span class="project-release_id" itemprop="addressCountry">
				<?php echo $this->project->release_id .'<br/>'; ?>
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

<?php if ($this->project->task_id && $this->params->get('show_task_id')) : ?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
			<?php echo $this->params->get('marker_task_id'); ?>
		</span>
	</dt>
	<dd>
		<span class="project-task_id" itemprop="task_id">
			<?php echo nl2br($this->project->task_id); ?>
		</span>
	</dd>
<?php endif; ?>
<?php if ($this->project->workorder_id && $this->params->get('show_workorder_id')) : ?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>">
			<?php echo $this->params->get('marker_workorder_id'); ?>
		</span>
	</dt>
	<dd>
		<span class="project-workorder_id" itemprop="workorder_idNumber">
		<?php echo nl2br($this->project->workorder_id); ?>
		</span>
	</dd>
<?php endif; ?>
<?php if ($this->project->mobile && $this->params->get('show_mobile')) :?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
			<?php echo $this->params->get('marker_mobile'); ?>
		</span>
	</dt>
	<dd>
		<span class="project-mobile" itemprop="task_id">
			<?php echo nl2br($this->project->mobile); ?>
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
