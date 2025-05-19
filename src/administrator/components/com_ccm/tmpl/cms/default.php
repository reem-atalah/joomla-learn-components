<?php
use Joomla\CMS\Language\Text;
?>
<h1><?php echo htmlspecialchars($this->item->cms_name, ENT_QUOTES, 'UTF-8'); ?></h1>
<p><?php echo Text::_('ID'); ?>: <?php echo (int) $this->item->id; ?></p>