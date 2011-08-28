<style type='text/css'>
 .twoColLayout .twoColLeft{
 	width:185px;float:left;
 }
 .twoColLayout .twoColRight{
 	width:640px;float:left;
 }
 .profile{
 
 }
 .profile .avatar{
 	width:120px;height:120px; background-color:whitesmoke; border:solid 1px #ddd;overflow:hidden; margin:0 auto;
 }
 .profile .avatar span {padding:4px;}
 
 .form {}
 .form p {padding:2px 4px;margin:0; vertical-align:middle;}
 .form label {width:120px; display: inline-block;}
 .form input {width:180px; border:solid 1px #18778F;padding:2px;}
 .form .save {border:solid 1px;padding:2px;background-color:#18778F;width:100px;cursor:pointer;color:#fff;}
 
 .infoBox {
	background-color: #18778F;
	margin: 8px 5px;
	padding: 5px;
	color: white;
 }
 .infoBox strong {width:80px;padding:1px;display: inline-block;}
</style>
<div class='twoColLayout'>
	<div class='twoColLeft'>
	
		<div class='profile'>
	 		<div class='avatar' style='height:140px;'>
	 			<img src="http://www.gravatar.com/avatar/<?php echo md5("thybag@gmail.com");?>?s=120&r=pg" />
	 			<span>Avatar (Gravatar)</span>
	 		</div>
	 		
	 		<p class='infoBox'>
		 		<strong>Registered:</strong> 21/04/2011
		 		<strong>Username:</strong>  Batman
		 		<strong>Group:</strong>  User
	 		</p>
	 		<p class='infoBox'>
	 		<strong>Messages:</strong> 5
	 		</p>
	 	
	 	
	 	</div>
	</div>
	<div class='twoColRight'>
		<div class='jsnipShowHide box' data-mode="open">
			<div class='title'><h3>Update your profile</h3></div>
			<div class='inner'>
				<form class='form' action='<?php Router::url(array("controller"=>"user", "action"=>"saveProfile")); ?>'>
					<p>
						<label>Email:</label><input value='<?php echo $data['email']?>'/>
					</p>
					<p>
						<label>Website:</label><input  value='<?php echo $data['website']?>'/> Error!
					</p>
					<p> 
						<label>DOB:</label><input value='<?php echo $data['dob']?>' />
					</p>
					<p>
						<label>Gender:</label><input  value='<?php echo $data['gender']?>'/>
					</p>
					<p>
						<label style='margin-top:10px;float:left;'>Bio:</label>
						<textarea style="width:490px;height:80px;border:solid 1px #18778F;"><?php echo $data['bio']?></textarea>
					</p>
					<p style='text-align:right;'>
						<input type='submit' class='save' value='Save' />
					</p>
				</form>
				
			
			</div>
		</div>
		<div class='jsnipShowHide box'>
			<div class='title'><h3>Change password</h3></div>
			<div class='inner'>
				<form class='form'>
					<p>
					<label>Old Password:</label><input />
					</p>
					<p>
					<label>New Password:</label><input /><br/><label>Again:</label><input />
					</p>
					<input type='submit' class='save'/>
				</form>
			</div>
		</div>
	</div>
</div>