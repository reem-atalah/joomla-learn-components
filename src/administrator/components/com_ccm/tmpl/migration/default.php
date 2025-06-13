<?php
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
?>
<form action="<?php echo Route::_('index.php?option=com_ccm&task=migration.apply'); ?>" method="post" id="migration-form" name="adminForm" class="form-validate">
    <div>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <legend><?php echo JText::_('COM_CCM_MIGRATION_FIELDSET_LABEL'); ?></legend>
                    <div class="col-md-6">
                    <?php
                    echo $this->form->renderField('source_cms');
                    echo $this->form->renderField('source_cms_object_type');
                    echo $this->form->renderField('target_cms');
                    echo $this->form->renderField('target_cms_object_type');
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <?php echo JText::_('APPLY_MIGRATION_BTN'); ?>
            </button>
        </div>
    </div>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>