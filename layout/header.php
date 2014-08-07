<?php
// if login / registration errors exist, show the panel
if (isset($_SESSION["msg"]["login-err"]) && isset($_SESSION["msg"]["login-err"])) {
	if ($_SESSION["msg"]["login-err"] || $_SESSION["msg"]["register-err"]) {
?>
<script>
	$(document).ready(function(){
		$("#panel").show();
		$(".tab li.middle").toggle();
	});
</script>
<?php
	}
}
?>
<script>
	$(document).ready(function(){
		// log in panel
		$(".tab li.notlogged").click(function(){	
			$("#panel").slideToggle(300, function() {;
				$(".tab li.middle").toggle();
			});
		});
		$(".logout").click(function(){
			$(location).attr("href", "index.php?logoff=true");
		});
		
	});
</script>


<!-- Start Log in panel -->
<div id="top-panel">
	<div id="panel">
		<div class="center-container-x">
			<div class="center-fixer-container-x">
				<div class="panel-box-left">
					<h2 style="text-align: center;">Login</h2>
					<br>
					<form method="post" action="index.php?page=<?php echo $_GET["page"] ?>">
						<input type="text" name="loginUsername" placeholder="Username">
						<input type="password" name="loginPassword" placeholder="Password">
						<br>
						<input type="checkbox" name="rememberMe"><span class="form-text">&nbspRemember Me</span><br>
						<input type="submit" name="submit" value="Login">
						<?php 
							if(isset($_SESSION['msg']['login-err'])) {
						?>
						<br><span class="error"><?php echo $_SESSION['msg']['login-err']; ?></span>
						<?php 
							}
						?>			
					</form>
				</div>

				<div class="panel-box-right">
					<h2 style="text-align: center;">Register A New Account</h2>
					<br>
					<form method="post" action="index.php?page=<?php echo $_GET["page"] ?>">
						<input type="text" name="registerUsername" placeholder="Username"><br>
						<input type="text" name="registerEmail" placeholder="Email"><br>
						<input type="submit" name="submit" value="Register"><br>
						<span class="contact-form-text-small">A temporary password will be emailed to you.</span>
						<?php 
							if(isset($_SESSION["msg"]["register-err"])) {
						?>
						<span class="error"><?php echo $_SESSION["msg"]["register-err"]; ?></span>
						<?php 
							}
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Clickable tab -->
	<div class="tab">
		<div class="center-container-x">
			<ul class="login">
				<?php	// if user is logged in, show logout button, otherwise, allow panel slide and toggle (Login/Register <-> Close)
					if(isset($_SESSION["id"])) {
				?>
				<li class="left logout"></li>
				<li class="middle logout">Logoff</li>
				<li class="right logout"></li>	
				<?php 
					}else{
				?>
				<li class="left notlogged"></li>
				<li class="middle notlogged">Login / Register</li>
				<li class="middle notlogged close" style="display: none;">Close</li>
				<li class="right notlogged"></li>	
				<?php
					}
				?>
			</ul>
		</div>
	</div>
</div>
<!-- End Log in panel -->
<!--End Layout Header-->

