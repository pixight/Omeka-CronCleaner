<?php 

//be careful, this plugin clean the coverage field with our proper specifications (example : multiple city are separated by # or ¤, and we kepp only the first and suppress the others)

$elementToClean = "Coverage"; // here the dublin core field name that contains the address of the item
$element_set_id = 1; //the element set id for the Dublin core field address
$increment = 100; //no more than 100 requests to limit CPU and Memory usage

include_once('db-connexion.php');
$result = $mysqli->query("SELECT * FROM `omeka_options` WHERE `name` LIKE 'cron_lastnum'");

//on récup le dernier
if($result){
	$row = $result->fetch_assoc();
	$cron_lastnum = intval($row['value']);


	$result = $mysqli->query("SELECT COUNT(id) as compte FROM `omeka_element_texts` WHERE `element_id` = (SELECT `id` FROM `omeka_elements` WHERE `name` LIKE '".$elementToClean."' AND `element_set_id` = ".$element_set_id.")");
	if($result){
		$row = $result->fetch_assoc();
		$count = $row['compte'];
		
		if($cron_lastnum > $count){
			$cron_lastnum = 0;
		}

		$result = $mysqli->query("SELECT * FROM `omeka_element_texts` WHERE `element_id` = 
			(SELECT id FROM `omeka_elements` WHERE name LIKE '".$elementToClean."' AND `element_set_id` = ".$element_set_id.")
			ORDER BY  `omeka_element_texts`.`id` ASC
			LIMIT ".$cron_lastnum.",".$increment);
		if($result){
			while($row = $result->fetch_assoc()){

				$text = $row['text'];
				$tabdiese = explode('#',$text); //on traite les adresse text ## text ## etc ...
				$text = $tabdiese[0]; //on ne garde que la première

				$tabsigle = explode('¤',$text); //on traite les adresse text ¤ text ¤ etc ...
				$text = $tabsigle[0]; //on ne garde que la première


				$patterns = '/\](.*)\[/i';
				$replacement = ' $1 ';
				$text = preg_replace($patterns, $replacement,$text); //traitement des crochets multiples
				$text = str_replace("[","",$text); //traitement des crochets ouvrant
				$text = str_replace("]","",$text); //traitement des crochets fermant

				$patterns = array('/s\.l\./i','/s\.n\./i','/s\.i\./i');
				$replacement = array('','');
				$text = preg_replace($patterns,$replacement,$text);

				$text = ucfirst($text);

				$text = $mysqli->real_escape_string($text);

				echo ("UPDATE `omeka_element_texts` SET  `text`='".$text."' WHERE `id`=".$row['id']."\n\n");
				//$result = $mysqli->query("UPDATE omeka_element_texts SET text='".$text."'");
				echo $row['id'].' -> '.$text.'\n\n\n';

				$result2 = $mysqli->query("UPDATE `omeka_element_texts` SET `text`='".$text."' WHERE `id`=".$row['id']);
				//echo $row['id']. ' '. $row['text'].'\n';
			}
			//une fois fini les 50, on passera au 50 suivant
			$cron_lastnum += $increment;
			$result = $mysqli->query("UPDATE `omeka_options` SET `value`=".$cron_lastnum." WHERE `name` LIKE 'cron_lastnum'");
		}
	}
}
$mysqli->close();
?>