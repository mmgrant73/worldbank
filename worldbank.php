<?php
class worldbankapi{

//add caching

//Can get a list of values in array form, visualize it in a table or a graph

//http://climatedataapi.worldbank.org/climateweb/rest/v1/country/type/var/start/end/ISO3[.ext]

//type is one of:
//	mavg			Monthly average
//	annualavg		Annual average
//	manom			Average monthly change (anomaly).  
//	annualanom		Average annual change (anomaly).

//var is one of:
	//pr			Precipitation (rainfall and assumed water equivalent), in millimeters
	//tas			Temperature, in degrees Celsius


//Past
//start 	end
//1920		1939
//1940		1959
//1960		1979
//1980		1999

//Future
//start 	end
//2020		2039
//2040		2059
//2060		2079
//2080		2099

//stat is one of:

//tmin_means		Average daily minimum temperature degrees Celsius
//tmax_means		Average daily maximum temperature degrees Celsius
//tmax_days90th		Number of days with maximum temperature above the control period’s 90th percentile (hot days) days
//tmin_days90th		Number of days with minimum  temperature above the control period’s 90th percentile (warm nights)
//tmax_days10th		Number of days with maximum temperature below the control period’s 10th percentile (cool days) days
//tmin_days10th		Number of days with minimum  temperature below the control period’s 10th percentile (cold nights)
//tmin_days0		Number of days with minimum  temperature below 0 degrees Celsius days
//ppt_days		Number of days with precipitation greater than 0.2 mm days
//ppt_days2		Number of days with precipitation greater than 2 mm days
//ppt_days10		Number of days with precipitation greater than 10 mm days
//ppt_days90th		Number of days with precipitation greater than the control period's 90th percentile days
//ppt_dryspell		Average number of days between precipitation events days
//ppt_means		Average daily precipitation mm

//Region codes

  //  EAP - (developing only)
  //  EAS - (all income levels)
  //  ECA - (developing only)
  //  ECS - (all income levels)
  //  LAC - (developing only)
  //  LCN - (all income levels)
  //  MNA - (developing only)
  //  MEA - (all income levels)
  //  NAC
  //  SAS
  //  SSA - (developing only)
  //  SSF - (all income levels)

//Income level codes

 //   NOC
 //   OEC
 //   HIC
 //   HPC
 //   LIC
 //   LMC
 //   LMY
 //   MIC
 //   UMC

//Error Code 	Response Code 	Description
//105 	503 'Service currently unavailable' 	'The requested service is temporarily unavailable.'
//110 	404 'API Version "XXX" not found.' 	'The requested API version was not found.'
//111 	404 'Format "XXX" not found.' 	'The requested response format was not found.'
//112 	404 'Method "XXX" not found.' 	'The requested method was not found.'
//115 	404 'Missing required parameter' 	'Parameters which are required have not been sent.'
//120 	404 'Parameter "XXX" has an invalid value.' 	'The provided parameter value is not valid.'
//140 	400 'Endpoint “XXX” not found.’ 	'The requested endpoint was not found'
//150 	400 'Language with ISO2 code: "XX" is not yet supported in the API' 	'Response requested in an unsupported language.'
//160 	400 ' Filtering data-set on an indicator value without indicating a date range is meaningless and is not allowed.' 	'You need to indicate date-range if you want to filter by an indicator value.'
//199 	500 'Unexpected error' 	'An unexpected error was encountered while processing the request.'

 

//income//
	const high_income = "HIC";
	const heavily_indebted_poor = "HPC";
	const low_income = "LIC";
	const lower_middle_income = "LMC";
	const low_and_middle_income = "LMY";
	const middle_income = "MIC";
	const high_nonoecd = "NOC"; 
	const high_oecd = "OEC";
	const upper_middle_income = "UMC";

//economics//
	const gdp="NY.GDP.MKTP.CD";
	const totalpopulation = "SP.POP.TOTL";
	const gni = "NY.GNP.PCAP.CD";
	const grosssavings = "NY.GNS.ICTR.ZS";
	const inflation = "FP.CPI.TOTL.ZG";
	const reserves = "FI.RES.TOTL.CD";
	const imports = "NE.IMP.GNFS.ZS";
	const budget = "GC.BAL.CASH.GD.ZS";
	const exports = "NE.EXP.GNFS.ZS";

