<?php

/*
 * You need install 
 * + ffmpeg
 * + mplayer
 * + mencoder
 * //+ flvtool2
 * 
 */

// avconv -i demo.avi -vsync 1 -r 1 -an -y videoframe.jpg

Class VideoConvertor {

	protected static $_runtimeDir = '/mnt/video_convert/';

	public static function runtimeDir($runtimeDir = null) {
		if (!is_null($runtimeDir)) {
			static::$_runtimeDir = $runtimeDir;
		} elseif (!static::$_runtimeDir) {
			static::$_runtimeDir = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR;
		}
		if (!file_exists(static::$_runtimeDir)) {
			@mkdir(static::$_runtimeDir, 0755, true);
		}
		return static::$_runtimeDir;
	}

	/**
	 * Convert video
	 * 
	 * @param string $videoSourcePath file to convert path
	 * 
	 * @return string converted path
	 */
	public static function process($videoSourcePath, $videoPath = null) {
		if (!$videoPath) {
			$videoPath = static::runtimeDir() . md5(uniqid()) . '.flv';
		}
		/**
		 * CONVERT VIDEO
		 */
		exec("mencoder \"{$videoSourcePath}\" -o \"{$videoPath}\" -forceidx -of lavf -oac mp3lame -lameopts abr:br=128"
				. " -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=480:mbd=2:mv0:trell:v4mv:cbp:last_pred=3 -vf scale=620:370");
		//exec("flvtool2 -Uv \"{$videoPath}\" \"{$videoPath}\"");
		/**
		 * RETURN
		 */
		return $videoPath;
	}

	/**
	 * Get duration of video
	 * 
	 * @param string $videoPath file to convert path
	 * 
	 * @return interger
	 */
	public static function getDuration($videoPath) {
		exec("mplayer -vo null -ao null -frames 0 -identify \"{$videoPath}\"", $len);
		while (list($k, $v) = each($len)) {
			if (($length = strstr($v, 'ID_LENGTH='))) {
				break;
			}
		}
		if (empty($length)) {
			return false;
		}
		$lx = explode("=", $length);
		return floor($lx[1]);
	}

	/**
	 * Get duration of video
	 * 
	 * @param string $videoPath file to convert path
	 * 
	 * @return interger
	 */
	public static function getSnapAt($videoPath) {
		$duration = static::getDuration($videoPath);
		if ($duration === false) {
			return false;
		}
		if ($duration <= 2) {
			$snapAt = VideoConvertor::timeToString(0, true);
		} elseif ($duration <= 200) {
			$snapAt = VideoConvertor::timeToString($duration / 2, true);
		} else {
			$snapAt = VideoConvertor::timeToString(180, true);
		}
		return array($snapAt, $duration);
	}

	/**
	 * Get thumb of video
	 * 
	 * @param string $videoPath file to convert path
	 * 
	 * @return string thumb path
	 */
	public static function getThumbnail($videoPath, $snapAt) {
		$thumbPath = static::runtimeDir() . md5(uniqid()) . '.jpg';
		/**
		 * GET THUMBNAIL
		 */
		$cmd = "ffmpeg -i \"{$videoPath}\" -an -ss {$snapAt} -t 00:00:01 -r 1 -y -f rawvideo -vcodec mjpeg {$thumbPath}";
		exec($cmd);
		return $thumbPath;
	}

	/**
	 * Time to string
	 * 
	 * @param interger $sec the second
	 * @param boolean $showHr
	 * 
	 * @return string
	 */
	public static function timeToString($sec, $showHr = false) {
		$ss = round($sec);
		if ($showHr && $ss >= (60 * 60)) {
			$hh = floor($ss / (60 * 60));
			if ($hh < 10) {
				$hh = "0" . $hh;
			}
			$ss = $ss % (60 * 60);
		} else {
			$hh = null;
		}

		if ($ss >= 60) {
			$mm = floor($ss / 60);
			if ($mm < 10) {
				$mm = "0" . $mm;
			}
			$ss = $ss % 60;
		} else {
			$mm = "00";
		}
		if ($ss < 10) {
			$ss = "0" . $ss;
		}
		if ($showHr) {
			if(!empty($hh)){
				$str = $hh . ":" . $mm . ":" . $ss;
			} else {
				$str = $mm . ":" . $ss;
			}
		} else {
			$str = $mm . ":" . $ss;
		}
		return $str;
	}

}

?>
