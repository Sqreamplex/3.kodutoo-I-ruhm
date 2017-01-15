<?php 
class bdayandtel {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}
	/*TEISED FUNKTSIOONID */
	function delete($id){
		$stmt = $this->connection->prepare("UPDATE bdayandtel SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "sunnipaev", "telefon"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		
		//kas otsib
		if ($q != "") {
			
			echo "Looking for: " .$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, sunnipaev, telefon
				FROM bdayandtel
				WHERE deleted IS NULL 
				AND (sunnipaev LIKE ? OR telefon LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);
			
		} else {
			
			$stmt = $this->connection->prepare("
				SELECT id, sunnipaev, telefon
				FROM bdayandtel
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $sunnipaev, $telefon);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$bdayandtel = new StdClass();
			
			$bdayandtel->id = $id;
			$bdayandtel->sunnipaev = $sunnipaev;
			$bdayandtel->telefon = $telefon;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $bdayandtel);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){
		$stmt = $this->connection->prepare("SELECT sunnipaev, telefon FROM bdayandtel WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($sunnipaev, $telefon);
		$stmt->execute();
		
		//tekitan objekti
		$bdayandtel = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$bdayandtel->sunnipaev = $sunnipaev;
			$bdayandtel->telefon = $telefon;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $bdayandtel;
		
	}
	function save($sunnipaev, $telefon) {
		
		$stmt = $this->connection->prepare("INSERT INTO bdayandtel (sunnipaev, telefon) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $sunnipaev, $telefon);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $sunnipaev, $telefon){
    	
		$stmt = $this->connection->prepare("UPDATE bdayandtel SET sunnipaev=?, telefon=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssi",$sunnipaev, $telefon, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>