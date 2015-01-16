<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 09/07/14 16:13
 */

namespace Inzeraz\Api;


use Nette;

class Crypt
{


	/**
	 * @param $crypt
	 * @param $appId
	 * @param $appSecret
	 *
	 * @return bool|string
	 */
	public static function getToken($crypt, $appId, $appSecret)
	{
		$sha1 = self::sha1([$crypt, $appId, $appSecret]);
		return substr($sha1, 0, 48);
	}


	/**
	 * @param $token
	 * @param $crypt
	 * @param $appId
	 * @param $appSecret
	 *
	 * @return bool
	 */
	public static function checkToken($token, $crypt, $appId, $appSecret)
	{
		return $token == self::getToken($crypt, $appId, $appSecret);
	}



	public static function sha1($data)
	{
        if(is_array($data)) {
            $data = json_encode($data);
        }
        return sha1($data);
	}



	/**
	 * @param $string
	 * @param $key
	 *
	 * @return bool|string
	 */
	public static function encrypt($string, $key)
	{
		return self::encrypt_decrypt('encrypt', $string, $key);
	}


	/**
	 * @param $crypt
	 * @param $key
	 *
	 * @return bool|string
	 */
	public static function decrypt($crypt, $key)
	{
		return self::encrypt_decrypt('decrypt', $crypt, $key);
	}


	/**
	 * @param $action
	 * @param $string
	 * @param $key
	 *
	 * @return bool|string
	 */
	protected static function encrypt_decrypt($action, $string, $key) {
		$output = false;

		$encrypt_method = "AES-256-CBC";

		// hash
		$key = hash('sha256', $key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $key), 0, 16);

		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}

		return $output;
	}
}
