<?php
    ini_set("display_errors",1);
	ob_start();
 	include(dirname(__FILE__).'/base2.php');
	$content = ob_get_clean();

	// conversion HTML => PDF
	require_once(dirname(__FILE__).'/html2pdf.class.php');

	try
	{
		$html2pdf = new HTML2PDF('P', 'A4', 'fr', false, 'ISO-8859-15');
		$html2pdf->pdf->SetDisplayMode('fullpage');
//		$html2pdf->pdf->SetProtection(array('print'), 'spipu');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('exemple07.pdf');
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	
?>