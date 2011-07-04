<?php 
/**
 * Displays some options for buying credits + some basic details about this.
 */
?><div class='innerContainer'>
<h1>Purchase Credits</h1>
<p>Kent app store credits cost &pound;1 each and are used to buy games and apps from the store.</p>
<p>Purchased credits are none refundable.</p>
<h2>Credit Packages</h2>
<div class='creditBox'>
	<p>5 Credits for &pound;5</p>
	<a href='<?php echo Util::parsePath('user/buyCredits/5'); ?>'><img src='https://checkout.google.com/buttons/checkout.gif?merchant_id=913973765595813&amp;w=180&amp;h=46&amp;style=white&amp;variant=text&amp;loc=en_GB' alt='checkout'/></a>
</div>
<div class='creditBox'>
	<p>10 Credits for &pound;10</p>
	<a href='<?php echo Util::parsePath('user/buyCredits/10'); ?>'><img src='https://checkout.google.com/buttons/checkout.gif?merchant_id=913973765595813&amp;w=180&amp;h=46&amp;style=white&amp;variant=text&amp;loc=en_GB' alt='checkout'/></a>
</div>
<div class='creditBox'>
	<p>20 Credits for &pound;20</p>
	<a href='<?php echo Util::parsePath('user/buyCredits/20'); ?>'><img src='https://checkout.google.com/buttons/checkout.gif?merchant_id=913973765595813&amp;w=180&amp;h=46&amp;style=white&amp;variant=text&amp;loc=en_GB' alt='checkout'/></a>
</div>
<div class='creditBox'>
<form onsubmit='return false;' action='#'>
	<p><select style='width:80px;' id='creditcount'>
	<?php 
	 for($c=1;$c<=20;$c++){
	 	echo "<option value='{$c}'>{$c}</option>";
	 }
	?>
	</select> Credit(s) </p>
	<div>
	<img src='https://checkout.google.com/buttons/checkout.gif?merchant_id=913973765595813&amp;w=180&amp;h=46&amp;style=white&amp;variant=text&amp;loc=en_GB' alt='checkout' style='cursor:pointer;' onclick='document.location = "<?php echo Util::parsePath('user/buyCredits/'); ?>" + document.getElementById("creditcount").value'/>
	</div>
</form>
</div>
</div>