<?php
namespace App\DovecotAuthenticator;
class DoveadmAuth {
	public static function encodepw($crypt, $password) {
		$descriptors = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$cwd = sys_get_temp_dir();
		$proc = proc_open(
			'doveadm pw -s '. escapeshellarg($crypt) . ' -p ' . escapeshellarg($password),
			$descriptors, $pipes, $cwd
		);
		if( ! is_resource($proc) ) { throw new Exception('failed to create passwordencrypt process'); }
		fwrite($pipes[0], $password);
		fclose($pipes[0]);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[1]);
		fclose($pipes[2]);

		$rval = proc_close($proc);

		return array($rval, $stdout, $stderr);
	}
	public static function auth($username, $password) {
		$descriptors = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);
		$cwd = sys_get_temp_dir();

		$proc = proc_open(
			'doveadm auth ' . escapeshellarg($username),
			$descriptors, $pipes, $cwd
		);
		if( ! is_resource($proc) ) { throw new Exception('failed to create auth process'); }
		fwrite($pipes[0], $password);
		fclose($pipes[0]);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[1]);
		fclose($pipes[2]);

		$rval = proc_close($proc);

		return array($rval, $stdout, $stderr);
	}
}
