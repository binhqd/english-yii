<?php
			$data = array(
			'code'			=> $_GET['code'],
			'client_id'		=> '626676873784.apps.googleusercontent.com',
			'client_secret'	=> 'cZdXlwgyMLdOhT10OF1Knyis',
			'redirect_uri'	=> 'http://userbase.greennet.com/users/social/managegoogleplus',
			'grant_type'	=> 'authorization_code'
		);

		$data = http_build_query($data, '', '&');
				//var_dump($data);
		

		$tuCurl = curl_init();
		curl_setopt($tuCurl, CURLOPT_URL, "https://accounts.google.com/o/oauth2/token");
		curl_setopt($tuCurl, CURLOPT_HEADER, 0);
		curl_setopt($tuCurl, CURLOPT_SSLVERSION, 3);
		curl_setopt($tuCurl, CURLOPT_POST, 1);
		curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-length: ".strlen($data)));

		$tuData = curl_exec($tuCurl);
		if(!curl_errno($tuCurl)){
			$info = curl_getinfo($tuCurl);
			//echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
		} else {
			echo 'Curl error: ' . curl_error($tuCurl);
		}

		curl_close($tuCurl);
		$ret = json_decode($tuData, true);
		//echo "<pre>";var_dump($ret);exit;
		// get userinfo

		$url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token={$ret['access_token']}";

		$gmailinfo = file_get_contents($url);
		var_dump($gmailinfo);
		?>