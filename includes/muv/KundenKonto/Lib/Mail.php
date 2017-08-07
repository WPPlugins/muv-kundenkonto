<?php

namespace muv\KundenKonto\Lib;


defined( 'ABSPATH' ) OR exit;


class Mail {
	

	private static $plainMessage = '';
	
	private static $htmlMessage = '';
	
	private static $fromMail = '';
	
	private static $fromName = '';
	
	private static $embImg = array();

	
	public static function send( $fromMail, $fromName, $to, $subject, $htmlMessage, $plainMessage, $headers = '', $attachments = array() ) {

								
		
		self::$htmlMessage  = $htmlMessage;
		self::$plainMessage = $plainMessage;
		self::$fromMail     = trim( $fromMail );
		self::$fromName     = trim( $fromName );
		self::$embImg       = $attachments;


		
		add_filter( 'wp_mail_content_type', array( self::class, 'getContentType' ) );
		add_action( 'phpmailer_init', array( self::class, 'initMailer' ) );
		if ( ! empty( self::$fromMail ) ) {
			add_filter( 'wp_mail_from', array( self::class, 'setFromMail' ) );
		}
		if ( ! empty( self::$fromName ) ) {
			add_filter( 'wp_mail_from_name', array( self::class, 'setFromName' ) );
		}

		
		$msg = ( ! empty( $htmlMessage ) ) ? $htmlMessage : $plainMessage;

		
		if ( ! empty( $htmlMessage ) && empty( $plainMessage ) ) {
			
			foreach ( self::$embImg as $k => $v ) {
				$msg = str_replace( 'cid:' . $k, $v, $msg );
			}
		}

		$return = wp_mail( $to, $subject, $msg, $headers, $attachments );

		remove_filter( 'wp_mail_content_type', array( self::class, 'getContentType' ) );

		remove_action( 'phpmailer_init', array( self::class, 'handleMultipart' ) );

		if ( ! empty( self::$fromMail ) ) {
			remove_filter( 'wp_mail_from', array( self::class, 'setFromMail' ) );
		}
		if ( ! empty( self::$fromName ) ) {
			remove_filter( 'wp_mail_from_name', array( self::class, 'setFromName' ) );
		}

		return $return;
	}

	
	public static function getContentType() {
		if ( empty( self::$htmlMessage ) ) {
			return 'text/plain';
		}
		if ( empty( self::$plainMessage ) ) {
			return 'text/html';
		}

		return 'multipart/alternative';
	}

	
	public static function initMailer( $phpMailer ) {
		
		if ( self::getContentType() === 'multipart/alternative' ) {
			$phpMailer->AltBody = self::$plainMessage;

			
			foreach ( self::$embImg as $k => $v ) {
				if ( ! empty( $v ) ) {
					$content = wp_remote_retrieve_body( wp_remote_get( $v ) );
					$phpMailer->AddStringEmbeddedImage( $content, $k );
				}
			}
		}


		
		$phpMailer->CharSet = get_bloginfo( 'charset' );

		return $phpMailer;
	}

	
	public static function setFromMail() {
		return self::$fromMail;
	}

	
	public static function setFromName() {
		return self::$fromName;
	}

}
