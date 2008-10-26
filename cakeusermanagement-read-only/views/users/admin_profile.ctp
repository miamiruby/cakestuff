<div class="user">
<?php echo $form->create('User', array('action' => 'profile'));?>
	<fieldset>
 		<legend>User Profile</legend>
	<?php
		echo $form->input('id');
		echo $form->input('username', array('type' => 'hidden'));
		echo $form->input('new_password', array('type' => 'password'));
		echo $form->input('confirm_password', array('type' => 'password'));
		
		echo $form->input('fname');
		echo $form->input('lname');
		echo $form->input('email');
		echo $form->input('confirm_email');
		//echo $form->input('group_id');
		//echo $form->input('active');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>

