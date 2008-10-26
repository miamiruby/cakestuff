<div class="user">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Add %s', true), __('User', true));?></legend>
	<?php
		echo $form->input('username');
		echo $form->input('new_password', array('type' => 'password'));
		echo $form->input('confirm_password', array('type' => 'password'));
		echo $form->input('fname');
		echo $form->input('lname');
		echo $form->input('email');
		echo $form->input('confirm_email');
		echo $form->input('Group');
		echo $form->input('active');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>