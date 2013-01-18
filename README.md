www.batteryqr.com

Track and Eliminate dead two-way radio batteries with the use of QR codes.


To get up and running with this app, you'll need to:
<ol>
  <li>Install and run MAMP (www.mamp.info)</li>
  <li>Import the battery_include/batteryqr.sql schema file to your MySQL to create the database</li>
  <li>Replace the generic database handler credentials at battery_static/database.inc (you can simply use your root access login/password as specified in the MAMP settings)</li>
</ol>

Before my git days, I simply overwrote the 'battery_include' and 'batteryqr.com' directories on the server with my local dev copies when making changes.
