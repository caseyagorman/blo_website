<!--
var weekday=new Array(7);
weekday[0]="Sunday";
weekday[1]="Monday";
weekday[2]="Tuesday";
weekday[3]="Wednesday";
weekday[4]="Thursday";
weekday[5]="Friday";
weekday[6]="Saturday";

var month=new Array(12);
month[0]="January";
month[1]="February";
month[2]="March";
month[3]="April";
month[4]="May";
month[5]="June";
month[6]="July";
month[7]="August";
month[8]="September";
month[9]="October";
month[10]="November";
month[11]="December";

/* Loads the Google data JavaScript client library */
google.load("gdata", "2.x");

function init() {
  // init the Google data JS client library with an error handler
  google.gdata.client.init(handleGDError);
  // load the code.google.com developer calendar
	//loadCalendarByAddress('dskr2u6gk9r3naaeb4la8kl9pg@group.calendar.google.com');
	loadCalendarByAddress('qirqup8q7vm9bots5invri0e6k@group.calendar.google.com');
}

/**
 * Adds a leading zero to a single-digit number.  Used for displaying dates.
 */
function padNumber(num) {
  if (num <= 9) {
    return "0" + num;
  }
  return num;
}

/**
 * Determines the full calendarUrl based upon the calendarAddress
 * argument and calls loadCalendar with the calendarUrl value.
 *
 * @param {string} calendarAddress is the email-style address for the calendar
 */ 
function loadCalendarByAddress(calendarAddress) {
  var calendarUrl = 'https://www.google.com/calendar/feeds/' +
                    calendarAddress + 
                    '/public/full';
  loadCalendar(calendarUrl);
}

/**
 * Uses Google data JS client library to retrieve a calendar feed from the specified
 * URL.  The feed is controlled by several query parameters and a callback 
 * function is called to process the feed results.
 *
 * @param {string} calendarUrl is the URL for a public calendar feed
 */  
function loadCalendar(calendarUrl) {
  var service = new 
      google.gdata.calendar.CalendarService('gdata-js-client-samples-simple');
  var query = new google.gdata.calendar.CalendarEventQuery(calendarUrl);
  query.setOrderBy('starttime');
  query.setSortOrder('ascending');
  query.setFutureEvents(true);
  query.setSingleEvents(true);
  query.setMaxResults(10);

  service.getEventsFeed(query, listEvents, handleGDError);
}

/**
 * Callback function for the Google data JS client library to call when an error
 * occurs during the retrieval of the feed.  Details available depend partly
 * on the web browser, but this shows a few basic examples. In the case of
 * a privileged environment using ClientLogin authentication, there may also
 * be an e.type attribute in some cases.
 *
 * @param {Error} e is an instance of an Error 
 */
function handleGDError(e) {
  document.getElementById('jsSourceFinal').setAttribute('style', 
      'display:none');
  if (e instanceof Error) {
    /* alert with the error line number, file and message */
    alert('Error at line ' + e.lineNumber +
          ' in ' + e.fileName + '\n' +
          'Message: ' + e.message);
    /* if available, output HTTP error code and status text */
    if (e.cause) {
      var status = e.cause.status;
      var statusText = e.cause.statusText;
      alert('Root cause: HTTP error ' + status + ' with status text of: ' + 
            statusText);
    }
  } else {
    alert(e.toString());
  }
}

/**
 * Callback function for the Google data JS client library to call with a feed 
 * of events retrieved.
 *
 * Creates an unordered list of events in a human-readable form.  This list of
 * events is added into a div called 'events'.  The title for the calendar is
 * placed in a div called 'calendarTitle'
 *
 * @param {json} feedRoot is the root of the feed, containing all entries 
 */ 
function listEvents(feedRoot) {
  var entries = feedRoot.feed.getEntries();
  var eventDiv = document.getElementById('gigslist');
  if (eventDiv.childNodes.length > 0) {
    eventDiv.removeChild(eventDiv.childNodes[0]);
  }	  
  /* create a new unordered list */
  var ul = document.createElement('ul');

  /* loop through each event in the feed */
  var len = entries.length;
  for (var i = 0; i < len; i++) {
    var entry = entries[i];

    var title = entry.getTitle().getText();
	var place = entry.getLocations()[0].valueString;
    var startDateTime = null;
    var startJSDate = null;
    var endDateTime = null;
    var endJSDate = null;
    var times = entry.getTimes();
    if (times.length > 0) {
      startDateTime = times[0].getStartTime();
      startJSDate = startDateTime.getDate();
      endDateTime = times[0].getEndTime();
      endJSDate = endDateTime.getDate();
    }
    var entryLinkHref = null;
    if (entry.getHtmlLink() != null) {
      entryLinkHref = entry.getHtmlLink().getHref();
    }
	var time = "";
    //var dateString = weekday[startJSDate.getDay()]+', '+(month[startJSDate.getMonth()] ) + " " + startJSDate.getDate();
    var dateString = (month[startJSDate.getMonth()] ) + " " + startJSDate.getDate();
    if (!startDateTime.isDateOnly()) {
      var hours = startJSDate.getHours();
      var am = 'AM';
      if (hours > 12) {
      	hours -= 12;
      	am  = 'PM';
      }
      time += " " + hours + ":" + 
          padNumber(startJSDate.getMinutes()) + ' ' + am;
    }
    if (!endDateTime.isDateOnly()) {
      var hours = endJSDate.getHours();
      var am = 'AM';
      if (hours > 12) {
      	hours -= 12;
      	am  = 'PM';
      }
      time += "- " + hours + ":" + 
          padNumber(endJSDate.getMinutes()) + ' ' + am;
    }
    var li = document.createElement('li');
	li.setAttribute('id', 'cal_entry_'+(i+1));
	li.setAttribute('class', 'cal_entry');
	li.setAttribute('onClick', "showEntry("+(i+1)+");");
	var text = entry.content.getText().replace(/[\n\r]/g, '<br>');
	li.innerHTML = '<span class="date">'+ dateString+'</span><span class="title">'+title+'</span><span class="details">'+text+'</span><span class="time">' +time+'</span><span class="location">Starting Location: '+place+'</span>';
    /* append the list item onto the unordered list */
   	eventDiv.appendChild(li);
  	//linkURLs(li);
  }
  //eventDiv.appendChild(ul);
}
function linkURLs(li){
	var exp = /([^'"])((https?|ftp|file):\/\/([-A-Z0-9+&amp;@#\\/%?=~_|!:,.;]*[-A-Z0-9+&amp;@#\\/%=~_|]))/ig;
	li.each( function(){
		$(this).html( $(this).html().replace(exp,"$1<a href='$2' target='_blank'>$4</a>") );
	});
	return this;
}
google.setOnLoadCallback(init);
//-->

