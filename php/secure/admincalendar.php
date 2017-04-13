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
	if ($_POST['operation'] == 'Add Class')  {
		if($Connection->CalendarPost()) {
			header('location:adminlist.php');
		} else {
			$message = 'There was a problem adding the record.  Please try again.';
		}
	} elseif ($_POST['operation'] == 'Update Class') {
		if( $Connection->CalendarUpdate( $_GET['classid'] )) {
			header('location: adminlist.php');
		} else {
			$message = 'There was a problem updating the record.  Please try again.';
		}
	} elseif ($_POST['operation'] == 'Remove') {
		if( $Connection->CalendarUpdate( $_GET['classid'], 1 )) {
			header('location: adminlist.php');
		} else {
			$message = 'There was a problem updating the record.  Please try again.';
		}
	}
}

//if a class id is passed in the URL, user is modifying the record -> populate fields
//otherwise, user is adding new record -> set fields as blank
if(isset($_GET['classid'])){
	$default = $Connection->CalendarGetById( $_GET['classid'] );
	$default['month'] = date('n', $default['classdate']);
	$default['day'] = date('j', $default['classdate']);
	$default['year'] = date('Y', $default['classdate']);

	if( isset($default['classdate2']) && ($default['classdate2'] != '-1') ) {
		$default['month2'] = date('n', $default['classdate2']);
		$default['day2'] = date('j', $default['classdate2']);
		$default['year2'] = date('Y', $default['classdate2']);
	} else {
		$default['month2'] = '';
		$default['day2'] = '';
		$default['year2'] = '';
	}
} else {
	$fields = array( 'title','time','location','address','city','state','zip','month','day','year','month2','day2','year2','description','link');
	foreach( $fields as $field) {
		$default[$field] = "";
	}
}


include "/var/www/html/historyproject.ucdavis.edu/snippets/header.htm"; ?>


<div id="adminHeader">
<a href="/"><img src="/images/logo.gif" alt="History Project Home" /></a>
</div>

<div class="adminList">
<a href="adminlist.php"><img src="/images/stonehenge_big.jpg" alt="Calendar Icon" /></a><br />

<div style="width: 200px;">
<form method="get" action="adminlist.php">
<input type="submit" value="View Full List" />
</form>
<h2>Calendar</h2>
</div>
</div>
<div id="modify" >
<h1><?php echo (isset($_GET['classid'])) ? ("Update Class") : ("New Class"); ?></h1>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
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
		<td>time:</td>
		<td><input type="text" name="time" value="<?php echo $default['time']; ?>" class="txtInputWide" /></td>
	</tr>
	<tr>
		<td>date:</td>
		<td style="text-align:left;">
		<select name="month">
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
		<td>ending date:</td>
		<td style="text-align:left;">
		<select name="month2">
	        <option value="f">Month...</option>
	<?php
			foreach ($months as $num => $name ) {
				echo '<option value="' . $num . '" ';
				if( $default['month2'] == $num ) {
					echo 'selected="selected" ';
				}
				echo '>' . $name . "</option>\n";
			}
		?>
		</select>
		<select name="day2">
	        <option value="f">Day...</option>
	<?php
			for($i = 1; $i <= 31; $i++) {
				echo "<option value=\"$i\" ";
				if($default['day2'] == $i) {
					echo 'selected="selected"';
				}
				echo ">$i</option>\n";
			}
		?>
		</select>
		<select name="year2">
	        <option value="f">Year...</option>
	<?php $year = (int)date( 'Y' );
			for($i = $year; $i<($year + 3); $i++) {
				echo "<option value=\"$i\" ";
				if($default['year2'] == $i) {
					echo 'selected="selected"';
				}
				echo ">$i</option>\n";
			}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td>location name:</td>
		<td><input type="text" name="location" value="<?php echo $default['location']; ?>" class="txtInputWide" /></td>
	</tr>
	<tr>
		<td>location address:</td>
		<td><input type="text" name="address" value="<?php echo $default['address']; ?>" class="txtInputWide" /></td>
	</tr>
	<tr>
		<td>city/state/zip</td>
		<td style="text-align:left;"><input type="text" name="city" value="<?php echo $default['city']; ?>" />
			<input type="text" name="state" value="<?php echo $default['state']; ?>" style="width: 25px;" />
			<input type="text" name="zip" value="<?php echo $default['zip']; ?>" style="width: 100px;" /></td>
	</tr>
	<tr>
		<td valign="top">description:</td>
		<td><textarea name="description" rows="20" cols="40" class="txtInputWide"><?php echo $default['description']; ?></textarea></td>
	</tr>
	<tr>
		<td>price:</td>
		<td><input type="text" name="price" value="<?php if(isset($default['price'])) { echo $default['price']; } ?>" class="txtInputWide" /></td>
	</tr>
	<tr>
		<td>link:</td>
		<td><input type="text" name="link" value="<?php echo $default['link']; ?>" class="txtInputWide" /></td>
	</tr>
    <?php
	if( isset($default['pdf_link']) && $default['pdf_link'] ) {
		echo '<tr><td>current pdf:</td><td style="text-align:left;">' . urldecode( basename( $default['pdf_link'] ) );
		echo ' &nbsp; <input type="submit" name="operation" value="Remove" />';
	}
	?>
    <tr>
    	<td>Upload pdf</td>
        <td><input type="file" name="pdf_link" class="txtInputWide" /></td>
    </tr>
	<tr>
		<td colspan="2" align="right">
		<?php if( isset($_GET['classid']) ){?>
		<input type="submit" name="operation" value="Update Class" />
		<?php } else { ?>
		<input type="submit" name="operation" value="Add Class" />
		<?php } ?>
		</td>
	</tr>
</table>
</form>

</div>

<?php include "../snippets/footer.htm"; ?>
