<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<?php echo Configure::read('SITE_NAME').' Administrator'?>:
		<?php echo $title_for_layout;?>
	</title>
	<?php
		echo $html->charset();
		echo $html->meta('icon');
		echo $html->css('cake.generic');
		echo $javascript->link('jquery.js');
		echo $javascript->link('tiny_mce/tiny_mce.js');
		echo $this->element('focus');
		echo $javascript->codeBlock(
		'
		tinyMCE.init({
			mode : "textareas",
			plugins : "ibrowser", 
			theme : "advanced",
			editor_deselector : "mceNoEditor",
			theme_advanced_buttons3_add : "ibrowser"
		});
		'
		);
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $html->link(Configure::read('SITE_NAME').' - Administrator', '/admin');?></h1>
		</div>
		<ul id="nav">
		
		
			<?php echo $link->navigationAdmin() ?>
		</ul>
		<div id="content">
			<?php
				if (is_array($messages = $session->read('Message'))) {
					foreach (array_keys($messages) as $key) {
						$session->flash($key);
					}
				}
			?>

			<?php echo $content_for_layout;?>
			
			<?php 
				if ($admin) {
					echo "<div>{$html->link('Logout', array('controller' => 'users', 'action' => 'logout', 'admin' => false))}</div>";
				}
			
			?>

		</div>
		<div id="footer">Site By:
			<?php echo $html->link(
							'3HN Designs',
							'http://www.3HNDesigns.com/',
							array(), null, false
						);
			?>
		</div>
	</div>
	<?php echo $cakeDebug?>
</body>
</html>