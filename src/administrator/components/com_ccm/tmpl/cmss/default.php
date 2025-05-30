<?php
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
?>
<form action="<?php echo Route::_('index.php?option=com_ccm&view=cmss'); ?>" method="post" name="adminForm" id="adminForm">

    <div class="row">
        <div class="col-md-12">
        <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
        </div>
    </div>

    <?php if (!empty($this->items)) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo Text::_('Name'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $item) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo Route::_('index.php?option=com_ccm&view=cms&task=cms.edit&id=' . (int) $item->id); ?>">
                                <?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</form>
