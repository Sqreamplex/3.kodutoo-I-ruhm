<?php 
	
	require("../functions.php");
	
	require("../class/User.class.php");
	$User = new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	// kui on juba sisse loginud siis suunan data lehele
	if (isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: data.php");
		exit();
		
	}
	
	//echo hash("sha512", "b");
	
	
	//GET ja POSTi muutujad
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//echo strlen("äö");
	
	// MUUTUJAD
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupEmail = "";
	$signupGender = "male";
	$Nickname = "";
	$Nicknameerror = "";
	
	// on üldse olemas selline muutja
	if( isset( $_POST["signupEmail"] ) ){
		
		//jah on olemas
		//kas on tühi
		if( empty( $_POST["signupEmail"] ) ){
			
			$signupEmailError = "This field is required";
			
		} else {
			
			// email olemas 
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	} 
	
if ( isset ( $_POST["Nickname"] ) ) {
		if ( empty ( $_POST["Nickname"] ) ) {
			$Nicknameerror = "This field is required";
		} else {
			
			$Nickname = $_POST["Nickname"];
		}
	}
	
	if( isset( $_POST["signupPassword"] ) ){
		
		if( empty( $_POST["signupPassword"] ) ){
			
			$signupPasswordError = "This field is required";
			
		} else {
			
			// siia jõuan siis kui parool oli olemas - isset
			// parool ei olnud tühi -empty
			
			// kas parooli pikkus on väiksem kui 8 
			if ( strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "Password must be atleast 8 symbols";
			
			}
			
		}
		
	}
	
	
	// GENDER
	if( isset( $_POST["signupGender"] ) ){
		
		if(!empty( $_POST["signupGender"] ) ){
		
			$signupGender = $_POST["signupGender"];
			
		}
		
	} 
	
	// peab olema email ja parool
	// ühtegi errorit
	
	if ( isset($_POST["signupPassword"]) &&
		isset($_POST["signupEmail"]) &&
		isset($_POST["Nickname"]) &&
		empty($signupEmailError) && 
		empty($signupPasswordError) &&
		empty($Nicknameerror) 
		) {
		echo "Salvestan...<br>";
		echo "email ".$signupEmail."<br>";
		$password = hash("sha512", $_POST["signupPassword"]);
		echo "parool ".$_POST["signupPassword"]."<br>";
		echo "räsi ".$password."<br>";
		echo "name " .$Nickname."<br>";
		
		
		//echo $serverUsername;
		
		// KASUTAN FUNKTSIOONI
		$signupEmail = $Helper->cleanInput($signupEmail);
		
		$User->signUp($signupEmail, $Helper->cleanInput($password));
		
	
	}
	
	
	$error ="";
	if ( isset($_POST["loginEmail"]) && 
		isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && 
		!empty($_POST["loginPassword"])
	  ) {
		  
		$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
		
	}
	
?>
<?php require("../header.php"); ?>

	
	<div class="container">
	
		<div class="row">
		
			<div class="col-sm-4 col-md-3">
				<h1>Sign in</h1>
				<form method="POST">
					<p style="color:red;"><?=$error;?></p>
					<label>E-mail</label>
					<br>
					
					<div class="form-group">
						<input class="form-control" name="loginEmail" type="email">
					</div>
					
					<div class="form-group">
					<input class="form-control" type="password" name="loginPassword" placeholder="Password">
					<br>
					</div>
					
					<input type="submit" class="btn btn-success" value="Log in">
					
					
					
				</form>
			</div>
			
			<div class="col-sm-4 col-md-3 col-sm-offset-4 col-md-offset-3">
				<h1>Sign up</h1>
				<form method="POST">
					
					<label>E-mail</label>
					<br>
					<div class="form-group">
					<input class="form-control" name="signupEmail" type="email" value="<?=$signupEmail;?>"> <?=$signupEmailError;?>
					</div>
					<div class="form-group">
					<input class="form-control" type="password" name="signupPassword" placeholder="Password"> <?php echo $signupPasswordError; ?>
					</div>
					
					<?php if($signupGender == "male") { ?>
						<input type="radio" name="signupGender" value="male" checked> Male<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="male"> Male<br>
					<?php } ?>
					
					<?php if($signupGender == "female") { ?>
						<input type="radio" name="signupGender" value="female" checked> Female<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="female"> Female<br>
					<?php } ?>
					
					<?php if($signupGender == "other") { ?>
						<input type="radio" name="signupGender" value="other" checked> Other<br>
					<?php }else { ?>
						<input type="radio" name="signupGender" value="other"> Other<br>
					<?php } ?>
					<br>
					<label>Nickname</label>
					<div class="form-group">
					<input class="form-control" name="Nickname" type="text"> <?php echo $Nicknameerror; ?>
					</div>
					<input type="submit" class="btn btn-success value="Create account">
					
					
				</form>
			</div>
			
					
		</div>
		
	</div>
<?php require("../footer.php"); ?>