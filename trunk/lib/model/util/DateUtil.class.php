<?php

class DateUtil {

	/**
	* Returns the diference between two dates expressed in the given interval
	*     
 	* @param $interval can be:
 	* yyyy - Number of full years
 	* q - Number of full quarters
 	* m - Number of full months
 	* y - Difference between day numbers
 	* (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
 	* d - Number of full days
 	* w - Number of full weekdays
	*  ww - Number of full weeks
 	* h - Number of full hours
 	* n - Number of full minutes
 	* s - Number of full seconds (default)
 	*/
	public static function diff($interval, $datefrom, $dateto, $using_timestamps = false) {
 		if (!$using_timestamps) {
  			$datefrom = strtotime($datefrom, 0);
			$dateto = strtotime($dateto, 0);
  		}
  		$difference = $dateto - $datefrom; // Difference in seconds
  
  		switch($interval) {
  
  			case 'yyyy': // Number of full years
  
  				$years_difference = floor($difference / 31536000);
  				if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
  					$years_difference--;
  				}
  				if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
  					$years_difference++;
  				}
  				$datediff = $years_difference;
  				break;
  
  		 case "q": // Number of full quarters
  
  			$quarters_difference = floor($difference / 8035200);
  			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
  				$months_difference++;
  			}
  			$quarters_difference--;
  			$datediff = $quarters_difference;
  			break;
   
  		case "m": // Number of full months
  
  			$months_difference = floor($difference / 2678400);
  			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
  				$months_difference++;
  			}
  			$months_difference--;
  			$datediff = $months_difference;
  			break;
   
  		case 'y': // Difference between day numbers

			$datediff = date("z", $dateto) - date("z", $datefrom);
  			break;
   
  		case "d": // Number of full days
   
  			$datediff = floor($difference / 86400);
  			break;
  
  		case "w": // Number of full weekdays
   
  			$days_difference = floor($difference / 86400);
  			$weeks_difference = floor($days_difference / 7); // Complete weeks
  			$first_day = date("w", $datefrom);
  			$days_remainder = floor($days_difference % 7);
  			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
  			if ($odd_days > 7) { // Sunday
  				$days_remainder--;
  			}
			if ($odd_days > 6) { // Saturday
				$days_remainder--;
  			}
  			$datediff = ($weeks_difference * 5) + $days_remainder;
  			break;
    
  			case "ww": // Number of full weeks
   
  				$datediff = floor($difference / 604800);
  				break;
  
  			case "h": // Number of full hours
  
  				$datediff = floor($difference / 3600);
  				break;
   
  			case "n": // Number of full minutes
   
  				$datediff = floor($difference / 60);
  				break;
  
 			default: // Number of full seconds (default)
  
 				$datediff = $difference;
 				break;
 		}
 
 		return $datediff;
 
 	}
 
 	
 	/**
 	 * Adds an interval of time to a time variable.
 	 *
 	 * @param string $interval
 	 * Inteval can be:
 	 * "yyyy": Years
 	 * "m": Months
 	 * "ww": Weeks
 	 * "h": Hours
 	 * "n": Minutes
 	 * "s": Seconds
 	 * @param $number
 	 * Is the number of intervals to be added
 	 * @param integer $date
 	 * An integer containing the time
 	 * @return integer (time)
 	 */
	public static function add($interval, $number, $date) {

	    $date_time_array = getdate($date);
	    $hours = $date_time_array['hours'];
	    $minutes = $date_time_array['minutes'];
	    $seconds = $date_time_array['seconds'];
	    $month = $date_time_array['mon'];
	    $day = $date_time_array['mday'];
	    $year = $date_time_array['year'];
	
	    switch ($interval) {
	    
	        case 'yyyy':
	            $year+=$number;
	            break;
	        case 'q':
	            $year+=($number*3);
	            break;
	        case 'm':
	            $month+=$number;
	            break;
	        case 'y':
	        case 'd':
	        case 'w':
	            $day+=$number;
	            break;
	        case 'ww':
	            $day+=($number*7);
	            break;
	        case 'h':
	            $hours+=$number;
	            break;
	        case 'n':
	            $minutes+=$number;
	            break;
	        case 's':
	            $seconds+=$number;
	            break;            
	    }
	       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
	    return $timestamp;
	}


	/**
	 * Format a PHP time to the mime string format for timestamps.
	 *
	 * @param long $date
	 * @return string
	 */
	public static function formatAsTimestamp($date) {
		return date("Y-m-d H:i:s", $date);
	}

	/**
	 * Format a timestamp integer to the standar
	 * mine timestamp format.
	 *
	 * @param integer $timestamp
	 * @return string
	 */
	public static function makePHPTimeFromTimestamp($timestamp) {
		if (strlen($timestamp) < 19) {
			return;
		}
		$year = substr($timestamp, 0, 4);
		$month = substr($timestamp, 5, 2);
		$day = substr($timestamp, 8, 2);
		$hour = substr($timestamp, 11, 2);
		$min = substr($timestamp, 14, 2);
		$sec = substr($timestamp, 17);
		
		return mktime($hour, $min, $sec, $month, $day, $year);
		
	}
	
	/**
	 * devuelve la fecha formateada con barras
	 * recibe YYYY-MM-DD HH:MM:SS
	 * devuelve MM/DD/YYYY
	 *
	 * @param string $timestamp
	 */
	public static function formatToBarDate($timestamp) {
		
		$dateAux = substr($timestamp,0,10);
		$date = explode("-", $dateAux);
		return $date[1]."/".$date[2]."/".$date[0];
	}
