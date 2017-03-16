<?php

include_once('CAS/CAS.php');

phpCAS::client(CAS_VERSION_2_0, "cas.ucdavis.edu", 443, "cas");
phpCAS::setCasServerCACert("/etc/pki/tls/cert.pem");
phpCAS::forceAuthentication();

include_once('../html/secure_users.inc.php');

if(in_array(phpCAS::getUser(), $valid_users)) {
	$_SESSION['loggedin'] = true;
	$_SESSION['uid'] = phpCAS::getUser();

	// We're no longer using this page, only a proper Kerberos is required now
	header('Location:/secure/adminlist.php');
} else {
	echo "You are not authorized to use this website.";
}

die;

ob_start();
session_start();
$message = "";
if( isset( $_POST['operation'] ) && ( $_POST['operation'] == 'Submit' )) {
	require_once "../classes/Connection.php";
	$Connection = new Connection();

	//function will automatically redirect user
	$Connection->LoginUser( $_POST['login'], $_POST['password'] );
	$message = "Username/Password not found, please try again";
}



include "../snippets/header.htm"; ?>


<div id="adminHeader">
	<a href="http://historyproject.ucdavis.edu/"><img src="/images/logo.gif" alt="History Project Home" /></a>
</div>
<div id="login2">&nbsp;</div>
<div id="login3">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table>
<?php if ($message != "") { echo '<tr><td colspan="2"><p>' . htmlspecialchars( $message ) . '</p></td></tr>'; } ?>
	<tr>
		<td><p class="title">Login ID</p></td>
		<td><input type="text" name="login" /></td>
	</tr>
	<tr>
		<td><p class="title">Password</p></td>
		<td><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" name="operation" value="Submit" /></td>
	</tr>
</table>
</form>
</div>

<?php include "../snippets/footer.htm"; ?>
