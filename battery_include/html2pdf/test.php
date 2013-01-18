<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
     ob_start();
     $msg = "http://html2pdf.fr/";
     
?>
<page>
<table style="margin-left: 7.5mm; margin-top: 8.5mm; margin-bottom: 0mm;">
<?
$count = 32;
$row = 0;
$column = 0;
for ($i = 1; $i <= $count; $i++) {
	if ($column == 0) {
	   echo "<tr>";
	}
	?>
	<td style="width: 48.45mm; height: 30.75mm; padding: 0; margin: 0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<qrcode value="<?php echo $msg; ?>" ec="M" style="border: none; width: 20mm;"></qrcode><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Battery 1</td>
	<?
	$column++;
	if ($i == $count) {
		?>
		</tr>
		</table>
		</page>
		<?
	}
	else if ($column == 4) {
		echo "</tr>";
		$column = 0;
		$row++;
		if ($row%8 == 0) {
			?>
			</table>
			</page>
			<page>
			<table border=1 style="margin-left: 8mm; margin-top: 8mm; margin-bottom: 0mm;">
			<?
		}
	}
}

     $content = ob_get_clean();

    // convert to PDF
    require_once('html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'USFORMAT', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('qrcode.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>