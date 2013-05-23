<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

include('../battery_include/battery_header.inc');
$member_db = member_db_connect();
?>


<h2>Getting Started:</h2>
<ul>
<li>Start adding batteries by clicking the 'add battery' link above, the battery name cannot be changed after the battery is created.  This is to prevent any confusion with the battery name printed out on the qr label.</li>
<li>Once you've added all the batteries in your repository, click the 'Print Qr' link and print out the labels.  You must have a laser printer and Avery 6576 labels to print.</li>
<li>Affix the sticker to the battery.  We recommend adhering to the side of the battery that will not be exposed.</li>
<li>For field use, create a system for marking bad batteries.  We recommend using a plastic bag system.  Marked batteries can then be pulled from the bag and qr coded before placing on the chargers with the rest of the dead batteries.</li>
<li>Mark batteries either manually on the web site or by visting the qr code url.  Confirmation will be provided for flagged batteries.</li>
<li>If a battery shows consistent history of poor performance, remove it from the field and mark on the website as removed.</li>
</ul>

<h2>QR Code Reader</h2>
<ul>
<li>For scanning and tagging many qr codes in a single sitting, you should consider the following in a QR app:</li>
	<ul>
		<li>built-in web browser</li>
		<li>reloads url page every time link is visited.</li>
		<li>doesn't ask first to load the page</li>
		<li><a href="https://play.google.com/store/apps/details?id=uk.tapmedia.qrreader&hl=en">QR Reader For Android</a> works well</li>
		<li><a href="http://itunes.apple.com/us/app/qr-journal/id483820530?mt=12">QR Journal</a> for Mac Desktop OS X works in a pinch</li>
		<li>Here is a desktop PC <a href="http://www.dansl.net/blog/2010/desktop-qr-code-reader/" >QR Reader</a> (requires adobe air)</li>
	</ul>
<li>There are a lot of good options for iOS devices.  But is important to note that qr code readers will not work for the iPhone 3G or earlier.</li>
<li>Don't forget, you can always just manually flag the battery from this web site.  Run a search for the battery name, click the link, and you will see an option to manually flag the battery.</li>
</ul>

<h2>Additional Info</h2>
<ul>
<li>Once flagged, a battery cannot be flagged again for a 72 hour period</li>
<li>Accidental flagging of batteries can be undone by clicking the undo link on the mobile confirmation page, or by accessing the main site.</li>
<li>Once a battery is deleted/"marked for removal", the battery will no longer appear in the repository</li>
<li>Even if deleted, battery names cannot be re-used</li>
</ul>
<?

include('../battery_include/battery_footer.inc');

?>