	public $listcountries = array();
	public $countriesdata = array();

	function __construct() {
       		$this->countriesdata["country"]="aaa";
		$this->countriesdata["iso2code"]="aa";
		$this->countriesdata["name"]="name";
		$this->countriesdata["region"]="aaa";
		$this->countriesdata["adminregion"]="aaa";
		$this->countriesdata["incomelevel"]="aaa";
		$this->countriesdata["lendingtype"]="aaa";
		$this->countriesdata["capitalcity"]="city";
		$this->countriesdata["longitude"]=11.11111;
		$this->countriesdata["latitude"]=11.11111;
		
   	}

	public function sendcommand($command){
		//echo "command-$command<br>";
		$json1 = file_get_contents($command);
		//echo "json1=$json1<br>";
		//var_dump(json_decode($json1,true));
		$arr1=json_decode($json1,true);
		return $arr1;
	}

	public function getiso2code($country){
		$r1=$this->getcountryinfo($country);
		if ($r1!=0){
			return $this->countriesdata["iso2code"];
		}
		else{
			return 0;
		}
	}

	public function checkcountry($country){
		$c1=0;
		switch($country){
			case "brazil":
				$c1="br";
				break;
				
		}
		return $c1;
	}

	public function getcapitalcity($country){
		$r1=$this->getcountryinfo($country);
		if ($r1!=0){
			return $this->countriesdata["capitalcity"];
		}
		else{
			return 0;
		}
	}

	public function getcountryinfo($country){
		$r1=$this->checkcountry($country);
		if ($r1!=$this->countriesdata["iso2code"]){
			if ($r1!=""){
				$str1="http://api.worldbank.org/countries/br?format=json";
				$arr1=$this->sendcommand($str1);
				$data1=$arr1[1];
				foreach($data1 as $item){
					$this->getcountrydata($item);
				}
				//return $this->countriesdata;
			}	
			else{
				echo "error";
				return 0;
			}
		}
		return $this->countriesdata;
	}

	public function getindicators(){

	}

	public function getcountrybylending($lending){

	}
		
	public function gettopics(){

	}

	public function getbudget($country,$year1){
		$indicator=self::budget;
		$budget=$this->getindicator($country, $year1, $indicator);
		return $budget;
	}

	public function getreserves($country,$year1){
		$indicator=self::reserves;
		$reserves=$this->getindicator($country, $year1, $indicator);
		return $reserves;
	}

	public function getexports($country,$year1){
		$indicator=self::exports;
		$exports=$this->getindicator($country, $year1, $indicator);
		return $exports;
	}

	public function getimports($country,$year1){
		$indicator=self::imports;
		$imports=$this->getindicator($country, $year1, $indicator);
		return $imports;
	}

	public function getinflation($country,$year1){
		$indicator=self::inflation;
		$inflation=$this->getindicator($country, $year1, $indicator);
		return $inflation;
	}

	public function getsavings($country,$year1){
		$indicator=self::grosssavings;
		$savings=$this->getindicator($country, $year1, $indicator);
		return $savings;
	}

	public function getpopulation($country,$year1){
		$indicator=self::totalpopulation;
		$population=$this->getindicator($country, $year1, $indicator);
		return $population;
	}

	public function getgni($country,$year1){
		$indicator=self::gni;
		$gni=$this->getindicator($country, $year1, $indicator);
		return $gni;
	}

	public function getgdp($country, $year1){
		$indicator="NY.GDP.MKTP.CD";
		$gdp=$this->getindicator($country, $year1, $indicator);
		return $gdp;
	}

	public function getindicator($country, $year1, $indicator){
		$c1=$this->checkcountry($country);
		$y1=$this->checkyear($year1);
		if ($c1!=""){
			$str1="http://api.worldbank.org/countries/".$c1."/indicators/".$indicator."?format=json&date=".$y1;
			//echo "str1=$str1";
			$arr1=$this->sendcommand($str1);
			$data1=$arr1[1];
			foreach($data1 as $item){
				$count1=0;
				foreach($item as $item1){
					if ($count1==2){
						$value1=$item1;
						//echo "<br>arr2=$gdp1<br>";
						return $value1;
					}
					$count1++;
				}
			}
		}
		return 0;
	}

