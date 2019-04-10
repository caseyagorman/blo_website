<?php
//function fetch_gigs($new=1, $calendarfeed="https://www.google.com/calendar/feeds/qirqup8q7vm9bots5invri0e6k@group.calendar.google.com/public/full?orderby=starttime&singleevents=true&ctz=America/Los_Angeles") {
function fetch_gigs($new=1, $calendarfeed="https://www.google.com/calendar/feeds/qirqup8q7vm9bots5invri0e6k@group.calendar.google.com/public/basic?orderby=starttime&singleevents=true&ctz=America/Los_Angeles") {
    $dateformat="Y-m-d"; // 10 March 2009 - see http://www.php.net/date for details
    $nicedateformat="F j"; // 10 March 2009 - see http://www.php.net/date for details
    $timeformat="g:i A"; // 12.15am
    $cache_time = 60 * .5; // 5 minutes
    date_default_timezone_set('America/Los_Angeles');

    $cache_file = '';
    if ($new) {
        $calendarfeed .= "&futureevents=true&sortorder=a";
	$cache_file = '/home/blo/gcal.xml'; //xml file saved on server
    } else { $calendarfeed .= "&sortorder=d"; }

    if ($cache_file) {
        $timedif = @(time() - filemtime($cache_file));
        $xml = "";
        if (file_exists($cache_file) && $timedif < $cache_time) {
            $str = file_get_contents($cache_file);
            $xml = simplexml_load_string($str);
        } else {
            $xml = simplexml_load_file($calendarfeed); //come here
            if ($f = fopen($cache_file, 'w')) { //save info
                $str = $xml->asXML();
                fwrite ($f, $str, strlen($str));
                fclose($f);
            } else { echo "<P>Can't write to the cache.</P>"; }
        }
    } else { $xml = simplexml_load_file($calendarfeed); }

    $items_shown=0;
    $xml->asXML();
    $gigs = array();

    foreach ($xml->entry as $entry){

        $gig = array();
        $ns_gd = $entry->children('http://schemas.google.com/g/2005');

		$gig['id'] = preg_replace("/^.*\/full\/(.+)$/", "$1", $entry->id);
        //Do some niceness to the description
        //Make any URLs used in the description clickable: thanks Adam
        $gig['description'] = preg_replace('/([^\'"])((f|ht)tps?:\/\/[-a-zA-Z0-9@:%_\+.~#?,&\/=]+)/i','$1<a href="$2">$2</a>', $entry->content);

        // These are the dates we'll display
        $gig['gCalDate'] = date($dateformat, strtotime($ns_gd->when->attributes()->startTime));
        $gig['niceDate'] = date($nicedateformat, strtotime($ns_gd->when->attributes()->startTime));
        $gig['gCalDateStart'] = date($dateformat, strtotime($ns_gd->when->attributes()->startTime));
        $gig['gCalDateEnd'] = date($dateformat, strtotime($ns_gd->when->attributes()->endTime));
        $gig['gCalStartTime'] = date($timeformat, strtotime($ns_gd->when->attributes()->startTime));
        $gig['gCalEndTime'] = date($timeformat,strtotime($ns_gd->when->attributes()->endTime));
       
       /* I don't think we need to correct time zone?
        $gig['gCalDate'] = date($dateformat, strtotime($ns_gd->when->attributes()->startTime)+date("Z",strtotime($ns_gd->when->attributes()->startTime)));
        $gig['niceDate'] = date($nicedateformat, strtotime($ns_gd->when->attributes()->startTime)+date("Z",strtotime($ns_gd->when->attributes()->startTime)));
        $gig['gCalDateStart'] = date($dateformat, strtotime($ns_gd->when->attributes()->startTime)+date("Z",strtotime($ns_gd->when->attributes()->startTime)));
        $gig['gCalDateEnd'] = date($dateformat, strtotime($ns_gd->when->attributes()->endTime)+date("Z",strtotime($ns_gd->when->attributes()->endTime)));
        $gig['gCalStartTime'] = gmdate($timeformat, strtotime($ns_gd->when->attributes()->startTime)+date("Z",strtotime($ns_gd->when->attributes()->startTime)));
        $gig['gCalEndTime'] = gmdate($timeformat,strtotime($ns_gd->when->attributes()->endTime)+date("Z",strtotime($ns_gd->when->attributes()->endTime)));
        */
        $gig['title'] = $entry->title;
        $gig['where'] = $ns_gd->where->attributes()->valueString;
        $gig['map'] = "http://maps.google.com/?q=".urlencode($ns_gd->where->attributes()->valueString);
        // Accept and translate HTML
        foreach(array_keys($gig) as $key) {
            $gig[$key]=str_replace("&lt;","<",$gig[$key]);
            $gig[$key]=str_replace("&gt;",">",$gig[$key]);
            $gig[$key]=str_replace("&quot;","\"",$gig[$key]);
        }
        $gigs[] = $gig;
    }
    return $gigs;
}
?>

