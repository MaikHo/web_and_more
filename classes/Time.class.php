<?php
class Time {
	public static function date_time() {
		$timestamp = time();

		$datum = date("d.m.Y",$timestamp);
		$uhrzeit = date("H:i",$timestamp);
		echo $datum;
	}
}
?>