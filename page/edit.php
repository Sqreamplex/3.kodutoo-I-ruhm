<?php
	//edit.php
	require("../functions.php");
	
	require("../class/Sports.class.php");
	$bdayandtel = new bdayandtel($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$bdayandtel->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["sunnipaev"]), $Helper->cleanInput($_POST["telefon"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$bdayandtel->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = $bdayandtel->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}
	
?>
<?php require("../header.php"); ?>
<br><br>
<a href="data.php"> Back </a>

<h2>Change info</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="bday" >Birthday</label><br>
	<input id="bday" name="sunnipaev" type="date" value="<?php echo $c->sunnipaev;?>" ><br><br>
  	<label for="tel" >Telephone</label><br>
	<input id="tel" name="telefon" type="tel" placeholder="Naide 56883412" pattern='\d{4}\d{4}' title='Telefoni numbri naide 9999-9999)' value="<?=$c->telefon;?>"><br><br>
  	
	<input type="submit" name="update" value="Save">
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">Delete</a>
 <?php require("../footer.php"); ?>