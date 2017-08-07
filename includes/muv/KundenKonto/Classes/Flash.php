<?php

namespace muv\KundenKonto\Classes;


defined( 'ABSPATH' ) OR exit;


class Flash extends \muv\KundenKonto\Lib\Flash {

	
	public static function echoMsg() {
				$var['msg'] = self::getMessages();
				Tools::echoTemplatePart( 'Plugin/flash.tpl.php', $var );
	}

}
