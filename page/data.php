<?php 
	
	require("../functions.php");
	
	require("../class/Sports.class.php");
	$bdayandtel = new bdayandtel($mysqli);
	
	require("../class/Helper.class.php");
	$Helper = new Helper();
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["sunnipaev"]) && 
		isset($_POST["sunnipaev"]) && 
		!empty($_POST["telefon"]) && 
		!empty($_POST["telefon"])
	  ) {
		  
		$bdayandtel->save($Helper->cleanInput($_POST["sunnipaev"]), $Helper->cleanInput($_POST["telefon"]));
		
	}
	
	//saan kõik auto andmed
	
	//kas otsib
	if(isset($_GET["q"])){
		
		// kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		
	}else{
		
		// otsisõna tühi
		$q = "";
	}
	
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsisõna fn sisse
	$finishData = $bdayandtel->get($q, $sort, $order);
	
	
	
	
	//echo "<pre>";
	//var_dump($carData);
	//echo "</pre>";
?>
<?php require("../header.php"); ?>
<?=$msg;?>
<p>
	<form> Welcome <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logout</a></form>
</p>


<h2><form>Add your birthday and phonenumber</h2></form>
<form method="POST">
  Birthday:
  <input type="date" name="sunnipaev">
  <br></br>
  Phonenumber:
  <input type='tel' name="telefon" placeholder="Naide 56883412" pattern='\d{4}\d{4}' title='Example 9999-9999)'> 
  <br></br>
  <input type="submit" value="Save">
</form>

<h2><form>Archive</h2></form>

<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Search">

</form>

<?php 
	
	$html = "<table class='table table-striped'>";
	
	
	$html .= "<tr>";
	
		$idOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$idOrder = "DESC";
			$arrow = "&uarr;";
		}
	
		$html .= "<th>
					<a href='?q=".$q."&sort=id&order=".$idOrder."'>
						id ".$arrow."
					</a>
				 </th>";
				 
				 
		$levelOrder = "ASC";
		$arrow = "&darr;";
		if (isset($_GET["order"]) && $_GET["order"] == "ASC"){
			$levelOrder = "DESC";
			$arrow = "&uarr;";
		}
		$html .= "<th>
					<a href='?q=".$q."&sort=sunnipaev&order=".$levelOrder."'>
						sunnipaev
					</a>
				 </th>";
		$html .= "<th>
					<a href='?q=".$q."&sort=telefon&order=".$levelOrder."'>
						telefon
					</a>
				 </th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($finishData as $f){
		
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$f->id."</td>";
			$html .= "<td>".$f->sunnipaev."</td>";
			$html .= "<td>".$f->telefon."</td>";
			$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$f->id."'><span class='glyphicon glyphicon-pencil'></span> Edit</a></td>";
			
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	
	foreach($finishData as $f){
		
		
		$listHtml .= "<h1>".$f->sunnipaev."</h1>";
		$listHtml .= "<p>telefon = ".$f->telefon."</p>";
	}
	
	//echo $listHtml;
	
	
	
?>
<?php require("../footer.php"); ?>