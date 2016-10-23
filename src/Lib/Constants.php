<?php
namespace App\Lib;

class Constants {
	const SYSTEM_NAME = "Expense Management System";
	
	/**
	 * Start of session part
	 */
	const SESSION_VALID_MINUTE = 60;
	const SESSION_VALID_SECOND = 3600;
	
	const SESSION_USER_NAME = "MqmZEeTJ9MbtG2am";
	const SESSION_LAST_ACCESS = "fK0EHsBMMzPfRe3I";
	
	const SESSION_URL_EXPENSE_VIEW = 'MGBAF4QyCUBl8Fl5';
	const SESSION_URL_WITHDRAW_VIEW = 'elv1QlODuqfEfNg5';
	const SESSION_URL_INCOME_VIEW = 'CSjqgtOb7Bll2phM';
	const SESSION_EXPENSE_SEARCH_CONDITIONS = 'e54TXeSYNZshcLTD';
	const SESSION_WITHDRAW_SEARCH_CONDITIONS = 'jfVkaNE94bPY4php';
	const SESSION_INCOME_SEARCH_CONDITIONS = 'AEsRSQsv5pCFF9z9';
	/**
	 * End of session part
	 */

	/**
	 * for Rabbit MQueue
	 */
	const RABBITMQ_SERVER_NAME = 'localhost';
	const RABBITMQ_SERVER_PORT = '5672';
	const RABBITMQ_EXPORT_EXPENSE_QUEUE_NAME = 'lem_expense_export';
	const RABBITMQ_EXPORT_EXPENSE_ROUTE_KEY = 'expense_export';
	const RABBITMQ_CALCULATE_SAVING_QUEUE_NAME = 'lem_saving_calculate';
	const RABBITMQ_CALCULATE_SAVING_ROUTE_KEY = 'saving_calculate';
	const RABBITMQ_EXCHANGE_NAME = 'lem';
	const RABBITMQ_USER = 'admin';
	const RABBITMQ_PASSWORD = 'admin123';
	
	const COOKIE_VALID_SECOND = 604800;
	const COOKIE_LOGIN_ID = 'g3MJyDS5wyTuSCvt';
	const COOKIE_USER_ID = 'fnhTJgUsSPoyUOti';
	
	/**
	 * For cache
	 */
	const CACHE_USER_ALL = 'lxeqehFTj5jSzuvi';
	const CACHE_EXPENSE_TYPE_ALL = 'xKRNsKoP7khMSWFo';
	/**
	 * End of cache
	 */
	
	//For routing
	const USER_PREFIX = "user";
	const LOGIN_PREFIX = "login";
	const API_PREFIX = "api";
	
	//Layout
	const LOGIN_LAYOUT = "before_login";
	const USER_LAYOUT = "user_after_login";
	
	//Optional Information
	const REISSUE_PASSWORD_CODE_LENGTH = 10;
	
	const NAMESPACE_CODE_CLASS = 'App\\Lib\\Code\\';
	
	//Main Menu's login history
	const MAIN_MENU_LOGIN_HISTORY_COUNT = 2;
	const MAIN_MENU_INCOME_LIST_COUNT = 10;
	const MAIN_MENU_EXPENSE_LIST_COUNT = 10;
	
	//for login screen
	const CHECKBOX_ON = '1';
	const LOGIN_TOKEN_LENGTH = 15;
	
	/**
	 * Pagination
	 */
	const EXPENSE_VIEW_LIMIT = 10;
	const WITHDRAW_VIEW_LIMIT = 10;
	const INCOME_VIEW_LIMIT = 10;
	
	/**
	 * End of Pagination
	 */
	const USER_LOCKED_SECOND = 600;
	const USER_LOCKED_ATTEMPT = 3;
}
?>