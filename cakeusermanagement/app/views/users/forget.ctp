<div class="user">
<?php echo $form->create('User', array('action' => 'forget'));?>
	<fieldset>
 		<legend>Forget Password</legend>
	<?php
		echo $form->input('username', array('label' => 'Username or Email Address'));
		
		///echo $form->input('remember_me', array('label' => 'Remember Me', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
