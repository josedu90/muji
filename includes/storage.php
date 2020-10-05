<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage MUJI
 * @since MUJI 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('muji_storage_get')) {
	function muji_storage_get($var_name, $default='') {
		global $MUJI_STORAGE;
		return isset($MUJI_STORAGE[$var_name]) ? $MUJI_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('muji_storage_set')) {
	function muji_storage_set($var_name, $value) {
		global $MUJI_STORAGE;
		$MUJI_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('muji_storage_empty')) {
	function muji_storage_empty($var_name, $key='', $key2='') {
		global $MUJI_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($MUJI_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($MUJI_STORAGE[$var_name][$key]);
		else
			return empty($MUJI_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('muji_storage_isset')) {
	function muji_storage_isset($var_name, $key='', $key2='') {
		global $MUJI_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($MUJI_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($MUJI_STORAGE[$var_name][$key]);
		else
			return isset($MUJI_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('muji_storage_inc')) {
	function muji_storage_inc($var_name, $value=1) {
		global $MUJI_STORAGE;
		if (empty($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = 0;
		$MUJI_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('muji_storage_concat')) {
	function muji_storage_concat($var_name, $value) {
		global $MUJI_STORAGE;
		if (empty($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = '';
		$MUJI_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('muji_storage_get_array')) {
	function muji_storage_get_array($var_name, $key, $key2='', $default='') {
		global $MUJI_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($MUJI_STORAGE[$var_name][$key]) ? $MUJI_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($MUJI_STORAGE[$var_name][$key][$key2]) ? $MUJI_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('muji_storage_set_array')) {
	function muji_storage_set_array($var_name, $key, $value) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if ($key==='')
			$MUJI_STORAGE[$var_name][] = $value;
		else
			$MUJI_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('muji_storage_set_array2')) {
	function muji_storage_set_array2($var_name, $key, $key2, $value) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if (!isset($MUJI_STORAGE[$var_name][$key])) $MUJI_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$MUJI_STORAGE[$var_name][$key][] = $value;
		else
			$MUJI_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('muji_storage_merge_array')) {
	function muji_storage_merge_array($var_name, $key, $value) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if ($key==='')
			$MUJI_STORAGE[$var_name] = array_merge($MUJI_STORAGE[$var_name], $value);
		else
			$MUJI_STORAGE[$var_name][$key] = array_merge($MUJI_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('muji_storage_set_array_after')) {
	function muji_storage_set_array_after($var_name, $after, $key, $value='') {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if (is_array($key))
			muji_array_insert_after($MUJI_STORAGE[$var_name], $after, $key);
		else
			muji_array_insert_after($MUJI_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('muji_storage_set_array_before')) {
	function muji_storage_set_array_before($var_name, $before, $key, $value='') {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if (is_array($key))
			muji_array_insert_before($MUJI_STORAGE[$var_name], $before, $key);
		else
			muji_array_insert_before($MUJI_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('muji_storage_push_array')) {
	function muji_storage_push_array($var_name, $key, $value) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($MUJI_STORAGE[$var_name], $value);
		else {
			if (!isset($MUJI_STORAGE[$var_name][$key])) $MUJI_STORAGE[$var_name][$key] = array();
			array_push($MUJI_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('muji_storage_pop_array')) {
	function muji_storage_pop_array($var_name, $key='', $defa='') {
		global $MUJI_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($MUJI_STORAGE[$var_name]) && is_array($MUJI_STORAGE[$var_name]) && count($MUJI_STORAGE[$var_name]) > 0) 
				$rez = array_pop($MUJI_STORAGE[$var_name]);
		} else {
			if (isset($MUJI_STORAGE[$var_name][$key]) && is_array($MUJI_STORAGE[$var_name][$key]) && count($MUJI_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($MUJI_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('muji_storage_inc_array')) {
	function muji_storage_inc_array($var_name, $key, $value=1) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if (empty($MUJI_STORAGE[$var_name][$key])) $MUJI_STORAGE[$var_name][$key] = 0;
		$MUJI_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('muji_storage_concat_array')) {
	function muji_storage_concat_array($var_name, $key, $value) {
		global $MUJI_STORAGE;
		if (!isset($MUJI_STORAGE[$var_name])) $MUJI_STORAGE[$var_name] = array();
		if (empty($MUJI_STORAGE[$var_name][$key])) $MUJI_STORAGE[$var_name][$key] = '';
		$MUJI_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('muji_storage_call_obj_method')) {
	function muji_storage_call_obj_method($var_name, $method, $param=null) {
		global $MUJI_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($MUJI_STORAGE[$var_name]) ? $MUJI_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($MUJI_STORAGE[$var_name]) ? $MUJI_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('muji_storage_get_obj_property')) {
	function muji_storage_get_obj_property($var_name, $prop, $default='') {
		global $MUJI_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($MUJI_STORAGE[$var_name]->$prop) ? $MUJI_STORAGE[$var_name]->$prop : $default;
	}
}
?>