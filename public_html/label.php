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
require('../battery_static/database.inc');    
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();

if (count($_POST) > 0) {
	$total = count($_POST['answers']);
	$array = $_POST['answers'];
}

     ob_start();
     $qr_link = "http://".$_SERVER['SERVER_NAME']."/update_status.php?qr=1&item_id=";

?>
<page>
<table style="margin-left: 7.5mm; margin-top: 8.5mm; margin-bottom: 0mm;">
<?
$count = $total;
$row = 0;
$column = 0;
for ($i = 1; $i <= $count; $i++) {
	if ($column == 0) {
	   echo "<tr>";
	}
	$item_id = $array[$i - 1];
	$item_name = get_item_name($item_id, $member_db);
	?>
	<td style="width: 48.45mm; height: 30.75mm; padding: 0; margin: 0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<qrcode value="<?php echo $qr_link.$item_id; ?>" ec="M" style="border: none; width: 20mm;"></qrcode><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $item_name; ?></td>
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
			<table style="margin-left: 7.5mm; margin-top: 8.5mm; margin-bottom: 0mm;">
			<?
		}
	}
}

     $content = ob_get_clean();

    // convert to PDF
    require_once('../battery_include/html2pdf/html2pdf.class.php');
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