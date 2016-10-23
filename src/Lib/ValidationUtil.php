<?php
namespace App\Lib;

class ValidationUtil
{

	/***********************************************
	 * 正規表現 FROM
	***********************************************/

	// 半角英数チェック時の正規表現文字列
	const CHECK_ALPHA_NUMERIC = '/^[a-z\d]*$/i';

	// 日本人の名前
	const CHECK_JAPAN_NAME = '/^[ \x3400-\x4DB5\x4E00-\x9FCB\xF900-\xFA6A]+$/';

	// 日本の携帯電話番号
	const CHECK_JAPAN_PHONE_NUM =  '/^(0\d{1,4}[\s-]?\d{1,4}[\s-]?\d{1,4})$/';

	// 日本の郵便番号
	const CHECK_JAPAN_POST_CODE =  '/^[0-9]{3}-?[0-9]{4}$/';
	//const CHECK_JAPAN_POST_CODE =  '/^[0-9]{7}$/';

	// 半角英数(スペース有り)チェック時の正規表現文字列
	const CHECK_ALPHA_NUMERIC_SPACE = '/^[ a-z\d]*$/i';

	// ﾛｸﾞｲﾝID&ﾊﾟｽﾜｰﾄﾞチェック時の正規表現文字列
	//const CHECK_PASSWORD = '/^[a-z\d_-]*$/i';
	const CHECK_PASSWORD = '/^.*(?=.{8,32})(?=.*\d)(?=.*[a-zA-Z]).*$/';

	// 半角数値チェック時の正規表現文字列
	const CHECK_NUMERIC = '/^[0-9]+$/';

	//身長、体重のようなタイプをチェック時の正規表現文字列
	const CHECK_DECIMAL_NUMBER = '/^[0-9]{1,6}.?[0-9]{0,3}$/';

	// 半角英数字と(_)の正規表現文字列
	const CHECK_ALPHA_NUMERIC_BAR = '/^[a-z\d_]*$/i';

	// 電話番号の正規表現文字列
	const CHECK_TEL_NO = '/^[\d-]*$/i';

	// 全角カナチェック
	const CHECK_ZENKAKU_KANA = '/^[ァ-ヶー]+$/u';

	// 全角ひらがなチェック
	const CHECK_ZENKAKU_HIRAGANA = '/^[ぁ-ん]+$/u';

	// 画像データかどうか
	const CHECK_IMAGE_PASS = "/^([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)\.(jpeg|jpg|gif|png)$/";

	// 連続した..を許可する場合のメールアドレスチェック正規表現
	const CHECK_EMAIL = '/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i';
	//const CHECK_EMAIL = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
	
	//For expense
	const TITLE_MAX_LENGTH = 100;
	const DESCRIPTION_MAX_LENGTH = 255;
	
	//For income
	const INCOME_NOTE_MAX_LENGTH = 200;
	
	//For withdraw
	const WITHDRAW_TITLE_MAX_LENGTH = 100;
	const WITHDRAW_DESCRIPTION_MAX_LENGTH = 255;
	
	//For User
	const USER_NAME_MAX_LENGTH = 50;
	const USER_PASSWORD_MAX_LENGTH = 20;
	const USER_PASSWORD_MIN_LENGTH = 6;
	

	/***********************************************
	 * 正規表現 TO
		***********************************************/

	// システムユーザログインパスの最大桁数
	const ADMIN_LOGIN_PASS_MAX_LEN = 32;
	// システムユーザログインパスの最小桁数
	const ADMIN_LOGIN_PASS_MIN_LEN = 8;
}
?>