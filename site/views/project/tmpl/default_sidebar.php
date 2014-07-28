<!-- show details -->
<?php if ($this->params->get('show_details')) : ?>
    <?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>

<!-- show links -->
<?php if ($this->params->get('show_links')) : ?>
    <?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>           

<!-- show articles created by manager -->
<?php if ($this->params->get('show_articles') && $this->project->manager && $this->project->articles) : ?>
    <?php echo $this->loadTemplate('articles'); ?>
<?php endif; ?>

<!-- render tags -->
<?php if ($this->params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
    <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
    <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
<?php endif; ?>