	public function checkyear($year1){
		
		return $year1;
	}

	public function getcountrydata($item){
		$id = $item['id']; 
		$iso2code = $item['iso2Code'];
		$name = $item['name'];
		$region = $item['region']['value'];
		$adminregion = $item['adminregion']['value'];
		$incomelevel1 = $item['incomeLevel']['value'];
		$lendingtype = $item['lendingType']['value'];
		$capitalcity = $item['capitalCity'];
		$longitude = $item['longitude'];
		$latitude = $item['latitude'];
		$this->countriesdata["country"]=$id;
		$this->countriesdata["iso2code"]=$iso2code;
		$this->countriesdata["name"]=$name;
		$this->countriesdata["region"]=$region;
		$this->countriesdata["adminregion"]=$adminregion;
		$this->countriesdata["incomelevel"]=$incomelevel1;
		$this->countriesdata["lendingtype"]=$lendingtype;
		$this->countriesdata["capitalcity"]=$capitalcity;
		$this->countriesdata["longitude"]=$longitude;
		$this->countriesdata["latitude"]=$latitude;
		return;
		//$this->listcountries[]=$this->countriesdata;
	}

	public function countrieswithincome($incomelevel){
		if ($incomelevel=="HIC" || $incomelevel=="HPC" || $incomelevel=="LIC" || $incomelevel=="LMC" || $incomelevel=="LMY" || $incomelevel=="MIC" || $incomelevel=="NOC" || $incomelevel=="OEC" || $incomelevel=="UMC"){
			$str1="http://api.worldbank.org/countries?format=json&incomeLevel=".$incomelevel;
			$arr1=$this->sendcommand($str1);
			$data1=$arr1[1];
			foreach($data1 as $item) {
				$this->getcountrydata($item);    				
				$this->listcountries[]=$this->countriesdata;
			}
			return 1;
		}
		else{
			return 0;
		}

	}

//2russoliver@gmail.com  
//russell oliver
//hilliard
//5/17/1975
//10/2/1995 keyla leigh oliver
//leah nicole oliver 7/14/2000
//willow brook oliver 12/9/11
	
}

function dolistcountries($wb){
	$wb->countrieswithincome('HIC');
	foreach($wb->listcountries as $item){
		$id=$item['country'];
		$iso2code=$item['iso2code'];
		$name=$item['name'];
		$region=$item['region'];
		$adminregion=$item['adminregion'];
		$incomelevel=$item['incomelevel'];
		$capitalcity=$item['capitalcity'];
		$longitude=$item['longitude'];
		$latitude=$item['latitude'];
		$lendingtype=$item['lendingtype'];
		echo "id=$id<br>";
		echo "iso2code=$iso2code<br>";
		echo "name=$name<br>";
		echo "region=$region<br>";
		echo "adminregion=$adminregion<br>";
		echo "incomelevel=$incomelevel<br>";
		echo "capitalcity=$capitalcity<br>";
		echo "longitude=$longitude<br>";
		echo "latitude=$latitude<br>";
		echo "lendingtype=$lendingtype<br><br>";
	}
	return;
}

echo "<h1>test worldbank functions</h1>";
$wb= new worldbankapi;
////List all the countries with high income level////////
	echo "--------------listcountries-------------------<br>";
	//dolistcountries($wb);
	echo "--------------getiso2code------------<br>";
	$str1=$wb->getiso2code("brazil");
	echo "The iso2code for Brazil is $str1<br>";
	echo "--------------getcountryGDP------------<br>";
	$str1=$wb->getgdp("brazil", "2002:2002");
	echo "The GDP for Brazil in 2002 is $str1<br>";
	echo "--------------getcapitalcity------------<br>";
	$str1=$wb->getcapitalcity("brazil");
	echo "The capital city of Brazil is $str1<br>";
	echo "--------------getgni--------------------<br>";
	$str1=$wb->getgni("brazil", "2002:2002");
	echo "The GNI for Brazil in 2002 is $str1<br>";
	echo "--------------getpopulation-------------<br>";
	$str1=$wb->getpopulation("brazil", "2002:2002");
	echo "The population for Brazil in 2002 is $str1<br>";

?>
