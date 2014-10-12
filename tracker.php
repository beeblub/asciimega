<?php

include_once("ref/config.php");

//------------------------------------
//	Manage active users list
//------------------------------------

// log user activity
function count_user ($ident,$url) {
  $list = get_list ();

  // refreshes/adds current user activity
  $list[$ident][0] = time ();
  $list[$ident][1] = $url; 

  set_list ($list);
}
function get_list () {
  global $config_countusers;

  // read file
  $lines = file ($config_countusers['filename']);

  // error?
  if (!is_array ($lines)) {
    return array ();
  }

  $result = array ();
  // check each line
  foreach ($lines as $line) {
    // seperate values in line
    list ($ident, $time,$url) = explode ('|', $line, 3);
    if (empty ($ident)) {
      continue;
    }

    // session expired
    if ($time < time() - $config_countusers['timelimit']) {
      continue;
    }

    // add data to result
    $result[$ident] = array ((int)$time,$url);
  }
  return $result;
}
// schreibe die Liste zurück
function set_list ($list) {
  // importiere die Konfiguration
  global $config_countusers;

  $result = '';
  // add each line to result
  foreach ($list as $ident => $data) {
    $result .= "$ident|$data[0]|$data[1]\n";
  }

  // open file
  $file = fopen ($config_countusers['filename'], 'w');

  // error?
  if (!is_resource ($file)) {
    return false;
  }

  // write
  $cnt = fwrite ($file, $result);

  // error?
  if ($cnt === false) {
    fclose ($file);
    return false;
  }

  fclose ($file);

  return true;
}
// returns the number of users online
function count_users () {
  return count (get_list ());
}

function get_userlist()
{
	return get_list ();
}

//------------------------------------
//		Controls
//------------------------------------

function logurl($url)
{
	global $config_track;

	$t = time();
	$filename = $config_track['logpath_pre'].date("Y-m-d",$t).$config_track['logpath_post'];
	$content = $url."|".$t."\n";
	file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
}
function track()
{
	$personid = hash("md5",$_SERVER['REMOTE_ADDR']."salt2l3492XD6".$_SERVER['HTTP_USER_AGENT']);
	$currenturl = $_SERVER['REQUEST_URI'];
	logurl($currenturl);
	count_user($personid,$currenturl);
}
track();
?>
