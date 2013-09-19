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

//Lending Type:
//IBD - IBRD
//IDB - Blend
//IDX - IDA
//LNX - not classified

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
	public $topicdata = array();
	public $listtopic = array();

	// booleans that tell you rather data is ready from a tread
	public $ready_couintries; 
	public $ready_topics;
	public $ready_data;

	//list of public variables//
	public $datapopulation= array();
	public $dataiso2code= array();
	public $datagdp= array();
	public $datagni= array();
	public $datainflation= array();
	public $datareserves= array();
	public $dataimports= array();
	public $dataexports= array();
	public $databudget= array();
	public $datacapitalcity=array();

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
		$this->datapopulation['country']="a";
		$this->datapopulation['value']="a";
		$this->datacapitalcity['country']="a";
		$this->datacapitalcity['value']="a";
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
			case "andorra":
				$c1="ad";
				break;
			case "united arab emirates":
				$c1="ae";
				break;
			case "afghanistan":
				$c1="af";
				break;
			case "antigua and barbuda":
				$c1="ag";
				break;
			case "anguilla":
				$c1="ai";
				break;
			case "albania":
				$c1="al";
				break;
			case "armenia":
				$c1="am";
				break;
			case "angola":
				$c1="ao";
				break;
			case "antarctica":
				$c1="aq";
				break;
			case "argentina":
				$c1="ar";
				break;
			case "american samoa":
				$c1="as";
				break;
			case "austria":
				$c1="at";
				break;
			case "australia":
				$c1="au";
				break;
			case "aruba":
				$c1="aw";
				break;
			case "aland island":
				$c1="ax";
				break;
			case "azerbaijan":
				$c1="az";
				break;
			case "bosnia and herzegovina":
				$c1="ba";
				break;
			case "barbados":
				$c1="bb";
				break;
			case "bangladesh":
				$c1="bd";
				break;
			case "belgium":
				$c1="be";
				break;
			case "burkina faso":
				$c1="bf";
				break;
			case "bulgaria":
				$c1="bg";
				break;
			case "bahrain":
				$c1="bh";
				break;
			case "burundi":
				$c1="bi";
				break;
			case "benin":
				$c1="bj";
				break;
			case "saint barthelemy":
				$c1="bl";
				break;
			case "bermuda":
				$c1="bm";
				break;
			case "brunei darussalam":
				$c1="bn";
				break;
			case "bolivia":
				$c1="bo";
				break;
			case "bonaire":
				$c1="bq";
				break;
			case "brazil":
				$c1="br";
				break;
			case "bahamas":
				$c1="bs";
				break;
			case "bhutan":
				$c1="bt";
				break;
			case "bouvet island":
				$c1="bv";
				break;
			case "botswana":
				$c1="bw";
				break;
			case "belarus":
				$c1="by";
				break;
			case "belize":
				$c1="bz";
				break;
			case "canada":
				$c1="ca";
				break;
			case "cocos islands":
				$c1="cc";
				break;
			case "congo":
				$c1="cd";
				break;
			case "central african republic":
				$c1="cf";
				break;
			case "switzerland":
				$c1="ch";
				break;
			case "cote d'lvoire":
				$c1="ci";
				break;
			case "cook islands":
				$c1="ck";
				break;
			case "chile":
				$c1="cl";
				break;
			case "cameroon":
				$c1="cm";
				break;
			case "china":
				$c1="cn";
				break;
			case "colombia":
				$c1="co";
				break;
			case "costa rica":
				$c1="cr";
				break;
			case "cuba":
				$c1="cu";
				break;
			case "cape verde":
				$c1="cv";
				break;
			case "curacao":
				$c1="cw";
				break;
			case "christmas island":
				$c1="cx";
				break;
			case "cyprus":
				$c1="cy";
				break;
			case "czech republic":
				$c1="cz";
				break;
			case "germany":
				$c1="de";
				break;
			case "djibouti":
				$c1="dj";
				break;
			case "denmark":
				$c1="dk";
				break;
			case "dominica":
				$c1="dm";
				break;
			case "dominican republic":
				$c1="do";
				break;
			case "algeria":
				$c1="dz";
				break;
			case "ecuador":
				$c1="ec";
				break;
			case "estonia":
				$c1="ee";
				break;
			case "egypt":
				$c1="eg";
				break;
			case "western sahara":
				$c1="eh";
				break;
			case "eritrea":
				$c1="er";
				break;
			case "spain":
				$c1="es";
				break;
			case "ethiopia":
				$c1="et";
				break;
			case "finland":
				$c1="fi";
				break;
			case "fiji":
				$c1="fj";
				break;
			case "falkland island":
				$c1="fk";
				break;
			case "micronesia":
				$c1="fm";
				break;
			case "faroe islands":
				$c1="fo";
				break;
			case "france":
				$c1="fr";
				break;
			case "gabon":
				$c1="ga";
				break;
			case "united kingdom":
				$c1="gb";
				break;
			case "grenada":
				$c1="gd";
				break;
			case "georgia":
				$c1="ge";
				break;
			case "french guiana":
				$c1="gf";
				break;
			case "guemsey":
				$c1="gg";
				break;
			case "ghana":
				$c1="gh";
				break;
			case "gibraltar":
				$c1="gi";
				break;
			case "greenland":
				$c1="gl";
				break;
			case "gambia":
				$c1="gm";
				break;
			case "guinea":
				$c1="gn";
				break;
			case "guadeloupe":
				$c1="gp";
				break;
			case "equatorial guinea":
				$c1="gq";
				break;
			case "greece":
				$c1="gr";
				break;
			case "south georgia":
				$c1="gs";
				break;
			case "guatemala":
				$c1="gt";
				break;
			case "guam":
				$c1="gu";
				break;
			case "guinea-bissau":
				$c1="gw";
				break;
			case "guyana":
				$c1="gy";
				break;
			case "hong kong":
				$c1="hk";
				break;
			case "heard island":
				$c1="hm";
				break;
			case "honduras":
				$c1="hn";
				break;
			case "croatia":
				$c1="hr";
				break;
			case "haiti":
				$c1="ht";
				break;
			case "hungary":
				$c1="hu";
				break;
			case "indonesia":
				$c1="id";
				break;
			case "ireland":
				$c1="ie";
				break;
			case "israel":
				$c1="il";
				break;
			case "isle of man":
				$c1="im";
				break;
			case "india":
				$c1="in";
				break;
			case "iraq":
				$c1="iq";
				break;
			case "iran":
				$c1="ir";
				break;
			case "iceland":
				$c1="is";
				break;
			case "italy":
				$c1="it";
				break;
			case "jamaica":
				$c1="jm";
				break;
			case "jordan":
				$c1="jo";
				break;
			case "japan":
				$c1="jp";
				break;
			case "kenya":
				$c1="ke";
				break;
			case "kyrgyzstan":
				$c1="kg";
				break;
			case "cambodia":
				$c1="kh";
				break;
			case "kiribati":
				$c1="ki";
				break;
			case "comoros":
				$c1="km";
				break;
			case "saint kitts and nevis":
				$c1="kn";
				break;
			case "north korea":
				$c1="kp";
				break;
			case "south korea":
				$c1="kr";
				break;
			case "kuwait":
				$c1="kw";
				break;
			case "cayman islands":
				$c1="ky";
				break;
			case "kazakhstan":
				$c1="kz";
				break;
			case "lao":
				$c1="la";
				break;
			case "lebanon":
				$c1="lb";
				break;
			case "saint lucia":
				$c1="lc";
				break;
			case "liechtenstein":
				$c1="li";
				break;
			case "sri lanka":
				$c1="lk";
				break;
			case "liberia":
				$c1="lr";
				break;
			case "lesotho":
				$c1="ls";
				break;
			case "lithuania":
				$c1="lt";
				break;
			case "luxembourg":
				$c1="lu";
				break;
			case "latvia":
				$c1="lv";
				break;
			case "libya":
				$c1="ly";
				break;
			case "morocco":
				$c1="ma";
				break;
			case "monaco":
				$c1="mc";
				break;
			case "moldova":
				$c1="md";
				break;
			case "montenegro":
				$c1="me";
				break;
			case "madagascar":
				$c1="mg";
				break;
			case "marshall islands":
				$c1="mh";
				break;
			case "macedonia":
				$c1="mk";
				break;
			case "mali":
				$c1="ml";
				break;
			case "myanmar":
				$c1="mm";
				break;
			case "mongolia":
				$c1="mn";
				break;
			case "macao":
				$c1="mo";
				break;
			case "martinique":
				$c1="mq";
				break;
			case "mauritania":
				$c1="mr";
				break;
			case "montserrat":
				$c1="ms";
				break;
			case "malta":
				$c1="mt";
				break;
			case "mauritius":
				$c1="mu";
				break;
			case "maldives":
				$c1="mv";
				break;
			case "malawi":
				$c1="nw";
				break;
			case "mexico":
				$c1="mx";
				break;
			case "malaysia":
				$c1="my";
				break;
			case "mozambique":
				$c1="mz";
				break;
			case "namibia":
				$c1="na";
				break;
			case "new caledonia":
				$c1="nc";
				break;
			case "niger":
				$c1="ne";
				break;
			case "norfolk island":
				$c1="nf";
				break;
			case "nigeria":
				$c1="ng";
				break;
			case "nicaragua":
				$c1="ni";
				break;
			case "netherlands":
				$c1="nl";
				break;
			case "norway":
				$c1="no";
				break;
			case "nepel":
				$c1="np";
				break;
			case "nauru":
				$c1="nr";
				break;
			case "niue":
				$c1="nu";
				break;
			case "new zealand":
				$c1="nz";
				break;
			case "oman":
				$c1="om";
				break;
			case "panama":
				$c1="pa";
				break;
			case "peru":
				$c1="pe";
				break;
			case "french polynesia":
				$c1="pf";
				break;
			case "papua new guinea":
				$c1="pg";
				break;
			case "philippines":
				$c1="ph";
				break;
			case "pakistan":
				$c1="pk";
				break;
			case "poland":
				$c1="pl";
				break;
			case "pitcairn":
				$c1="pn";
				break;
			case "puerto rico":
				$c1="pr";
				break;
			case "palestine":
				$c1="ps";
				break;
			case "portugal":
				$c1="pt";
				break;
			case "palau":
				$c1="pw";
				break;
			case "paraguay":
				$c1="py";
				break;
			case "qatar":
				$c1="qa";
				break;
			case "reunion":
				$c1="re";
				break;
			case "romania":
				$c1="ro";
				break;
			case "serbia":
				$c1="rs";
				break;
			case "russian":
				$c1="ru";
				break;
			case "rwanda":
				$c1="rw";
				break;
			case "saudi arabia":
				$c1="sa";
				break;
			case "solomon islands":
				$c1="sb";
				break;
			case "seychelles":
				$c1="sc";
				break;
			case "sudan":
				$c1="sd";
				break;
			case "sweden":
				$c1="se";
				break;
			case "singapore":
				$c1="sg";
				break;
			case "slovakia":
				$c1="si";
				break;
			case "slovakia":
				$c1="sk";
				break;
			case "sierra leone":
				$c1="sl";
				break;
			case "san marino":
				$c1="sm";
				break;
			case "senegal":
				$c1="sn";
				break;
			case "somalia":
				$c1="so";
				break;
			case "suriname":
				$c1="sr";
				break;
			case "el salvador":
				$c1="sv";
				break;
			case "syria":
				$c1="sy";
				break;
			case "swaziland":
				$c1="sz";
				break;
			case "chad":
				$c1="td";
				break;
			case "togo":
				$c1="tg";
				break;
			case "thailand":
				$c1="th";
				break;
			case "tajikistan":
				$c1="tj";
				break;
			case "tokelau":
				$c1="tk";
				break;
			case "timor-leste":
				$c1="tl";
				break;
			case "turkmenistan":
				$c1="tm";
				break;
			case "tunisia":
				$c1="tn";
				break;
			case "tonga":
				$c1="to";
				break;
			case "turkey":
				$c1="tr";
				break;
			case "trinidad":
				$c1="tt";
				break;
			case "tuvalu":
				$c1="tv";
				break;
			case "taiwan":
				$c1="tw";
				break;
			case "tanzania":
				$c1="tz";
				break;
			case "ukraine":
				$c1="ua";
				break;
			case "uganda":
				$c1="ug";
				break;
			case "united states":
				$c1="us";
				break;
			case "uruguay":
				$c1="uy";
				break;
			case "uzbekistan":
				$c1="uz";
				break;
			case "venezuela":
				$c1="ve";
				break;
			case "viet nam":
				$c1="vn";
				break;
			case "vanuatu":
				$c1="vu";
				break;
			case "samoa":
				$c1="ws";
				break;
			case "yemen":
				$c1="ye";
				break;
			case "mayotte":
				$c1="yt";
				break;
			case "south africa":
				$c1="za";
				break;
			case "zambia":
				$c1="zm";
				break;
			case "zimbabwe":
				$c1="zw";
				break;
				
		}
		return $c1;
	}

	public function getcapitalcity($country){
		if ($this->datacapitalcity['country']==$country){
			return $this->datacapitalcity['value'];
		}
		elseif ($this->countriesdata["iso2code"]==$country){
			return $this->countriesdata["capitalcity"];
		}
		else{
			$r1=$this->getcountryinfo($country);
			if ($r1!=0){
				return $this->countriesdata["capitalcity"];
			}
			else{
				return 0;
			}
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

	public function _getcountryinfo($result){
		$arr1=json_decode($result,true);
		$result1=$arr1[1];
		//var_dump($result1);
		foreach($result1 as $item){
			$this->getcountrydata($item);
		}
		return $this->countriesdata;
	}

	public function getindicatorinfo($indicator){
		//check indicator
		$str1="http://api.worldbank.org/indicators/$indicator?format=json";
		$arr1=$this->sendcommand($str1);
		$data1=$arr1[1];
		foreach($data1 as $item){
				$count1=0;
				foreach($item as $item1){
					if ($count1==3){
						$value1=$item1;
						return $value1;
					}
					//echo "item - $item1";
					$count1++;
				}
			}
		return 0;
	}

	public function getindicators(){
		//http://api.worldbank.org/indicators
	}

	public function getcountrybylending($lending){
		$lending = strtolower($lending);
		if ($lending=="ibd" || $lending=="idb" || $lending=="idx" || $lending=="lnx"){
			$str1="http://api.worldbank.org/countries?format=json&lendingType=".$lending;
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

	public function gettopicdata($item){
		$value = $item['value'];
		$note = $item['sourceNote'];
		$this->topicdata["value"]=$value;
		$this->topicdata["note"]=$note;
		return;
	}
		
	public function gettopics(){
		$str1="http://api.worldbank.org/topics/?format=json";
		$arr1=$this->sendcommand($str1);
			$data1=$arr1[1];
			//var_dump($arr1);
			foreach($data1 as $item) {
				$this->gettopicdata($item);    				
				$this->listtopics[]=$this->topicdata;
			}
			return 1;
	}

	public function gettopicinfo($topic){
		//http://api.worldbank.org/topic/5/indicator
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

	public function getpopulationx($country,$startyear,$endyear){

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

	private function commandresult($resarray){
		$command=$resarray["command"];
		$result=$resarray["result"];
		//var_dump($result);
		switch($command){
			case "getcountryinfo":
				$cdata=$this->_getcountryinfo($result);
				$r1["command"]=$command;
				$r1["result"]=$cdata;
				return $r1;
				break;
		}
	}

	private function addcommand($command,$param){
		switch($command){
			case "getcountryinfo":
				$c1=$param[0];
				$str1="/countries/$c1";
				$arr1['command']=$command;
				$arr1['url']=$str1;
				return $arr1;
				break;
			}
	}

	private function setmultcommands($command){
		$param['format']='json';
		$conn1 = new http('api.worldbank.org', 80,"http",false);
		$conn1->get($command, $param);
		return $conn1;
	}

	public function sendmultcommands($commandarray){
		//send in a list of commands to get data (ie getiso2code)
		//stores the result in an array of the following format
		//{command1 => result1, command2 => result2 ...etc }
		$connm = new http(null,null,null,true);
		foreach ($commandarray as $value){
			$arr1=$this->addcommand($value['command'],$value['param']);
			$conn1=$this->setmultcommands($arr1["url"]);
			$connm->add($conn1);
		}
    		$out=$connm->run();
		$c1=0;
		foreach ($commandarray as $value1){
			$a1[]=$value1["command"];
			//echo "command-$value1['command']";
		}
		foreach ($out as $value){
			$d1=$a1[$c1];
			//echo "command=$d1<br>";
			//var_dump($value);
			//echo "<br>";
			$result1["command"]=$d1;
			$result1["result"]=$value;
			$r1=$this->commandresult($result1);
			$res[]=$r1;
			$c1++;
		}
		//print_r($out);
		return $res;
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

function dolistcountry($item){
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
	return;
}

function dolisttopics($wb){
	$wb->gettopics();
	foreach($wb->listtopics as $item){
		$value=$item['value'];
		$note=$item['note'];
		echo "topic subject - $value<br>";
		echo "topic description - $note<br>";
		echo "---------------------------------<br>";
	}
}



///////////////////////testing main/////////////////////////////
require_once('http.php');
echo "<h1>test worldbank functions</h1>";
$wb= new worldbankapi;
////List all the countries with high income level////////
	echo "--------------listcountries-------------------<br>";
	//$r1=$wb->countrieswithincome('HIC');
	//dolistcountries($wb);
	echo "--------------getiso2code------------<br>";
	//$str1=$wb->getiso2code("brazil");
	//echo "The iso2code for Brazil is $str1<br>";
	echo "--------------getcountryGDP------------<br>";
	//$str1=$wb->getgdp("brazil", "2002:2002");
	//echo "The GDP for Brazil in 2002 is $str1<br>";
	echo "--------------getcapitalcity------------<br>";
	//$str1=$wb->getcapitalcity("brazil");
	//echo "The capital city of Brazil is $str1<br>";
	echo "--------------getgni--------------------<br>";
	//$str1=$wb->getgni("brazil", "2002:2002");
	//echo "The GNI for Brazil in 2002 is $str1<br>";
	echo "--------------getpopulation-------------<br>";
	//$str1=$wb->getpopulation("brazil", "2002:2002");
	//echo "The population for Brazil in 2002 is $str1<br>";
	echo "--------------getsavings-------------<br>";
	//$str1=$wb->getsavings("brazil", "2002:2002");
	//echo "The savings for Brazil in 2002 is $str1<br>";
	echo "--------------getinflation-------------<br>";
	//$str1=$wb->getinflation("brazil", "2002:2002");
	//echo "The inflation for Brazil in 2002 is $str1<br>";
	echo "--------------getimports-------------<br>";
	//$str1=$wb->getimports("brazil", "2002:2002");
	//echo "The imports for Brazil in 2002 is $str1<br>";
	echo "--------------getexports---------------<br>";
	//$str1=$wb->getexports("brazil", "2002:2002");
	//echo "The exports for Brazil in 2002 is $str1<br>";
	echo "--------------getreserves---------------<br>";
	//$str1=$wb->getreserves("brazil", "2002:2002");
	//echo "The reserves for Brazil in 2002 is $str1<br>";
	echo "--------------getbudget---------------<br>";
	//$str1=$wb->getbudget("brazil", "2002:2002");
	//echo "The budget for Brazil in 2002 is $str1<br>";
	echo "--------------gettopics-------------------<br>";
	//dolisttopics($wb)
	echo "-------------getindicatorinfo------------<br>";
	//$d1=$wb->getindicatorinfo($wb::gni);
	//echo "GNI - $d1";
	echo "-------------getcountrybylending----------<br>";
	//$r1=$wb->getcountrybylending("IDB");
	//dolistcountries($wb);
	echo "-------------sendmultcommands-------------<br>";
	$param1[]="br";
	$arr1["command"]="getcountryinfo";
	$arr1["param"]=$param1;
	$commandsarr[]=$arr1;
	$param2[]="bg";
	$arr2["command"]="getcountryinfo";
	$arr2["param"]=$param2;
	$commandsarr[]=$arr2;
	//$commandsarr[]="/countries/BG";
	$res=$wb->sendmultcommands($commandsarr);
	foreach ($res as $value){
		echo $value["command"]."<br>";
		dolistcountry($value["result"]);
	}
?>
