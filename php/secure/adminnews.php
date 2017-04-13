<?php
ob_start();
session_start();
if(!( isset( $_SESSION['loggedin'] ) && ($_SESSION['loggedin'] == true ) ) ){
	header('location:index.php');
}

require_once "../classes/Connection.php";
$Connection = new Connection();

$months = array( 1 => 'January',
						2 => 'February',
						3 => 'March',
						4 => 'April',
						5 => 'May',
						6 => 'June',
						7 => 'July',
						8 => 'August',
						9 => 'September',
						10 => 'October',
						11 => 'November',
						12 => 'December' );


if( isset( $_POST['operation']) ){
	if ($_POST['operation'] == 'Add Article')  {
		$Connection->NewsPost();
	} elseif ($_POST['operation'] == 'Update Article') {
		$Connection->NewsUpdate( $_GET['newsid'] );
	}
	header('location:adminlist.php');
}
//if a news id is passed in the URL, user is modifying the record -> populate fields
//otherwise, user is adding new record -> set fields as blank
if(isset($_GET['newsid'])){
	$default = $Connection->NewsGetById( $_GET['newsid'] );
	#echo $default['dateposted'];
	if( $default['dateposted'] != -1 ) {
		$default['month'] = date('n', $default['dateposted']);
		$default['day'] = date('j', $default['dateposted']);
		$default['year'] = date('Y', $default['dateposted']);
	}
} else {
	$fields = array( 'title','byline','month','day','year','article');
	foreach( $fields as $field) {
		$default[$field] = "";
	}
	$default['active'] = 1;
}


?>


<?php include "/var/www/html/historyproject.ucdavis.edu/snippets/header.htm"; ?>


<div id="adminHeader">
<a href="/"><img src="/images/logo.gif" alt="History Project Home" /></a>
</div>

<div class="adminList">
<a href="adminlist.php"><img src="/images/freemensmall.jpg" alt="News Icon" /></a><br />
<div style="width: 200px;">
<form method="get" action="adminlist.php">
<input type="submit" value="View Full List" />
</form>
<h2>News</h2>
</div>
</div>
<div id="modify" >
<h2><?php echo (isset($_GET['newsid'])) ? ("Update Article") : ("New Article"); ?></h2>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table>
	<tr>
		<td>title:</td>
		<td><input type="text" name="title" value="<?php echo $default['title']; ?>" class="txtInputWide" /></td>
        <td rowspan="10" valign="top">
        	<div class="htmlRef">
        	<h4>HTML Quick Reference</h4>
        	<h2>Bold</h2>
            <p>&lt;strong&gt;<strong>this is bold</strong>&lt;/strong&gt;</p>
            <h2>Italic</h2>
            <p>&lt;em&gt;<em>this is italic</em>&lt;/em&gt;</p>
            <h2>Link</h2>
            <p>&lt;a href=&quot;http://www.ucdavis.edu&quot;&gt;Link Description&lt;/a&gt;</p>
            <h2>Email</h2>
            <p>&lt;a href=&quot;mailto:EMAIL&quot;&gt;Link Description&lt;/a&gt;</p>
            </div>
        </td>
	</tr>
	<tr>
		<td>by line:</td>
		<td><input type="text" name="byline" value="<?php echo $default['byline']; ?>" class="txtInputWide" /></td>
	</tr>
	<tr>
		<td>date:</td>
		<td style="text-align:left;">
		<select name="month">
        <option value="">month...</option>
		<?php
			foreach ($months as $num => $name ) {
				echo '<option value="' . $num . '" ';
				if( $default['month'] == $num ) {
					echo 'selected="selected" ';
				}
				echo '>' . $name . "</option>\n";
			}
		?>
		</select>
		<select name="day">
	    <option value="">day...</option>
	<?php
			for($i = 1; $i <= 31; $i++) {
				echo "<option value=\"$i\" ";
				if($default['day'] == $i) {
					echo 'selected="selected"';
				}
				echo ">$i</option>\n";
			}
		?>
		</select>
		<select name="year">
        <option value="">year...</option>
		<?php $year = (int)date( 'Y' );
			for($i = $year; $i<($year + 3); $i++) {
				echo "<option value=\"$i\" ";
				if($default['year'] == $i) {
					echo 'selected="selected"';
				}
				echo ">$i</option>\n";
			}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td valign="top">article:</td>
		<td><textarea name="article" rows="20" cols="40" class="txtInputWide"><?php echo $default['article']; ?></textarea></td>
	</tr>
	<tr>
		<td>active:</td>
		<td style="text-align:left;"><input type="radio" name="active" <?php if ($default['active'] == 1) {echo 'checked="checked"';} ?> value="1" />Yes &nbsp;
			<input type="radio" name="active" <?php if ($default['active'] == 0) {echo 'checked="checked"';} ?> value="0" />No
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
		<?php if(isset($_GET['newsid'])){?>
		<input type="submit" name="operation" value="Update Article" />
		<?php } else { ?>
		<input type="submit" name="operation" value="Add Article" />
		<?php } ?>
		</td>
	</tr>
</table>
</form>

</div>

<?php include "../snippets/footer.htm"; ?>
