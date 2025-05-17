<?php foreach ($this->form->getFieldset() as $field) :?>
    <?php echo $field->renderField(); ?>
<?php endforeach;?>