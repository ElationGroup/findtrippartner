<?php

/**
 * 
 * Service class that exact generates dates based on the passed schedule 
 * 
 *  
 */
class Service_DateGenerator {
	
	private $startDate;
	private $repeatTimeUnit;
	private $repeatFrequency;
	private $repeatDays;
	private $dates;


    
    public function setStartDate($startDate)
    {
    	$this->startDate = $startDate;
    }
    
    public function setRepeatTimeUnit($repeatTimeUnit)
    {
    	$this->repeatTimeUnit = $repeatTimeUnit;
    }
    
    public function setRepeatFrequency($repeatFrequency)
    {
    	$this->repeatFrequency = $repeatFrequency;
    }
    
    
    public function setRepeatDays($repeatDays)
    {
    	if(is_array($repeatDays))
    	{
    		$this->repeatDays = implode(",", $repeatDays);
    	}
    	else
    	{
    		$this->repeatDays = $repeatDays;
    	}
    	
    }
    
    public function getDates()
    {
    	return $this->dates;
    }
    
    public function generateDates()
    {

    	$startDate			= $this->startDate;
    	$repeatTimeUnit		= $this->repeatTimeUnit;
    	$repeatFrequency	= $this->repeatFrequency;
    	$repeatDays 		= array(); 
    	
    	
    	//dates generated for 1 year..
    	
    	$maxDays = 365;

    	
    	$this->dates = array();
    	if($repeatTimeUnit == 'daily')
    	{
    		$lastDate = strtotime($startDate);
    	
    		$diff = 0;
    	
    		while($diff <= $maxDays)	//for 1 year..
    		{
    			$totalSeconds = (24 * 3600) * $repeatFrequency;
    			$publishingDate =  $lastDate + $totalSeconds; // $repeatFrequency;
    			$formattedLastDate = date('Y-m-d', $lastDate);
    			array_push($this->dates, $formattedLastDate);
    			$lastDate = $publishingDate;
    				
    			$datetime1 = new DateTime($startDate);
    			$datetime2 = new DateTime($formattedLastDate);
    			$interval = $datetime1->diff($datetime2, true);
    			$diff = (int) $interval->format('%a');
    		}
    	
    	
    	}
    	elseif($repeatTimeUnit == 'weekly')
    	{
    		//get days of the week selected by user
    		
    		
    		$repeatDays = array_map('trim', explode(',', $this->repeatDays));
    			
    			
    		//get startdates for each day selected for the week.
    			
    		$dayStartDates = array();
    		while($day = array_shift($repeatDays))
    		{
    			$wd = 0;
    			$lastDate = strtotime($startDate);
    			while($wd<7)
    			{
    					
    				$dayName = strtolower(date('D',$lastDate));
    					
    				if($dayName == $day)
    				{
    					//echo $dayName .'='. date('Y-m-d', $lastDate) ."<br>";
    					$dayStartDates[$dayName] = date('Y-m-d', $lastDate);
    					break;
    				}
    				$lastDate = $lastDate + (3600*24);
    				$wd++;
    			}
    		}
    			
    			
    		//generate dates for each day from their respective start date.
    		$dayDates = array();
    		foreach($dayStartDates as $dayName => $dayStartDate)
    		{
    			$dayDates[$dayName]= array();		//will store the dates day wise
    			$lastDate = strtotime($dayStartDate);
    			$diff = 0;
    	
    			while($diff <= $maxDays)	//for 1 year..
    			{
    				$totalSeconds = (24 * 3600) * ($repeatFrequency * 7); // - 7 days in a week
    				$publishingDate =  $lastDate + $totalSeconds;
    				$formattedLastDate = date('Y-m-d', $lastDate);
    				array_push($dayDates[$dayName], $formattedLastDate);
    				$lastDate = $publishingDate;
    	
    				$datetime1 = new DateTime($startDate);
    				$datetime2 = new DateTime($formattedLastDate);
    				$interval = $datetime1->diff($datetime2, true);
    				$diff = (int) $interval->format('%a');
    			}
    		}
    			
    		//merge all day wise dates to single date array.
    		$this->dates = array();
    		foreach($dayDates as $dates)
    		{
    			$this->dates = array_merge($this->dates, $dates);
    		}
    		sort($this->dates);
    	}
    	
    	/* echo "<pre>";
    	print_r($this->dates);
    	echo "</pre>";
    	die(); */
    	
    	return $this->dates;
    }


}

?>
