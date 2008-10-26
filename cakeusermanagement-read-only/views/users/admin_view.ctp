<div class="user">
<h2><?php  __('User');?></h2>
	<dl>
		<dt class="altrow"><?php __('Id') ?></dt>
		<dd class="altrow">
			<?php echo $user['User']['id'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Username') ?></dt>
		<dd>
			<?php echo $user['User']['username'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Password') ?></dt>
		<dd class="altrow">
			<?php echo $user['User']['password'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Fname') ?></dt>
		<dd>
			<?php echo $user['User']['fname'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Lname') ?></dt>
		<dd class="altrow">
			<?php echo $user['User']['lname'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Email') ?></dt>
		<dd>
			<?php echo $user['User']['email'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Group') ?></dt>
		<dd class="altrow">
			<?php echo $html->link(__($user['Group']['name'], true), array('controller'=> 'groups', 'action'=>'view', $user['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php __('Active') ?></dt>
		<dd>
			<?php echo $user['User']['active'] ?>
			&nbsp;
		</dd>
		<dt class="altrow"><?php __('Created') ?></dt>
		<dd class="altrow">
			<?php echo $user['User']['created'] ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified') ?></dt>
		<dd>
			<?php echo $user['User']['modified'] ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('Edit %s', true), __('User', true)), array('action'=>'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(sprintf(__('Delete %s', true), __('User', true)), array('action'=>'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Users', true)), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('User', true)), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Groups', true)), array('controller'=> 'groups', 'action'=>'index', 'admin'=>false)); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Group', true)), array('controller'=> 'groups', 'action'=>'add', 'admin'=>false)); ?> </li>
	</ul>
</div>
