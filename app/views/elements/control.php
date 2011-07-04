<?php 
/**
 * Shows navigation and login details (or login box if there not logged in)
 * 
 */

//Nav changes depending on login status
//
?><div class='controls'>
	<div class='nav'>
		<ul>
			<li><a href='<?php echo Util::parsePath('/');?>'>Home</a></li>
			<li><a href='<?php echo Util::parsePath('/appstore/all');?>'>About</a></li>
			<li><a href='<?php echo Util::parsePath('/appstore/all');?>'>Themes</a></li>
			<li><a href='<?php echo Util::parsePath('/appstore/all');?>'>Modules</a></li>
			<li><a href='<?php echo Util::parsePath('/appstore/all');?>'>Development</a></li>
		</ul>
	</div>
	<div class='login'>
	<?php 
	if($this->session->userSessionExist()){
		//If user is logged in, say hello and how many credits they have.
		echo "Welcome back <strong>{$this->session->getUserInfo('username')}</strong>. 
		
		<br/><br/>
		";
		//Provide logout and view purchased apps links.
		echo "<a href='{$base}/user/profile'>Update your profile</a> | ";
		echo "<a href='{$base}/user/logout'>Log out</a>";
	}else{
		//If not, show login form.
	?>
	<form method='post' action='<?php echo $base;?>/user/authenticate' class='form'>
	
	<div>Username: <input name='username' type='text' /></div>
	<div>Password: <input name='password' type='password' /></div>
	<div><input type='submit' value='login' /></div>
	
	</form>
	A php5 fully MVC user system framework.<br/><br/>
	
	<?php 
		//And register button
		echo "<a href='".Util::parsePath('user/register')."'>Register new account</a>";
	}//end if.
	?>
	
	</div>
</div>