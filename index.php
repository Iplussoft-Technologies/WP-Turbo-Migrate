<?php
/**************************************************************************

    Copyright (C) 2018 Iplussoft Technologies

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
**************************************************************************/

set_time_limit(0);

$error = null;

if ($_SERVER['REQUEST_METHOD']=='POST') {

	$fromurl = rtrim($_POST['oldurl'], '/');
	$tourl = rtrim($_POST['newurl'], '/');
	
	if(!filter_var($fromurl, FILTER_VALIDATE_URL))
	{
		$error .= 'Invalid url provided for old site.<br>';
	}
	
	if(!filter_var($tourl, FILTER_VALIDATE_URL))
	{
		$error .= 'Invalid url provided for new site.<br>';
	}
	
	$string = null;
	
    if(!empty($_FILES['sqlupload']['tmp_name']))
	{
		$string = file_get_contents($_FILES['sqlupload']['tmp_name']);
	}
	
	if ($string == null) {
		$error .= 'Invalid file uploaded.<br>';
	}

	if ($error == null) {

		$string = str_replace($fromurl, $tourl, $string);
		$string = str_replace(str_replace('/', '\\\\/', $fromurl), str_replace('/', '\\\\/', $tourl), $string);
		$string = str_replace(urlencode($fromurl), urlencode($tourl), $string);

		$string = preg_replace_callback(
			'|\[vc\_raw\_html\]([^\[\]]+)\[\/vc\_raw\_html\]|',
			function ($matches) {
				global $fromurl, $tourl;
				$find = $fromurl;
				$replace = $tourl;
		
				return '[vc_raw_html]'.base64_encode(str_replace(urlencode($find), urlencode($replace), base64_decode($matches[1]))).'[/vc_raw_html]';
			},
			$string
		);


		$string = preg_replace_callback(
			'|s\:([0-9]+)\:\"(http[^\"]+)\"|',
			function ($matches) { return 's:'.strlen($matches[2]).':"'.($matches[2]).'"'; },
			$string
		);

		$string = preg_replace_callback(
			'|s\:([0-9]+)\:\\\"(http[^\"]+)\\\"|',
			function ($matches) { return 's:'.strlen($matches[2]).':\\"'.($matches[2]).'\\"'; },
			$string
		);
	
		function slug($str)
		{
			$str = strtolower(trim($str));
			$str = preg_replace('/[^a-z0-9-]/', '-', $str);
			$str = preg_replace('/-+/', "-", $str);
			return $str;
		}

		//force download
		header("Content-type: application/sql");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="patched_'.date('d-m-Y').'_'.slug($tourl).'.sql"');

		echo $string;
		exit;
		
	}

}

?><html>
<head>
<title>WP Turbo Migrate</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family:Arial;">
<h2 style="color:#000;">WP Turbo Migrate</h2>
<form id="frm" method="post" autocomplete="off" enctype="multipart/form-data">
<div style="color:#ff0000;margin:5px;"><?php if ($error) echo $error; ?></div>
<div><input type="text" name="oldurl" placeholder="Old WP Site URL" style="margin-bottom:5px;padding:10px;width:100%;font-size:15px;" value="<?php echo htmlspecialchars(@$_POST['oldurl']); ?>" autofocus></div>
<div><input type="text" name="newurl" placeholder="New WP Site URL" style="margin-bottom:5px;padding:10px;width:100%;font-size:15px;" value="<?php echo htmlspecialchars(@$_POST['newurl']); ?>"></div>

<div style="margin-bottom:5px;padding:10px;background:#edf5ff;">
	<div>Select MySQL File</div>
	<div><input type="file" id="sqlupload" name="sqlupload" accept="sql/*" style="margin-bottom:5px;padding:10px;width:100%;font-size:15px;"></div>
</div>

<br>

<div><input type="submit" value="Export New MySQL File" style="padding:10px;width:100%;height:40px;background:#000;color:#fff;"></div>
</form>

<center><small>&copy; 2018 Iplussoft Technologies, Version 1.1</small></center>

</body>
</html>
