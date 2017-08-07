<?php

namespace muv\KundenKonto\Lib;


defined( 'ABSPATH' ) OR exit;


class Tools {

	
	public static function getPageUrl( $mitParameter = false ) {
		global $wp;
		if ( empty( $wp->request ) ) {
						return home_url( '?' . $wp->query_string );
		} else {
						if ( $mitParameter ) {
				return filter_input( INPUT_SERVER, 'REQUEST_URI' );
			} else {
				return home_url( $wp->request );
			}
		}
	}

	
	public static function locateTemplate( $templateName ) {

				$templateFile = locate_template( [ $templateName, '/muv-kundenkonto/' . $templateName ] );

		
		if ( empty( $templateFile ) ) {
			$templateFile = MUV_KK_DIR . '/templates/' . $templateName;
		}

				return $templateFile;
	}

	
	public static function locateTemplatePart( $templateName ) {
				return self::locateTemplate( $templateName );
	}

	
	public static function echoTemplate( $templateName, $v = null ) {
		

				require( self::locateTemplate( $templateName ) );
	}

	
	public static function echoTemplatePart( $templateName, $v = null ) {
		

				echo '<div class="muv muv-kundenkonto">';
				require( self::locateTemplatePart( $templateName ) );
				echo '</div>';
	}

	
	public static function getTemplateContent( $templateName, $v = null ) {
		
		ob_start();
		self::echoTemplate( $templateName, $v );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	
	public static function getTemplatePartContent( $templateName, $v = null ) {
		
		ob_start();
		self::echoTemplatePart( $templateName, $v );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	
	public static function tooltip( $tip ) {
		
		$tip = str_replace( '"', '&quot;', $tip );
		$tip = str_replace( "'", '&apos;', $tip );

				echo ' <span class="tipso" data-tipso="' . $tip . '" style="float:left;margin-right:10px;margin-top:5px;border-bottom:none"><i class="fa fa-fw fa-question-circle"></i></span>';
	}
}
