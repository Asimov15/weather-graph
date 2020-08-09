<?php
/* David Zuccaro 27/05/2016                     */
/* David Zuccaro 07/05/2018 : Formatting Issues */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>		
        <meta http-equiv='Content-Type' content='text/html;charset=utf-8'/>
        <meta http-equiv='refresh' content='900'/>
        <link rel='stylesheet' type='text/css' href='weather.css'/>
		<title>Melbourne Temperature Graph</title>           
		<script type='text/javascript'>
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
        $today_cp1 = clone $today;
        $time = date('H:i');
        $jd = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
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
			
        exec('/usr/bin/python weath.py -f' . $outfn . ' -d' . $date1 . ' 2> /tmp/error.txt', $maxtemp); 
        echo("<body onload=\"setop(" . "'" . $date1 . "'" . ")\">\n");
        echo("    <div id='header'>\n");
        echo("        <h1>Geelong Temperature Graph</h1>\n");
        echo("    </div>\n");
        echo("    <form action='index.php' method='get'>\n");
        echo("        <div class='dates'>\n");
        echo("            <div id='wrapper'>\n");
        echo("                <div id='outer1'>\n");
        echo("                    <span class='temp'>Maximum Temperature: </span>\n");
        echo("                    <span class='tempd'>" . $maxtemp[0] . "</span>\n");
        echo("                    <br/>\n");
        echo("                    <span class='temp'>Minimum Temperature: </span>\n");
        echo("                    <span class='tempd'>" . $maxtemp[1] . "</span>\n");
        echo("                    <br/>");
        echo("                    <span class='temp'>Current Temperature: </span>\n");
        echo("                    <span class='tempd'>" . $maxtemp[2] . "</span>\n");
        echo("                </div><!-- end #outer1 -->\n");
        echo("                <div id='outer2'>\n");
        echo("                <select id ='date' name='date'>\n");
        for ($x = 3; $x >= 0; $x--)
        {
            $td  = $wdates[$x]->format("d") . $wdates[$x]->format("m") . $wdates[$x]->format("Y");
            $td2 = $wdates[$x]->format("d") . "/" . $wdates[$x]->format("m") . "/" . $wdates[$x]->format("Y");
            echo("                <option value='" . $td . "'>" . $td2 . "</option>\n");
        }
        echo("                </select>\n"); 							
        echo("                </div><!-- end #outer2 -->\n"); 
        echo("                <div id='outer3'>\n");
        echo("                    <input type='submit' value='Draw' name='submit'/>\n");
        echo("                </div><!-- end #outer3 -->\n"); 
        echo("                <div id='outer4'>\n");
        echo("                <span class='temp'>Current Day:</span>\n");
        echo("                <span class='tempd'>" . jddayofweek($jd,1) . "</span>\n");
        echo("                    <br/>\n");    
        echo("                <span class='temp'>Current Date:</span>\n");    
        echo("                <span class='tempd'>" . $today_cp1->format("d/m/Y") . "</span>\n");    
        echo("                    <br/>\n");
        echo("                <span class='temp'>Current Time:</span>\n");    
        echo("                <span class='tempd'>" . $time . "</span>\n");    
        echo("                </div><!-- end #outer4 -->\n"); 
        echo("            </div><!-- end #wrapper -->\n");			
        echo("            <div id='footer'>\n");
        echo("            </div>\n");
        echo("        </div>\n");
        echo("    </form>\n");
        echo("    <div class='graph'>\n");
        echo("        <img class='dz' alt='Weather Graph For " . $date1 . "' src='images/" . $outfn . "'/>\n");				
        echo("    </div>\n");
        echo("    <div class='validate2'>\n");       
		echo("    	  <a href='http://jigsaw.w3.org/css-validator/check/referer'><img style='border:0;width:89px;height:31px' src='http://jigsaw.w3.org/css-validator/images/vcss' alt='Valid CSS!' /></a>\n");
        echo("	   	  <a href='http://validator.w3.org/check?uri=referer'><img src='http://www.w3.org/Icons/valid-xhtml10' alt='Valid XHTML 1.0 Strict' height='31' width='89' /></a>\n");       
        echo("    </div>\n");       
        echo("</body>\n");       
        echo("</html>\n");
    ?>
