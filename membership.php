<?php 
$title='BLO Membership Form';
include_once('header.php');
echo "<h2>$title</h2>";

#These are the emails the membership form gets sent to
#$emails = "reillysj@yahoo.com, megan@meganswoboda.com, unruhlee@yahoo.com, jeffgiaquinto@gmail.com, iflewakite@gmail.com, geofffreely@gmail.com";
$emails = "blomemberships@googlegroups.com";
$debug=0;

if ($_REQUEST['submit']) {
	if ($debug) {
		$emails = "greg@primate.net, blomemberships@googlegroups.com";
	}
	$descriptions = array(
		'name'=>"Name",
		'email'=>"Email",
		'phone'=>"Phone",
		'instrument'=>"Instrument/Role",
		'howlong'=>"Experience with instrument",
		'groups_played'=>"Groups they've played with",
		'heard'=>"How they heard about BLO",
		'unity'=>"Points of Unity response",
		'why'=>"Why they want to join",
		'projects'=>"Projects they've been involved with",
		'about'=>"About them"
	);

	$createdEvent = "";
	
	$error = "";
	$body = "";
	foreach (array_keys($_REQUEST) as $key) { 
		if ($descriptions[$key]) { 
			$body .= strtoupper($descriptions[$key]).": $_REQUEST[$key]\n\n";
		}
	}
	$title = "New Membership Request: $_REQUEST[name]"; 
	#mail($emails, $title, $body, "From: BLO-bot <brassliberation@gmail.com>\r\n"); 
	mail($emails, $title, $body); 
 
	print "<div id='response'>Thank you! We will get back to you within 10 days about next steps.</div>";

}
?>
<script src="js/validation.js" type="text/javascript"></script>
<script language="javascript">
    Protoplasm.use('datepicker').transform('input.datepicker');
    Protoplasm.use('timepicker').transform('input.timepicker');
    Event.observe(window, 'load', function() { 
    	valid = new Validation('gig_request'); 
    });
</script>
If you see us on the street, we are always open to folks coming up and playing with us.  If you want to get more involved, please fill out the form below. <br/> B.L.O. is open to all musical levels. Please <a href="http://www.brassliberation.org/pou.php" target="_new">check out our politics</a> and look over some of our <a href='http://brassliberation.org/events.php#pastactions' target='_new'>past actions</a> to see if you think we would be a good fit.


<p>Please briefly answer this questionnaire (no need for essays), so we can learn a little bit about you and how you might fit the band's current needs:</p>
<form class='gig_request' id='gig_request' method='post'>
<div class='question'><label for='name'>Name</label><input type='text' class="required" name="name" /></div>

<div class='question'><label for='email'>Email</label><input type='text' class='validate-email required' name='email' /></div>
<div class='question'><label for='phone'>Phone</label><input type='text' class='required' name='phone' /></div>

<div class='question'><label for='instrument'>Instrument(s) you play, or performance role you would like to play with BLO (could be dance, flag, tactical support, chanter, something else to blow our minds?!)</label><input type='text' class='required' name='instrument' /></div>

<div class='question'><label for='howlong'>How long you have played your instrument(s), and/or how recently you have picked it back up? (or your experience with your chosen performance role)</label><textarea class='required' name='howlong'></textarea></div>

<div class='question'><label for='groups_played'>What kind of groups/situations have you played/performed in? (ie high school band, busking on my own...)</label><textarea class='required' name='groups_played'></textarea></div>

<div class='question'><label for='heard'>How did you hear about BLO?</label><textarea class='required' name='heard'></textarea></div>

<div class='question'><label for='unity'>Please read the <a href="http://www.brassliberation.org/pou.php" target="_new">BLO Points of Unity</a>.  Do you feel aligned with these political points? (yes/no answer is fine)</label><textarea class='required' name='unity'></textarea></div>

<div class='question'><label for='why'>Why are you interested in joining BLO?</label><textarea class='required' name='why'></textarea></div>

<div class='question'><label for='projects'>What kind of political or community organizing projects have you been involved in?</label><textarea class='required' name='projects'></textarea></div>

<div class='question'><label for='about'>Please tell us a little bit about yourself, however you would like to describe yourself.</label><textarea class='required' name='about'></textarea></div>
<input autocomplete="off"  name='submit' type='submit' value='Submit Membership Form'>

<?php include_once('footer.php'); ?>
