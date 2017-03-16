<?php
ob_start();
session_start();
if(!( isset( $_SESSION['loggedin'] ) && ($_SESSION['loggedin'] == true ) ) ){
	header('location: /index.php');
}

require_once "../classes/Connection.php";
$Connection = new Connection();

if(isset($_POST['newsoperation'])) {
	if ($_POST['newsoperation'] == 'edit') {
		header("location:adminnews.php?newsid={$_POST['newsid']}" );
	} elseif ($_POST['newsoperation'] == 'delete') {
		$Connection->NewsDelete( $_POST['newsid'] );
	}
}

if(isset($_POST['caloperation'])) {
	if ($_POST['caloperation'] == 'edit') {
		header("location:admincalendar.php?classid={$_POST['classid']}");
	} elseif ($_POST['caloperation'] == 'delete') {
		$Connection->CalendarDelete( $_POST['classid'] );
	}
}

//Search Functionality
if( isset( $_GET['newssearch'] ) ) {
	$_SESSION['newssearch'] = $_GET['newssearch'];
}
if( isset( $_SESSION['newssearch']) && ($_SESSION['newssearch'] != "") ) {
	$articles = $Connection->NewsSearch( $_SESSION['newssearch'] );
} else {
	$articles = $Connection->NewsGetAll();
}
if( isset( $_GET['calsearch'] ) ) {
	$_SESSION['calsearch'] = $_GET['calsearch'];
}
if(isset($_SESSION['calsearch']) ) {
	$classes = $Connection->CalendarSearch( $_SESSION['calsearch'] );
} else {
	$classes = array_reverse($Connection->CalendarGetAll());
}

?>


<?php include "../snippets/header.htm"; ?>

<div id="adminHeader">
<a href="/"><img src="/images/logo.gif" alt="History Project Home" /></a>
</div>

<div class="adminList">

<a href="/calendar.php"><img src="/images/stonehenge_big.jpg" alt="Calendar Icon" /></a><br />
<form class="searchForm" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<input type="text" name="calsearch" value="<?php if(isset($_SESSION['calsearch']) ) echo $_SESSION['calsearch']; ?>" />
<input type="submit" value="search" />
</form>
<form class="searchForm" method="get" action="/secure/admincalendar.php">
<input type="submit" value="Add Class" />&nbsp;
</form>
<h2>Calendar</h2>
<div style="clear:both;">
<table width="100%">
<?php
$count = 1;
foreach($classes as $row) {
	echo ($count % 2) ? '<tr class="listLineOdd">' : '<tr class="listLine">';
	echo '<td><p>' . $row['title'] . '</p></td>';
?>
	<td>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="classid" value="<?php echo $row['classid']; ?>" />
    <div class="modButtons">
	<input type="submit" name="caloperation" value="edit" style="color: #384DA7;" /> |
	<input type="submit" name="caloperation" value="delete" onclick="return confirm('Are you sure you want to permanantly delete this record?');" style="color: #384DA7;" /></div>
	</form>
	</td>
<?php
	echo "</tr>\n";
	$count++;
}
?>
</table>
</div>
</div>
<div class="adminList">
<a href="news.php"><img src="/images/freemensmall.jpg" alt="News Icon" /></a><br />
<form class="searchForm" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<input type="text" name="newssearch" value="<?php if(isset($_SESSION['newssearch'])) echo $_SESSION['newssearch']; ?>" />
<input type="submit" value="search" />
</form>
<form class="searchForm" method="get" action="adminnews.php">
<input type="submit" value="Add Article" />&nbsp;
</form>
<h2>News</h2>
<div style="clear:both;">
<table width="100%" >
<?php
$count = 1;
while( $row = mysql_fetch_assoc( $articles )){
	echo ($count % 2) ? '<tr class="listLineOdd">' : '<tr class="listLine">';
	echo '<td><p>' . $row['title'] . '</p></td>';
?>
	<td>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<input type="hidden" name="newsid" value="<?php echo $row['newsid']; ?>" />
    <div class="modButtons">
	<input type="submit" name="newsoperation" value="edit" style="color: #384DA7;" /> |
	<input type="submit" name="newsoperation" value="delete" onclick="return confirm('Are you sure you want to permanantly delete this record?');" style="color: #384DA7;" /></div>
	</form>
	</td>
<?php
	echo "</tr>\n";
	$count++;
}
?>
</table>
</div>

<?php include "../snippets/footer.htm"; ?>
