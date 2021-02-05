<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage MAXINET
 * @since MAXINET 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('maxinet_storage_get')) {
	function maxinet_storage_get($var_name, $default='') {
		global $MAXINET_STORAGE;
		return isset($MAXINET_STORAGE[$var_name]) ? $MAXINET_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('maxinet_storage_set')) {
	function maxinet_storage_set($var_name, $value) {
		global $MAXINET_STORAGE;
		$MAXINET_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('maxinet_storage_empty')) {
	function maxinet_storage_empty($var_name, $key='', $key2='') {
		global $MAXINET_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($MAXINET_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($MAXINET_STORAGE[$var_name][$key]);
		else
			return empty($MAXINET_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('maxinet_storage_isset')) {
	function maxinet_storage_isset($var_name, $key='', $key2='') {
		global $MAXINET_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($MAXINET_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($MAXINET_STORAGE[$var_name][$key]);
		else
			return isset($MAXINET_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('maxinet_storage_inc')) {
	function maxinet_storage_inc($var_name, $value=1) {
		global $MAXINET_STORAGE;
		if (empty($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = 0;
		$MAXINET_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('maxinet_storage_concat')) {
	function maxinet_storage_concat($var_name, $value) {
		global $MAXINET_STORAGE;
		if (empty($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = '';
		$MAXINET_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('maxinet_storage_get_array')) {
	function maxinet_storage_get_array($var_name, $key, $key2='', $default='') {
		global $MAXINET_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($MAXINET_STORAGE[$var_name][$key]) ? $MAXINET_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($MAXINET_STORAGE[$var_name][$key][$key2]) ? $MAXINET_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('maxinet_storage_set_array')) {
	function maxinet_storage_set_array($var_name, $key, $value) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if ($key==='')
			$MAXINET_STORAGE[$var_name][] = $value;
		else
			$MAXINET_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('maxinet_storage_set_array2')) {
	function maxinet_storage_set_array2($var_name, $key, $key2, $value) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if (!isset($MAXINET_STORAGE[$var_name][$key])) $MAXINET_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$MAXINET_STORAGE[$var_name][$key][] = $value;
		else
			$MAXINET_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('maxinet_storage_merge_array')) {
	function maxinet_storage_merge_array($var_name, $key, $value) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if ($key==='')
			$MAXINET_STORAGE[$var_name] = array_merge($MAXINET_STORAGE[$var_name], $value);
		else
			$MAXINET_STORAGE[$var_name][$key] = array_merge($MAXINET_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('maxinet_storage_set_array_after')) {
	function maxinet_storage_set_array_after($var_name, $after, $key, $value='') {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if (is_array($key))
			maxinet_array_insert_after($MAXINET_STORAGE[$var_name], $after, $key);
		else
			maxinet_array_insert_after($MAXINET_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('maxinet_storage_set_array_before')) {
	function maxinet_storage_set_array_before($var_name, $before, $key, $value='') {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if (is_array($key))
			maxinet_array_insert_before($MAXINET_STORAGE[$var_name], $before, $key);
		else
			maxinet_array_insert_before($MAXINET_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('maxinet_storage_push_array')) {
	function maxinet_storage_push_array($var_name, $key, $value) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($MAXINET_STORAGE[$var_name], $value);
		else {
			if (!isset($MAXINET_STORAGE[$var_name][$key])) $MAXINET_STORAGE[$var_name][$key] = array();
			array_push($MAXINET_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('maxinet_storage_pop_array')) {
	function maxinet_storage_pop_array($var_name, $key='', $defa='') {
		global $MAXINET_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($MAXINET_STORAGE[$var_name]) && is_array($MAXINET_STORAGE[$var_name]) && count($MAXINET_STORAGE[$var_name]) > 0) 
				$rez = array_pop($MAXINET_STORAGE[$var_name]);
		} else {
			if (isset($MAXINET_STORAGE[$var_name][$key]) && is_array($MAXINET_STORAGE[$var_name][$key]) && count($MAXINET_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($MAXINET_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('maxinet_storage_inc_array')) {
	function maxinet_storage_inc_array($var_name, $key, $value=1) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if (empty($MAXINET_STORAGE[$var_name][$key])) $MAXINET_STORAGE[$var_name][$key] = 0;
		$MAXINET_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('maxinet_storage_concat_array')) {
	function maxinet_storage_concat_array($var_name, $key, $value) {
		global $MAXINET_STORAGE;
		if (!isset($MAXINET_STORAGE[$var_name])) $MAXINET_STORAGE[$var_name] = array();
		if (empty($MAXINET_STORAGE[$var_name][$key])) $MAXINET_STORAGE[$var_name][$key] = '';
		$MAXINET_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('maxinet_storage_call_obj_method')) {
	function maxinet_storage_call_obj_method($var_name, $method, $param=null) {
		global $MAXINET_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($MAXINET_STORAGE[$var_name]) ? $MAXINET_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($MAXINET_STORAGE[$var_name]) ? $MAXINET_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('maxinet_storage_get_obj_property')) {
	function maxinet_storage_get_obj_property($var_name, $prop, $default='') {
		global $MAXINET_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($MAXINET_STORAGE[$var_name]->$prop) ? $MAXINET_STORAGE[$var_name]->$prop : $default;
	}
}
?>