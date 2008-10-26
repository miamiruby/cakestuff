<div class="users">
<h2><?php __('Users');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('username');?></th>
	<th><?php echo $paginator->sort('password');?></th>
	<th><?php echo $paginator->sort('fname');?></th>
	<th><?php echo $paginator->sort('lname');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<th><?php echo $paginator->sort('active');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $user['User']['username'] ?>
		</td>
		<td>
			<?php echo $user['User']['password'] ?>
		</td>
		<td>
			<?php echo $user['User']['fname'] ?>
		</td>
		<td>
			<?php echo $user['User']['lname'] ?>
		</td>
		<td>
			<?php echo $user['User']['email'] ?>
		</td>
		
		<td>
			<?php echo $user['User']['active'] ?>
		</td>
		<td>
			<?php echo $user['User']['created'] ?>
		</td>
		<td>
			<?php echo $user['User']['modified'] ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $user['User']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $user['User']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $user['User']['id']), null, 
				'Are you sure you want to delete this user?'); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('User', true)), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Groups', true)), array('controller'=> 'groups', 'action'=>'index', 'admin'=>false)); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s',  true), __('Group', true)), array('controller'=> 'groups', 'action'=>'add', 'admin'=>false)); ?> </li>
	</ul>
</div>
