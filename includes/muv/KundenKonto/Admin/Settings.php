<?php

namespace muv\KundenKonto\Admin;


defined( 'ABSPATH' ) OR exit;


class Settings {

	
	public static function init() {
				Settings\Allgemein::init();
		Settings\EMail::init();
		Settings\Benachrichtigungen::init();
		Settings\API::init();
	}

	
	public static function handleSettings() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sie haben nicht das Recht, diese Seite zu sehen!', 'muv-kundenkonto' ) );
		};
				echo '<div class="wrap muv muv-kundenkonto">';

		echo '<h1><i class="fa fa-fw fa-cog"></i> ';
		_e( 'Einstellungen', 'muv-kundenkonto' );
		echo '</h1>';

		settings_errors();

		
		$activeTab = filter_input( INPUT_GET, 'tab' );
		if ( empty( $activeTab ) ) {
			$activeTab = 'allgemein';
		}
		
		echo '<h2 class="nav-tab-wrapper">';

		echo '<a href="?page=muv-kk-einstellungen&tab=allgemein" class="nav-tab ';
		if ($activeTab === 'allgemein') {
			echo 'nav-tab-active';
		};
		echo '"><i class="fa fa-fw fa-cogs"></i> ';
		echo __( 'Allgemein', 'muv-kundenkonto' );
		echo '</a>';

		echo '<a href="?page=muv-kk-einstellungen&tab=email"" class="nav-tab ';
		if ($activeTab === 'email') {
			echo 'nav-tab-active';
		};
		echo '"><i class="fa fa-fw fa-envelope"></i> ';
		echo __( 'E-Mail', 'muv-kundenkonto' );
		echo '</a>';

		echo '<a href="?page=muv-kk-einstellungen&tab=benachrichtigungen" class="nav-tab ';
		if ($activeTab === 'benachrichtigungen') {
			echo 'nav-tab-active';
		};
		echo '"><i class="fa fa-fw fa-bullhorn"></i> ';
		echo __( 'Benachrichtigungen', 'muv-kundenkonto' );
		echo '</a>';

		
		if (defined('MUV_SH_API')) {
			echo '<a href="?page=muv-kk-einstellungen&tab=api" class="nav-tab ';
			if ($activeTab === 'api') {
				echo 'nav-tab-active';
			};
			echo '"><i class="fa fa-fw fa-exchange"></i> ';
			echo __( 'API', 'muv-kundenkonto' );
			echo '</a>';
		}
		echo '</h2>';

		
		switch ( $activeTab ) {
			case 'allgemein':
				Settings\Allgemein::handleSettings();
				break;
			case 'email':
				Settings\EMail::handleSettings();
				break;
			case 'benachrichtigungen':
				Settings\Benachrichtigungen::handleSettings();
				break;
			case 'api':
				Settings\Api::handleSettings();
				break;
		}

		echo '</div>'; 	}

}
