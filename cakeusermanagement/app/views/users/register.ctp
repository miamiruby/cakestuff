<div class="user">
<?php echo $form->create('User', array('action' => 'register'));?>
	<fieldset>
 		<legend>Register User</legend>
	<?php
		echo $form->input('username');
		echo $form->input('new_password', array('type' => 'password'));
		echo $form->input('confirm_password', array('type' => 'password'));
		echo $form->input('fname');
		echo $form->input('lname');
		echo $form->input('email');
		echo $form->input('confirm_email');
		//echo $form->input('group_id');
		
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>