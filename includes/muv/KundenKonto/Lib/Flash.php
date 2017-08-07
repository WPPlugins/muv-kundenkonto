<?php

namespace muv\KundenKonto\Lib;


defined( 'ABSPATH' ) OR exit;


class Flash {

	
	private static $initDone = false;

	
	private static function init() {
		
		if ( self::$initDone ) {
			return;
		}

		
		if ( ! isset( $_SESSION['muv']['flash']['msg'] ) ) {
			$_SESSION['muv']['flash']['msg'] = array();
		}
		if ( ! isset( $_SESSION['muv']['flash']['key'] ) ) {
			$_SESSION['muv']['flash']['key'] = array();
		}

		self::$initDone = true;
	}

	
	public static function addMessage( $text, $type = 'info' ) {
				self::init();

		
		$key                                     = md5( $text );
		$msg                                     = array( 'text' => $text, 'type' => $type );
		$_SESSION['muv']['flash']['msg'][ $key ] = $msg;
	}

	
	public static function getMessages() {
				self::init();

		$out = $_SESSION['muv']['flash']['msg'];
		self::clearMessages();

		return $out;
	}

	
	public static function clearMessages() {
				self::init();
		$_SESSION['muv']['flash']['msg'] = array();
	}

	
	public static function hasMessages() {
				self::init();
		$msg = $_SESSION['muv']['flash']['msg'];

		return ! empty( $msg );
	}

	
	public static function setValue( $key, $value = null ) {
				self::init();
		$_SESSION['muv']['flash']['key'][ $key ] = $value;
	}

	
	public static function getValue( $key, $defValue = '' ) {
				self::init();
		if ( empty( $_SESSION['muv']['flash']['key'][ $key ] ) ) {
			return $defValue;
		}
		$out = $_SESSION['muv']['flash']['key'][ $key ];
		unset( $_SESSION['muv']['flash']['key'][ $key ] );

		return $out;
	}

	
	public static function clearValues() {
				self::init();
		$_SESSION['muv']['flash']['key'] = array();
	}

}
