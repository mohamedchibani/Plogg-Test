

<?php

    //Le nombre des jours entren les deux dates séléctionnées (sans prendre en compte le jour 6 et 7 "weekend")
	$total = $_POST["total"];
	$baseline = $_POST["baseline"];
	$start = strtotime($_POST["start_date"]);
	$end = strtotime($_POST["end_date"]);
	$num_days = 0;
	$rand_number = 0;
	$total_rand_numbers = 0;
	$weekday_array = array();
	$normalised_array = array();	
	$final_array = array();
	$j = 0;
	
	while(date('Y-m-d', $start) <= date('Y-m-d', $end)){

	  if(date('N', $start) < 6){
	  	 ++$num_days;
	  }
	  //Ajouter un jour
	  $start = strtotime("+1 day", $start);
	}

	//Calculer Baseline
	$min_in_days = floatval($total)*floatval($baseline)/(floatval($num_days)*100.0);
	$amount_dist = floatval(100-$baseline) *floatval($total)/100.0;

	
	//normaliser le tableau
	for ($i= 0 ; $i < $num_days ; $i++) {
		$rand_number = rand(1,9999);
		$total_rand_numbers += $rand_number;
		array_push($weekday_array , $rand_number);
	}
	$total_rand_numbers = floatval($total_rand_numbers);
	foreach ( $weekday_array as $day ) {	
		array_push( $normalised_array, floatval($day) / $total_rand_numbers);		
	}

	//Tableau final
	$start_t = strtotime($_POST["start_date"]);
	$end_t = strtotime($_POST["end_date"]);
	while ($start_t <= $end_t) {		
		if (date("N", $start_t) < 6){

			$formatted_amount = number_format(($normalised_array[$j] * $amount_dist + $min_in_days), 2,'.','');
 			$j++;
		} else {
			$formatted_amount = "0.00";	
		}
		$curr_date = date('Y-m-d',$start_t);

		//Construire le tableau finale
		$final_array += [$curr_date => $formatted_amount];	
		$start_t = strtotime("+1 day", $start_t);
	}

echo "Total:" .$_POST["total"]. "<br>";
echo "Nombre des weekdays:" .$num_days."<br>";
$total_distributed_values = 0;

foreach ($final_array  as $key => $value){
	echo $key."=>".$value. "<br>";
	$total_distributed_values += $value;
}
echo "<br>Total des valeurs distribuées ".$total_distributed_values;



?> 
