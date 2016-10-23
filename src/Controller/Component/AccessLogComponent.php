<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Log\Log;
use App\Lib\LoginUser;

class AccessLogComponent extends Component
{
	public function writeLog($data = NULL)
	{
		$user_id = is_null(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
		if (!is_null($data)) {
			foreach ($data as $key=>$value) {
				if ($key == 'password') {
					$value = "***";
				}
			}
			$msg = "[User: {$user_id}], [REQUEST_URI: ".env('REQUEST_URI')."], [HTTP_REFERER: ".env('HTTP_REFERER').
			"], [HTTP_USER_AGENT: ".env('HTTP_USER_AGENT')."], [REMOTE_ADDR: ".env('REMOTE_ADDR').'], [DATA: '.json_encode($data, JSON_UNESCAPED_UNICODE)."]\n";
		} else {
			$msg = "[User: {$user_id}], [REQUEST_URI: ".env('REQUEST_URI')."], [HTTP_REFERER: ".env('HTTP_REFERER').
			"], [HTTP_USER_AGENT: ".env('HTTP_USER_AGENT')."], [REMOTE_ADDR: ".env('REMOTE_ADDR')."]\n";
		}
		Log::info($msg);
	}
}