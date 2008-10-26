<div class="user">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Edit %s', true), __('User', true));?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('username');
		//echo $form->input('password');
		
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
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Users', true)), array('action'=>'index'));?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Groups', true)), array('controller'=> 'groups', 'action'=>'index', 'admin'=>false)); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Group', true)), array('controller'=> 'groups', 'action'=>'add', 'admin'=>false)); ?> </li>
	</ul>
</div>
