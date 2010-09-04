<?php

class BBGuestbookPrinter{
	function fillPage($content = ''){
		
		$tableHtml = "<div id='bbguestbook'>";
		$tableHtml .= "<table border='0'>";
		for ($i = 0; $i < 1; $i++){
			$dato = "31.06.2010";
			$navn = "Kenneth";
			$mail = NULL;
			//$mail = "kenneth@stigen.net";
			$spiller = "Trompett";
			$spilleri = "Jaren";
			$melding = "Dette er en fin test for a se om det blir en skravlebok av dette her...Det hadde jo vart moro!kasdjhfkj asdfkj asdkfjh askjh kasjdhf kjahsdf kjahsdkf jhaksd fkjhsadk fjhksa djf ";
			$tableHtml = $tableHtml . "<tr><td width='150'><span class='date'>" . $dato . "</span><br/><b>Navn: </b>" . $navn;

			if(isset($mail)){$tableHtml .= "<br/><b>E-post: </b>" . $mail;}
			if(isset($spiller)){$tableHtml .= "<br/><b>Spiller: </b>" . $spiller;}
			if(isset($spilleri)){$tableHtml .= "<br/><b>Spiller i: </b>" . $spilleri;}
						
			$tableHtml = $tableHtml . "</td><td valign='top' class='message'>" . $melding . "</td></tr>";
		}
		$tableHtml .= "</table>";
		$tableHtml .= "</div>";

		$content .= $tableHtml;
		return $content;
	}
}

?>