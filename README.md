worldbank
=========

A wrapper class for getting information for the World Bank API using REST. 

The World Bank website holds a wealth of information that one can access using REST calls.  For example, one can get a countries
GDP, total population, capital city and much more information.  A person communicates to the website by using the WOrld Bank API
which is done using the REST protocol.  The resulting dataset will be transmitted back in XML or json format.  One does not need
to understand or worry about the communication because the this wrapper library does it for you behind the scene.  One will just
need to call a user-friendly function call to get the desire dataset.  Eventaully, a graphic display through the use of charts will
be added by using Google Charts.  THe goal of this class is to enable a developer to easily get data from the World Bank website.

Usage
-----
To use this library one must include the worldbank.php at the beginning of your php script.  Intializes the class and call the 
appropriate functions.

```
<?php

include "worldbank.php";

echo "<h1>worldbank functions</h1>";
$wb= new worldbankapi;
	echo "<br>--------------getiso2code------------<br>";
	$str1=$wb->getiso2code("brazil");
	echo "The iso2code for Brazil is $str1<br>";
	echo "<br>--------------getcountryGDP------------<br>";
	$str1=$wb->getgdp("brazil", "2002:2002");
	echo "The GDP for Brazil in 2002 is $str1<br>";
	echo "<br>--------------getcapitalcity------------<br>";
	$str1=$wb->getcapitalcity("brazil");
	echo "The capital city of Brazil is $str1<br>";


?>
```
The output of this script will produce the following output:

```
worldbank functions

--------------getiso2code------------
The iso2code for Brazil is BR

--------------getcountryGDP------------
The GDP for Brazil in 2002 is 504221228974.035

--------------getcapitalcity------------
The capital city of Brazil is Brasilia
```
Note:  This library is in the alpha stage of development and thus, there will be bugs.  If you find any please feel free to let me know so I can fix them.
Also, all function is not implement as of yet but will be added as I have the time to work on this project.

Roadmap for version 1.1.0
-------------------------
* Will add support for Asynchronous communication.  At the moment it only uses synchronous communication.
* Will add graphical support using google charts API
