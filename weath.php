<?php
/* David Zuccaro 27/05/2016 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>		
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />	
        <link rel='stylesheet' type='text/css' href='weather.css' />
		<title>Melbourne Temperature Graph</title>           
		<script>
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
					$randstring .= $characters[rand(0, strlen($characters))];
				};
				/*echo($randstring);*/
				return $randstring;
			};			
			$outfn = RandomString() . ".png";
			$date1 = $_GET['date'];
			$today = date("dmY");
			if (strlen($date1) == 0)
				$date1 = $today;
			exec('/usr/bin/python weath.py ' . $outfn . ' ' . $date1); 				
			echo("<body onload=\"setop(" . "'$date1'" . ")\">\n");

			echo("<h1 class='dz'>Melbourne Temperature Graph</h1>\n");
		 	
			echo("<form action='weath.php' method='get'>\n");
			echo("<div class='validate'>\n");
			echo("<select id ='date' name='date'>\n");
			for ($x = 3; $x >= 0; $x--)
			{
				$td  = strval(intval(substr($today,0,2))-$x) . substr($today,2,2) . substr($today,4,4);
				$td2 = strval(intval(substr($today,0,2))-$x) . "/" . substr($today,2,2) . "/" . substr($today,4,4);
				echo("<option value='" . $td . "'>" . $td2 . "</option>");
			}
			echo("	</select> 
			<input type='submit' value='Submit'> 
			</div>
			</form>
			<div class='dz'>");
			echo("<img class='dz' src='images/" . $outfn . "'>");				
			echo("</div>");
		?>	
		<div class="validate2">
			<a href="http://jigsaw.w3.org/css-validator/">
				<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"/>
			</a>	
						
			<a href="http://validator.w3.org/check?uri=referer">
				<img src="http://www.w3.org/Icons/valid-xhtml11" alt="Valid XHTML 1.1" height="31" width="88" />
			</a>
		</div>					
	
	</body>
</html>
