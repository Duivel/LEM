<?php
namespace App\Database\Type;

use Cake\Database\Type;
use Cake\Database\Driver;
use App\Lib\Crypt;

class CryptedType extends Type
{
	public function toDatabase($value, Driver $driver)
	{
		return Crypt::customHash($value);
	}
}
?>