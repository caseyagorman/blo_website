<?php $title = 'BLO - Calendar'; ?>
<?php include('header.php'); ?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type='text/javascript'>
	function showGigs(show) {
		var d = new Date();
		var years = document.getElementsByClassName('one-gig');
		var navs = document.getElementsByClassName('year-nav-item')
		for (i = 0; i < years.length; i++) {
			var table = years[i];
			var nav = navs[i]
			if (!nav || !table) { continue; }

			if (i == show) {
				if(table) { table.style.display='block'; }
				nav.classList.add('selected');
			} else {
				if (table) { table.style.display='none'; }
				nav.classList.remove('selected');
			}
		}
	}
	google.setOnLoadCallback(function() { showGigs(0); });
</script>
<?php
$c = mysqli_connect( 'db.electricembers.net', 'blo', 'WredPyagri') or die('Could not connect: ' . mysqli_error($c));
mysqli_select_db($c,'blo') or die('Could not select database');
  mysqli_set_charset($c,'utf8');
$gigs_res = mysqli_query('%M %D',"select date_format(date) as nicedate, time_format(start_time, '%l:%i %p') as start_time, time_format(end_time, '%l:%i %p') as end_time, title, public_description, location from gigs where approved = 1 and public_description != '' and  deleted = 0 and date > DATE_SUB(NOW(), INTERVAL 1 DAY) order by date");
?>
	  <h2>Calendar</h2>
Want to keep up on our gigs? <a href='https://www.google.com/calendar/b/0/render?cid=https://www.google.com/calendar/feeds/qirqup8q7vm9bots5invri0e6k@group.calendar.google.com/public/basic&gsessionid=OK'>Subscribe</a> to our Google Calendar!
<?php
print "<ul id='gigslist'>";
while($gig = mysqli_fetch_assoc($gigs_res)) {
	print"\n\t<li class='cal_entry'>
		<span class='date'>$gig[nicedate]</span>
		<span class='title'>$gig[title]</span>
		<span class='details'>$gig[public_description]</span>
		<span class='time'>Time: $gig[start_time] - $gig[end_time]</span>";
	if ($gig['location']) {	
    	print "<span class='location'>Starting Location: $gig[location]</span>";
	}	
	print "</li>";
}
print "</ul>";


//$years_result = mysql_query("select distinct year(date) from gigs");
$query = "select date_format(date, '%c/%e/%Y'), title, year(date) from gigs where date < NOW() and approved=1 and deleted= 0 order by date desc";
$res = mysqli_query($c,$query);
$years = array();
$curr_year = "";
// need to extract public description here in second SQL query
if ( $res ) {
	while ( $row = mysqli_fetch_row( $res ) ) {
		$date = $row[0];
		$name = $row[1];
		$year = $row[2];
		$name = preg_replace('/\&/', '&amp;', $name);
		$years[$year] = 1;
		if ($year != $curr_year) {
			if ($curr_year) { $oldgigs[] = "\n</table>\n";}
			$curr_year = $year;
			$oldgigs[] = "\n<table id='gigs_$curr_year' class='one-gig' style='text-align: left; margin-left: auto; margin-right: auto;' border='0'>";
		}
		$oldgigs[] = "\n\t<tr><td class='date'>$date</td> <td>$name</td></tr>";
	}
	mysqli_free_result( $res );
}
mysqli_close( $c );
//arsort($years);
?>

<a name='pastactions'></a>
 <h3>Past Actions</h3>
 <p>A list of some past events that BLO played at (Also see <a href="links.html">links</a> page for links to many of the organizations and groups that we have worked with).</p>

<div class="events">
<div id='yearchooser' style='text-align: center'>
<?php
foreach(array_keys($years) as $index=>$year) { echo "<div id='nav_$year' class='year-nav-item' onclick='showGigs($index);'>$year</div>"; }
?>
</div>
<div class="gigs">
<?php
foreach( $oldgigs as $gig) {
	echo $gig;
}
?>
</table>
<br style='clear:both'/>
</div>
</div>
<!--
<span class='date'>Oct 26, 2005</span><img src="images/SEIU BLO 10-05.jpg" width="396" height="296" alt="" />
<span class='date'>Jan 20, 2005</span><img src="images/coffeeshop.jpg" alt="picture" style="width: 400px; height: 273px;" />
<span class='date'>March 20, 2003</span><img style="width: 339px; height: 232px;" alt="march 20, 2003" src="images/blo.jpg" />
<span class='date'>Jan 17, 2003</span><img style="width: 428px; height: 290px;" alt="blo at Cesar Chavez parade" src="images/cesarchavez2" />
-->
<?php include('footer.php'); ?>
