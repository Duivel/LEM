<?php
namespace App\Lib;

use Cake\Utility\Security;
use Cake\Network\Exception\ForbiddenException;
use Cake\Core\Configure;

class Crypt extends Security {
	const HASH_TYPE = 'sha256';
	const SESSION_NO_KEY_NAME = 'Security.session_id_key';
	
	/**
	 * encrypt a string (base64encode and aes256)
	 * @param string $encryptString: 
	 * @return string
	 */
	public static function customEncrypt($encryptString, $key = null) {
		return base64_encode(Security::encrypt($encryptString, $key));
// 		return base64_encode($encryptString);
	}
	
	/**
	 * decrypt a string
	 * @param string $decryptString
	 */
	public static function customDecrypt($decryptString, $key = null) {
// 		return base64_decode($decryptString);
		return Security::decrypt(base64_decode($decryptString), $key);
	}
	
	/**
	 * Hash a string
	 * This function will get 2 first characters of the string and add them to hash key.
	 * @param string $hashString
	 * @throws ForbiddenException if the hash string's length is less than 3
	 */
	public static function customHash($hashString) {
		if (strlen($hashString) <= 2) {
			throw new ForbiddenException(__('Invalid String'));
		}
		$id = substr($hashString, 0, 2);
		$salt = $id.Configure::read('Security.salt');
		return Security::hash($hashString, self::HASH_TYPE, $salt);
	}
}
?>