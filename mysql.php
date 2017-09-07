<?php
/**
 * Procedural drop in replacement for legacy projects using the MySQL function
 *
 * @author Sjoerd Maessen
 * @version 0.1
 */

// Make sure the MySQL extension is not loaded and there is no other drop in replacement active
if (!extension_loaded('mysql') && !function_exists('mysql_connect')) {

	// Validate if the MySQLi extension is present
	if (!extension_loaded('mysqli')) {
		trigger_error('The extension "MySQLi" is not available', E_USER_ERROR);
	}

	// The function name "getLinkIdentifier" will be used to return a valid link_indentifier, make it is available
	if (function_exists('getLinkIdentifier')) {
		trigger_error('The function name "getLinkIdentifier" is already defined, please change the function name', E_USER_ERROR);
	}

	// Define MySQL constants
	define('MYSQL_CLIENT_COMPRESS', MYSQLI_CLIENT_COMPRESS);
	define('MYSQL_CLIENT_IGNORE_SPACE', MYSQLI_CLIENT_IGNORE_SPACE);
	define('MYSQL_CLIENT_INTERACTIVE', MYSQLI_CLIENT_INTERACTIVE);
	define('MYSQL_CLIENT_SSL', MYSQLI_CLIENT_SSL);

	define('MYSQL_ASSOC', MYSQLI_ASSOC);
	define('MYSQL_NUM', MYSQLI_NUM);
	define('MYSQL_BOTH', MYSQLI_BOTH);

	// Will contain the link identifier
	$link = null;

	/**
	 * Get the link identifier
	 *
	 * @param mysqli $mysqli
	 * @return mysqli|null
	 */
	function getLinkIdentifier(mysqli $mysqli = null)
	{
		if (!$mysqli) {
			global $link;
			$mysqli = $link;
		}

		return $mysqli;
	}

	/**
	 * Open a connection to a MySQL Server
	 *
	 * @param $server
	 * @param $username
	 * @param $password
	 * @return mysqli|null
	 */
	function mysql_connect($server, $username, $password, $new_link = false, $client_flags = 0)
	{
		global $link;

		$link = mysqli_connect($server, $username, $password);
		return $link;
	}

	/**
	 * Open a persistent connection to a MySQL server
	 *
	 * @param $server
	 * @param $username
	 * @param $password
	 * @return mysqli|null
	 */
	function mysql_pconnect($server, $username, $password, $new_link = false, $client_flags = 0)
	{
		global $link;

		$link = mysqli_connect('p:' . $server, $username, $password);
		return $link;
	}

	/**
	 * @param $databaseName
	 * @return bool
	 */
	function mysql_select_db($databaseName)
	{
		global $link;

		return mysqli_select_db($link, $databaseName);
	}

	/**
	 * @param $query
	 * @param mysqli $mysqli
	 * @return bool|mysqli_result
	 */
	function mysql_query($query, mysqli $mysqli = null)
	{
		return getLinkIdentifier($mysqli)->query($query);
	}

	/**
	 * @param $string
	 * @param mysqli $mysqli
	 * @return string
	 */
	function mysql_real_escape_string($string, mysqli $mysqli = null)
	{
		return getLinkIdentifier($mysqli)->escape_string($string);
	}

	/**
	 * @param mysqli_result $result
	 * @return bool|array
	 */
	function mysql_fetch_assoc(mysqli_result $result)
	{
		$result = $result->fetch_assoc();
		if ($result === NULL) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param mysqli_result $result
	 * @return object|stdClass
	 */
	function mysql_fetch_object(mysqli_result $result)
	{
		$result = $result->fetch_object();
		if ($result === NULL) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param mysqli_result $result
	 * @return bool|int
	 */
	function mysql_num_rows(mysqli_result $result)
	{
		$result = $result->num_rows;
		if ($result === NULL) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param mysqli_result $result
	 * @return bool|array
	 */
	function mysql_fetch_row(mysqli_result $result)
	{
		$result = $result->fetch_row();
		if ($result === NULL) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param mysqli $mysqli
	 * @return int
	 */
	function mysql_affected_rows(mysqli $mysqli = null)
	{
		return mysqli_affected_rows(getLinkIdentifier($mysqli));
	}

	/**
	 * @return void
	 */
	function mysql_client_encoding(mysqli $mysqli = null)
	{
		return mysqli_character_set_name(getLinkIdentifier($mysqli));
	}

	/**
	 * @param mysqli $mysqli
	 * @return bool
	 */
	function mysql_close(mysqli $mysqli = null)
	{
		return mysqli_close(getLinkIdentifier($mysqli));
	}

	/**
	 * @return bool
	 */
	function mysql_create_db($database_name, mysqli $mysqli = null)
	{
		trigger_error('This function was deprecated in PHP 4.3.0 and is therefor not supported', E_USER_DEPRECATED);
		return false;
	}

	/**
	 * @param mysqli $mysqli
	 * @return int
	 */
	function mysql_errno(mysqli $mysqli = null)
	{
		return mysqli_errno(getLinkIdentifier($mysqli));
	}

	/**
	 * Not implemented
	 *
	 * @todo implement
	 *
	 * @return null
	 */
	function mysql_db_name()
	{
		trigger_error('The function mysql_db_name() is not implemented', E_USER_WARNING);
		return false;
	}

	/**
	 * @param mysqli $mysqli
	 * @return string
	 */
	function mysql_error(mysqli $mysqli = null)
	{
		return mysqli_error(getLinkIdentifier($mysqli));
	}

	/**
	 * @param mysqli_result $result
	 * @param $result_type
	 * @return void
	 */
	function mysql_fetch_array(mysqli_result $result, $result_type = MYSQL_BOTH)
	{
		return mysqli_fetch_array($result, $result_type);
	}

	/**
	 * @param mysqli $mysqli
	 * @return bool
	 */
	function mysql_ping(mysqli $mysqli = null)
	{
		return mysqli_ping(getLinkIdentifier($mysqli));
	}

	/**
	 * @param $query
	 * @param mysqli $mysqli
	 */
	function mysql_unbuffered_query($query, mysqli $mysqli = null)
	{
		return mysqli_query(getLinkIdentifier($mysqli), $query, MYSQLI_USE_RESULT);
	}

	/**
	 * @return string
	 */
	function mysql_get_client_info()
	{
		return mysqli_get_client_info();
	}

	/**
	 * @param mysqli_result $result
	 * @return void
	 */
	function mysql_free_result(mysqli_result $result)
	{
		return mysqli_free_result($result);
	}

	/**
	 * @param mysqli $mysqli
	 * @return bool|mysqli_result
	 */
	function mysql_list_dbs(mysqli $mysqli = null)
	{
		trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL Query: SHOW DATABASES statement instead.', E_USER_DEPRECATED);

		return mysqli_query(getLinkIdentifier($mysqli), 'SHOW DATABASES');
	}

	/**
	 * @param $database_name
	 * @param $table_name
	 * @param null $mysqli
	 * @return bool|mysqli_result
	 */
	function mysql_list_fields($database_name, $table_name, mysqli $mysqli = null)
	{
		trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL SHOW COLUMNS FROM table [LIKE \'name\'] statement instead.', E_USER_DEPRECATED);

		$mysqli = getLinkIdentifier($mysqli);
		$db = mysqli_escape_string($mysqli, $database_name);
		$table = mysqli_escape_string($mysqli, $table_name);

		return mysqli_query($mysqli, sprintf('SHOW COLUMNS FROM %s.%s', $db, $table));
	}

	/**
	 * @param mysqli $mysqli
	 * @return bool|mysqli_result
	 */
	function mysql_list_processes(mysqli $mysqli = null)
	{
		return mysqli_query(getLinkIdentifier($mysqli), 'SHOW PROCESSLIST');
	}

	/**
	 * @param $charset
	 * @param null $mysqli
	 * @return bool
	 */
	function mysql_set_charset($charset, mysqli $mysqli = null)
	{
		return mysqli_set_charset(getLinkIdentifier($mysqli), $charset);
	}

	/**
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_info(mysqli $mysqli = null)
	{
		$result = mysqli_info(getLinkIdentifier($mysqli));
		if ($result === NULL) {
			$result = false;
		}

		return $result;
	}

	/**
	 * Get current system status
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_stat(mysqli $mysqli = null)
	{
		return mysqli_stat(getLinkIdentifier($mysqli));
	}

	/**
	 * Return the current thread ID
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_thread_id(mysqli $mysqli = null)
	{
		return mysqli_thread_id(getLinkIdentifier($mysqli));
	}

	/**
	 * Get MySQL host info
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_get_host_info(mysqli $mysqli = null)
	{
		return mysqli_get_host_info(getLinkIdentifier($mysqli));
	}

	/**
	 * Get MySQL protocol info
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_get_proto_info(mysqli $mysqli = null)
	{
		return mysqli_get_proto_info(getLinkIdentifier($mysqli));
	}

	/**
	 * Get MySQL server info
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_get_server_info(mysqli $mysqli = null)
	{
		return mysqli_get_server_info(getLinkIdentifier($mysqli));
	}

	/**
	 * Get table name of field
	 *
	 * @param $result
	 * @param $i
	 * @return bool
	 */
	function mysql_tablename($result, $i)
	{
		trigger_error('Not implemented', E_USER_WARNING);
		return false;
	}

	/**
	 * Get the ID generated in the last query
	 *
	 * @param null $mysqli
	 * @return int|string
	 */
	function mysql_insert_id(mysqli $mysqli = null)
	{
		return mysqli_insert_id(getLinkIdentifier($mysqli));
	}

	/**
	 * Get result data
	 *
	 * @param $result
	 * @param $row
	 * @param int $field
	 * @return mixed
	 */
	function mysql_result($result, $row, $field = 0)
	{
		$result->data_seek($row);
		$row = $result->fetch_array();
		if (!isset($row[$field])) {
			return false;
		}

		return $row[$field];
	}

	/**
	 * Get number of fields in result
	 *
	 * @param mysqli_result $result
	 * @return int
	 */
	function mysql_num_fields(mysqli_result $result)
	{
		return mysqli_num_fields($result);
	}

	/**
	 * List tables in a MySQL database
	 *
	 * @param null $mysqli
	 * @return bool|string
	 */
	function mysql_list_tables($database_name, mysqli $mysqli = null)
	{
		trigger_error('This function is deprecated. It is preferable to use mysql_query() to issue an SQL SHOW TABLES [FROM db_name] [LIKE \'pattern\'] statement instead.', E_USER_DEPRECATED);

		$mysqli = getLinkIdentifier($mysqli);
		$db = mysqli_escape_string($mysqli, $database_name);

		return mysqli_query($mysqli, sprintf('SHOW TABLES FROM %s', $db));
	}

	/**
	 *  Get column information from a result and return as an object
	 *
	 * @param mysqli_result $result
	 * @param int $field_offset
	 * @return bool|object
	 */
	function mysql_fetch_field(mysqli_result $result, $field_offset = 0)
	{
		if ($field_offset) {
			mysqli_field_seek($result, $field_offset);
		}

		return mysqli_fetch_field($result);
	}

	/**
	 * Returns the length of the specified field
	 *
	 * @param mysqli_result $result
	 * @param int $field_offset
	 * @return bool
	 */
	function mysql_field_len(mysqli_result $result, $field_offset = 0)
	{
		trigger_error('This function is not implemented', E_USER_WARNING);
		return false;
	}

	/**
	 * @return bool
	 */
	function mysql_drop_db()
	{
		trigger_error('This function is deprecated since PHP 4.3.0 and therefore not implemented', E_USER_DEPRECATED);
		return false;
	}

	/**
	 * Move internal result pointer
	 *
	 * @param mysqli_result $result
	 * @param int $row_number
	 * @return void
	 */
	function mysql_data_seek(mysqli_result $result, $row_number = 0)
	{
		return mysqli_data_seek($result, $row_number);
	}

	/**
	 * Get the name of the specified field in a result
	 *
	 * @param $result
	 * @param $field_offset
	 * @return bool
	 */
	function mysql_field_name($result, $field_offset = 0)
	{
		$props = mysqli_fetch_field_direct($result, $field_offset);
		return is_object($props) ? $props->name : false;
	}

	/**
	 * Get the length of each output in a result
	 *
	 * @param mysqli_result $result
	 * @return array|bool
	 */
	function mysql_fetch_lengths(mysqli_result $result)
	{
		return mysqli_fetch_lengths($result);
	}

	/**
	 * Get the type of the specified field in a result
	 * @param mysqli_result $result
	 * @param $field_offset
	 * @return string
	 */
	function mysql_field_type(mysqli_result $result, $field_offset = 0)
	{
		$unknown = 'unknown';
		$info = mysqli_fetch_field_direct($result, $field_offset);
		if (empty($info->type)) {
			return $unknown;
		}

		switch ($info->type) {
			case MYSQLI_TYPE_FLOAT:
			case MYSQLI_TYPE_DOUBLE:
			case MYSQLI_TYPE_DECIMAL:
			case MYSQLI_TYPE_NEWDECIMAL:
				return 'real';

			case MYSQLI_TYPE_BIT:
				return 'bit';

			case MYSQLI_TYPE_TINY:
				return 'tinyint';

			case MYSQLI_TYPE_TIME:
				return 'time';

			case MYSQLI_TYPE_DATE:
				return 'date';

			case MYSQLI_TYPE_DATETIME:
				return 'datetime';

			case MYSQLI_TYPE_TIMESTAMP:
				return 'timestamp';

			case MYSQLI_TYPE_YEAR:
				return 'year';

			case MYSQLI_TYPE_STRING:
			case MYSQLI_TYPE_VAR_STRING:
				return 'string';

			case MYSQLI_TYPE_SHORT:
			case MYSQLI_TYPE_LONG:
			case MYSQLI_TYPE_LONGLONG:
			case MYSQLI_TYPE_INT24:
				return 'int';

			case MYSQLI_TYPE_CHAR:
				return 'char';

			case MYSQLI_TYPE_ENUM:
				return 'enum';

			case MYSQLI_TYPE_TINY_BLOB:
			case MYSQLI_TYPE_MEDIUM_BLOB:
			case MYSQLI_TYPE_LONG_BLOB:
			case MYSQLI_TYPE_BLOB:
				return 'blob';

			case MYSQLI_TYPE_NULL:
				return 'null';

			case MYSQLI_TYPE_NEWDATE:
			case MYSQLI_TYPE_INTERVAL:
			case MYSQLI_TYPE_SET:
			case MYSQLI_TYPE_GEOMETRY:
			default:
				return $unknown;
		}
	}

	/**
	 * Get name of the table the specified field is in
	 *
	 * @param mysqli_result $result
	 * @param $field_offset
	 * @return bool
	 */
	function mysql_field_table(mysqli_result $result, $field_offset = 0)
	{
		$info = mysqli_fetch_field_direct($result, $field_offset);
		if (empty($info->table)) {
			return false;
		}

		return $info->table;
	}

	/**
	 * Get the flags associated with the specified field in a result
	 *
	 *  @todo implement
	 *
	 * @param mysqli_result $result
	 * @param int $field_offset
	 * @return bool
	 */
	function mysql_field_flags(mysqli_result $result, $field_offset = 0)
	{
		trigger_error('This function is not implemented', E_USER_WARNING);
		return false;
	}

	/**
	 * Set result pointer to a specified field offset
	 *
	 * @param mysqli_result $result
	 * @param int $field_offset
	 * @return bool
	 */
	function mysql_field_seek(mysqli_result $result, $field_offset = 0)
	{
		return mysqli_field_seek($result, $field_offset);
	}

	/**
	 * Selects a database and executes a query on it
	 *
	 * @todo implement
	 *
	 * @param $database
	 * @param $query
	 * @param mysqli $mysqli
	 * @return bool
	 */
	function mysql_db_query($database, $query, mysqli $mysqli = null)
	{
		trigger_error('This function is deprecated since PHP 5.3.0 and therefore not implemented', E_USER_DEPRECATED);
		return false;
	}

}
?>
