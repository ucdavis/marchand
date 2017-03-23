<?php
	// Anyone on this list will be allowed access
	$admin_users = array("cthielen", "safutrel", "ptindall", "lkraus", "sschnack", "helens", "pbarron", "cwc23", "kkipp22", "smooers", "eefracol");

	function is_admin($loginid) {
		global $admin_users;

		foreach($admin_users as $user) {
			if($loginid == $user) {
				return true;
			}
		}

		return false;
	}

