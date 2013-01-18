<page>
<table border=1 style="margin-left: 7.5mm; margin-top: 7.5mm; margin-bottom: 0mm;">
<?php
$count = 32;
$row = 0;
$column = 0;
for ($i = 1; $i <= $count; $i++) {
	if ($column == 0) {
	   echo "<tr>";
	}
	?>
	<td style="width: 49.45mm; height: 31.75mm; padding: 0; margin: 0;">&nbsp;&nbsp;&nbsp;BatteryQR &nbsp;&nbsp; Battery 1</td>
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
?>