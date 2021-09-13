<?php

namespace Snlbaral\NepaliDate;

class NepaliDate {

    private $format;
    private $convert;
    private $eng_date;
    private $nepali_length;
    private $firstday_en;
	private $start_ne;
	private $end_ne;
	private $month_name;
	private $day_name;
	private $nep_date;
	private $year;
	private $month;
	private $days;
	private $dayname;

    function __construct($format="l, M d, Y", $convert=true) {
        $this->format = $format;
        $this->convert = $convert;
        $this->checkDate();
    }

    private function checkDate() {
        try {
            if(!DateTime::createFromFormat($this->format, date($this->format))) {
            	http_response_code(400);	
                throw new Exception("Given Date Format is Invalid.");
            }
            if(!file_exists("nepali_length.txt")) {
				http_response_code(404);
				throw new Exception("nepali_length.txt file not found");
			}
            $this->eng_date = date($this->format);
            $this->nepali_length = json_decode(file_get_contents("nepali_length.txt"), true);
            $this->parseDate();
        } catch(\Throwable $e) {
			throw new Exception($e->getMessage());
        } catch(\Exception $e) {
			throw new Exception($e->getMessage());
        }        
    }

    private function parseDate() {
    	$this->firstday_en ="1918-04-13";
		$this->start_ne = "1975";
		$this->end_ne = "2095";
		$this->month_name = array('ब‌ैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज', 'कार्तिक', 'मङ्सिर', 'पुस', 'माघ', 'फाल्गुण', 'चैत');
		$this->day_name = array('अाईतबार', 'सोमबार', 'मङ्गलबार', 'बुधबार', 'बिहिबार', 'शुक्रबार', 'शनिवार');

		$date_parse = date_parse(date("Y-m-d"));
		$year = $date_parse['year'];
		$month = $date_parse['month'];
		$day = $date_parse['day'];

		$date = $year.'-'.$month.'-'.$day;
	 	$jd = GregorianToJD($month, $day, $year);
	 	$this->dayname =  $this->day_name[JDDayOfWeek($jd,0)];
		$date_start = date_create($this->firstday_en);
		$date_today = date_create($date);
		$diff = date_diff($date_start,$date_today, true);
		$days = $diff->format("%a");
		$this->processDate($days);
    }

    private function processDate($days) {
		$arr = '0';
		for ($i=$this->start_ne; $i<$this->end_ne; $i++) 
		{
			$arr+=array_sum($this->nepali_length[$i]);

			if ($arr>$days) 
			{
				$this->year = $i;
				
				$count_previous=$arr-array_sum($this->nepali_length[$i]);
				$year_previous = $i-1;
				for ($j=0; $j < 12; $j++) 
				{
					$count_previous+= $this->nepali_length[$i][$j];
					if($count_previous>$days)
					{
						$this->month = $j+1;
						$daysss = $count_previous-$days;
						$this->days = ($this->nepali_length[$i][$j]-$daysss)+1;
						break;
					} elseif ($count_previous==$days)
					{
						$this->year = $i;
						$this->month = $j+1;
						$day = 1;
					}
				}
				break;
			} elseif($arr==$days)
			{
				$this->year = $i+1;
				$this->month = 1;
				$day = 1;
			}
		}
		$this->formatDate();
    }


    private function formatDate() {
    	$this->nep_date = $this->eng_date;
		if(strpos($this->format, "Y")!==false) {
			$this->nep_date = str_replace(date("Y"), $this->year, $this->nep_date);
		}
		if(strpos($this->format, "m")!==false) {
			$this->nep_date = str_replace(date("m"), $this->month, $this->nep_date);
		}
		if(strpos($this->format, "M")!==false) {
			$this->nep_date = str_replace(date("M"), $this->month_name[$this->month-1], $this->nep_date);
		}
		if(strpos($this->format, "d")!==false) {
			$this->nep_date = str_replace(date("d"), $this->days, $this->nep_date);
		}
		if(strpos($this->format, "l")!==false) {
			$this->nep_date = str_replace(date("l"), $this->dayname, $this->nep_date);
		}
		if($this->convert) {
			$this->convertDigits();
		}
    }

    private function convertDigits() {
    	$nepali_numbers = ["o","१","२","३","४","५","६","७","८","९"];
    	for($i=0;$i<10;$i++) {
    		$this->nep_date = str_replace($i, $nepali_numbers[$i], $this->nep_date);
    	}
    }

    public function __toString()
    {
    	return $this->nep_date;
    }



}
?>