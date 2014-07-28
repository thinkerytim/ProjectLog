<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_projectlog/helpers/route.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework', true);

$input     = JFactory::getApplication()->input;
$function  = $input->getCmd('function', 'jSelectProject');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_projectlog&view=logs&layout=modal&tmpl=component&function='.$function); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<fieldset class="filter clearfix">
        <div class="btn-toolbar">
			<div class="btn-group pull-left">
				<label for="filter_search">
					<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
				</label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_PROJECTLOG_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom">
					<i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.id('filter_search').value='';this.form.submit();">
					<i class="icon-remove"></i></button>
			</div>
			<div class="clearfix"></div>
		</div>
		<hr class="hr-condensed" />

		<div class="filters pull-left">
			<select name="filter_published" class="input-medium" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<?php if ($this->state->get('filter.forcedLanguage')) : ?>
                <?php echo $this->projectfield->callModal('filter_project_id', $this->state->get('filter.project_id'), false, $this->state->get('filter.forcedLanguage')); ?>
                <input type="hidden" name="forcedLanguage" value="<?php echo $this->escape($this->state->get('filter.forcedLanguage')); ?>" />
                <input type="hidden" name="filter_language" value="<?php echo $this->escape($this->state->get('filter.language')); ?>" />
            <?php else : ?>
                <?php echo $this->projectfield->callModal('filter_project_id', $this->state->get('filter.project_id')); ?>
                <select name="filter_language" class="input-medium" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
                </select>
			<?php endif; ?>
		</div>
	</fieldset>
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                </th>
                <th class="nowrap hidden-phone">
                    <?php echo JHtml::_('grid.sort', 'COM_PROJECTLOG_DESC', 'a.description', $listDirn, $listOrder); ?>
                </th>
                <th width="5%" class="nowrap hidden-phone">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" class="nowrap center hidden-phone">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <?php if ($item->language && JLanguageMultilang::isEnabled())
                {
                    $tag = strlen($item->language);
                    if ($tag == 5)
                    {
                        $lang = substr($item->language, 0, 2);
                    }
                    elseif ($tag == 6)
                    {
                        $lang = substr($item->language, 0, 3);
                    }
                    else {
                        $lang = "";
                    }
                }
                elseif (!JLanguageMultilang::isEnabled())
                {
                    $lang = "";
                }
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', '<?php echo $this->escape($item->project_id); ?>', null, '<?php echo $this->escape('#'); ?>', '<?php echo $this->escape($lang); ?>', null);">
                        <?php echo $this->escape($item->title); ?></a>
                        <div class="small">
                            <?php echo $item->project_name; ?>
                        </div>
                    </td>
                    <td class="center hidden-phone">
                        <?php echo $item->description; ?>
                    </td>
                    <td class="small hidden-phone">
                        <?php if ($item->language == '*'):?>
                            <?php echo JText::alt('JALL', 'language'); ?>
                        <?php else:?>
                            <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                        <?php endif;?>
                    </td>
                    <td align="center hidden-phone">
                        <?php echo $item->id; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
