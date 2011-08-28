<!DOCTYPE html> 
<html dir="ltr" lang="en-GB"> 
	<head>
		<?php
			$base = Config::get('base_dir');
		?>
		<title>Magic 2.0</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link href='style/global.css' rel="stylesheet" type="text/css" />
		<script type="text/javascript" src='js/base.min.js'></script> 
		<script type="text/javascript" src='js/jsnip.min.js'></script> 
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<div class='logo'>
					<a href='<?php echo $base; ?>'><img src='img/logo.png' alt='' /></a>
				</div>
				<!-- Control bar -->
				<?php 
					$this->addElement('control');
				?>
				<!-- End Control bar -->
			</div>
			<!-- Flash? -->
			<?php echo $this->getFlash(); ?>
			<!-- Flash? -->
			<div class='content'>
				<!-- Main page content -->
				<?php echo $data; ?>
				<!-- End main page content -->
				<div class='clear'></div>
			</div>
			<div class='footer'>
				<div class='page_footer'>
					Text here
				</div>
				<div class='under_nav'>
					<div style='float:right;padding-right:6px;'>
						<a href='<?php echo Util::parsePath('/page/contact');?>'>Contact</a> | 
						<a href='<?php echo Util::parsePath('/page/feedback');?>'>Feedback</a> |
						<a href='<?php echo Util::parsePath('/page/about');?>'> About </a>
					</div>
					<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style ">
					<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4d78439b1382a8b2" class="addthis_button_compact">Share</a>
					<span class="addthis_separator">|</span>
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					</div>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d78439b1382a8b2"></script>
					<!-- AddThis Button END -->
					
				</div>
			</div>
		</div>
		
	</body>
</html>