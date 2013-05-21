<?php


require_once("system/init.php");


if($_GET['t']=="logout"){
	session_destroy();
	header("Location: http://ritechatter.com/");
}
if($_GET['t']=="authin"){
	if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=="http://ritechatter.com/" && $_SERVER['HTTP_REFERER']==(strpos($_SERVER['HTTP_REFERER'], 'ritechatter.com/chat/') !== FALSE)){
		$_SESSION['twitter_auth_redirect'] = $_SERVER['HTTP_REFERER'];
	}
	header("Location: ../twitter_auth.php");
}
if($_GET['t']=="twitter_sign_in"){
	
}

if(isset($_POST['t'])){
	switch($_POST['t']){
		case 'register':
			if(isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm'])){
				if($_POST['password']==$_POST['confirm']){
					$register = $user->register(array('first'=>$_POST['first'], 'last'=>$_POST['last'], 'email'=>$_POST['email'], 'password'=>$user->genHash($_POST['password']), 'active'=>1, 'acct_type'=>1));
					if($register){ header("Location: terms.html"); }
				}else{
    			    header("Location: index.php");   
				}
			}
			break;
		case 'login':
			if(isset($_POST['username']) && isset($_POST['password'])){
				$login = $user->login($_POST['username'], $_POST['password']);
				if($login){
					header("Location: manager/home/");
				}
			}
			break;
	}
}

?>
<!DOCTYPE html>
<html>

	<head>
		<base href="/">
		<meta charset="utf-8">
		<title>Ritechatter &middot; User Login</title>
		
		<link rel="stylesheet" href="assets/css/bootstrap.css">
		<style type="text/css">
			html,body { background-color: #eee; }
			body { padding-top:40px; }
			.container { width:300px; }
			
			.container > .content { background-color:#fff; padding:20px; margin:0 -20px; border-radius:10px; box-shadow:0 1px 2px rgba(0,0,0,0.15); }
			.login-form { margin-left:65px; }
			legend { margin-right: -50px; font-weight:bold; color:#404040; }
		</style>
	</head>
	<body>
		
		<div class="container">
			<div class="content">
				<div class="row">
					<div class="login-form">
						<? if($_GET['t']=="register") : ?>
						<h2>Register</h2>
						<form action="" method="POST">
							<fieldset>
								<div class="clearfix">
									<input type="text" name="first" placeholder="First Name">
									<input type="text" name="last" placeholder="Last Name">
								</div>
								<div class="clearfix">
									<input type="text" name="email" placeholder="Email Address">
								</div>
								<div class="clearfix">
									<input type="password" name="password" placeholder="Password">
								</div>
								<div class="clearfix">
									<input type="password" name="confirm" placeholder="Confirm Password">
								</div>
								<input type="hidden" name="t" value="register">
								<button type="submit" class="btn">Register</button>
							</fieldset>
						</form>
						<? elseif($_GET['t']=="login") : ?>
						<h2>Login</h2>
						<form action="" method="POST">
							<fieldset>
								<div class="clearfix">
									<input type="text" name="username" placeholder="Username">
								</div>
								<div class="clearfix">
									<input type="password" name="password" placeholder="Password">
								</div>
								<input type="hidden" name="t" value="sign_in">
								<button type="submit" class="btn">Sign in</button>
							</fieldset>
						</form>
						<? endif; ?>
						<hr>
						<a class="btn btn-primary" href="?t=twitter_sign_in">Twitter Sign in</a>
					</div>
				</div>
			</div>
		</div>
		
	</body>

</html>