//TODO refactorizar para que la de arriba reciba un parametro con el idioma
	
	/**
	 * devuelve la fecha formateada con barras
	 * recibe YYYY-MM-DD HH:MM:SS
	 * devuelve DD/MM/YYYY
	 *
	 * @param string $timestamp
	 */
	public static function formatToBarDateSpanish($timestamp) {
		
		$dateAux = substr($timestamp,0,10);
		$date = explode("-", $dateAux);
		return $date[2]."/".$date[1]."/".$date[0];
	}
	
	/**
	 * devuelve la fecha formateada con barras con la hora
	 * recibe YYYY-MM-DD HH:MM:SS
	 * devuelve MM/DD/YYYY HH:MM
	 *
	 * @param string $timestamp
	 */
	public static function formatToBarDateWithHour($timestamp) {
		
		$dateAux = substr($timestamp,0,16);
		$dateHour = explode(" ", $dateAux);
		$date = explode("-", $dateHour[0]);
		return $date[2]."/".$date[1]."/".$date[0]." ".$dateHour[1];
	}
	
	/**
	 * devuelve la fecha formateada como string
	 * recibe YYYY-MM-DD HH:MM:SS
	 * devuelve DDMMYYYY
	 *
	 * @param string $timestamp
	 */
	public static function formatToString($timestamp) {
		
		$dateAux = explode(" ", $timestamp);
		$dateAux = $dateAux[0];
		$date = explode("-", $dateAux);
		return $date[2].$date[1].$date[0];
	}
	
	/**
	 * recibe un tat en horas y devuelve la cantidad de dias
	 * correspondiente redondeando siempre para arriba
	 * @param tat string 
	 * @return int
	 */
	 
	public static function tatToDay($tat) {
		return ceil($tat / 24);
	}
	
	/**
	 * Agrega la cantidad de dias a la fecha actual
	 * @param $days int
	 * 
	 * @return date timestamp
	 */
	public static function addDaysToday($days) {
		$timestamp = DateUtil::formatAsTimestamp(time());
		$date = explode(" ", $timestamp);
		
		$today = explode("-", $date[0]);
		$year = $today[0];
		$month = $today[1];
		$day = $today[2];
		
		return date("Y-m-d", (mktime(0, 0, 0, $month, $day + $days, $year)));
	}
	
}

?>