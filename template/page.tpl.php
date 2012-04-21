<?php print $doctype."\n"; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="cache-control" content="max-age=200" />
    <?php print $styles; ?>
    <?php print $scripts; ?>

		<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>	
<title><?php print $head_title; ?></title>
</head>
<body>
<div class="ui-bar">
	<div class="ui-bar-a">
		<div data-role="header" data-position="inline">
				<?php print $logo_img; ?>
				<?php print $breadcrumb;?>
		</div>
	</div>
<div class="ui-body ui-body-b">
			<?php if ($mission) print '<div id="mission">' . $mission . '</div>'; ?>
			<?php if ($title) print '<div id="main"><h2>' . $title . '</h2></div>'; ?>
			<?php if ($tabs) print '<div data-role="navbar"><ul>' . $tabs . '</ul></div>'; ?>
			<?php if ($tabs2) print '<ul class="tabs secondary">' . $tabs2 . '</ul>'; ?>
			<?php if ($show_messages && $messages) print $messages; ?>
			<?php if ($help) print $help; ?>
			<?php if(!$mode_user) { 
					print drupal_get_form('user_login');
			}
			?>			
			<div id="content">
				<?php print $content; ?>
			</div>
				<?php if($mode_user) { ?>
				<?php print $left . $right; ?>
				<?php } ?>
			</div>	
	<div data-role="footer" data-position="fixed">
				Netspective
			</div>
</div>
</body>
</html>