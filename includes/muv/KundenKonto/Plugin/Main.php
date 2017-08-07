<?php

namespace muv\KundenKonto\Plugin;


defined( 'ABSPATH' ) OR exit;


class Main {

	
	public static function init() {

		
		add_action( 'init', array(self::class, 'loadPluginTextdomain'));

		
		Cron::init();

		if ( is_admin() ) {
			\muv\KundenKonto\Admin\Main::init();
		} else {
			\muv\KundenKonto\Frontend\Main::init();
		}
	}

	public static function loadPluginTextdomain() {
				$locale = apply_filters('plugin_locale', get_locale(), 'muv-kundenkonto');

		load_textdomain('muv-kundenkonto', WP_LANG_DIR . '/plugins/muv-kundenkonto' . $locale . '.mo');
		load_plugin_textdomain('muv-kundenkonto', FALSE, '/muv-kundenkonto/languages/');
	}

}
