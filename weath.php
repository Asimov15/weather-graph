<?php
/* David Zuccaro 27/05/2016 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>		
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />	
        <link rel='stylesheet' type='text/css' href='weather.css' />
		<title>Melbourne Temperature Graph</title>           
		<script type="text/javascript">
			function setop(opt)
			{
				var element = document.getElementById("date");
				element.value = opt;
			}
		</script>		
	</head>	 
		<?php 
			function RandomString()
			{
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randstring = '';
				for ($i = 0; $i < 10; $i++) 
				{
					$randstring = $randstring . $characters[rand(0, strlen($characters) - 1)];
				};

				return $randstring;
			};			
			$outfn = RandomString() . ".png";
			$today = date_create("now");
			$date1 = "";
			if (isset($_GET["submit"])) 
			{
				$date1 = $_GET['date'];
			}

			if (strlen($date1) == 0)
				$date1 = $today->format("dmY");
				
			$wdates = array();
			$datei = new DateInterval("P" . 0 . "D");
			$wdates[0] = clone $today;			
			for ($x = 1; $x <= 3; $x++)
			{				
				$wdates[$x] = clone $today->sub(new DateInterval("P" . "1" ."D"));
			}			

			exec('/usr/bin/python weath.py ' . $outfn . ' ' . $date1, $maxtemp); 				
			echo("<body onload=\"setop(" . "'" . $date1 . "'" . ")\">\n");
			echo("<div id='header'>");
			echo("<h1 class='dz'>Melbourne Temperature Graph</h1>\n");
		 	echo("</div>");
			echo("<form action='weath.php' method='get'>\n");			
				echo("<div class='validate'>\n");
					echo("<div id='wrapper'>
							<div id='outer1'>");
							echo("<div class='maxtemp'>Maximum Temperature: </div>\n");
							echo("<div class='mintemp'>Minimum Temperature: </div>\n");
						echo("</div>");
						echo("<div id='outer2'>");
							echo("<div class='maxtempd'>" . $maxtemp[0] . "</div>\n");
							echo("<div class='mintempd'>" . $maxtemp[1] . "</div>\n");
						echo("</div> 
						<div id='outer3'>");
							echo("<select id ='date' name='date'>\n");
							for ($x = 3; $x >= 0; $x--)
							{
								$td  = $wdates[$x]->format("d") . $wdates[$x]->format("m") . $wdates[$x]->format("Y");
								$td2 = $wdates[$x]->format("d") . "/" . $wdates[$x]->format("m") . "/" . $wdates[$x]->format("Y");
								echo("<option value='" . $td . "'>" . $td2 . "</option>");
							}
							echo("	</select> 
						</div> 
						<div id='outer4'>
							<input type='submit' value='Submit' name='submit'/> 
						</div> 
					</div><!-- end #wrapper -->			
					<div id='footer'>
					</div>
				</div>
			</form>
			<div class='dz'>");
			echo("<img class='dz' alt='Weather Graph For " . $date1 . "' src='images/" . $outfn . "'/>");				
			echo("</div>");
			echo("
		<div class='validate2'>
			<!--<a href='http://jigsaw.w3.org/css-validator/'>
				<img style='border:0;width:88px;height:31px' src='http://jigsaw.w3.org/css-validator/images/vcss' alt='Valid CSS!'/>
			</a> -->		  
			<a href='http://validator.w3.org/check?uri=referer'>
				<img src='http://www.w3.org/Icons/valid-xhtml10' alt='Valid XHTML 1.0 Strict' height='31' width='88' />
			</a>
		  
  
		</div>					
	
	</body>
</html>");

?>
