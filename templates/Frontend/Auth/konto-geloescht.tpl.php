<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="loesche-konto">
    <form action="/" method="get" autocomplete="off" novalidate>
        <div class="title">Konto gelöscht</div>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <button type="submit"><i class="fa fa-fw fa-home"></i> Zur Startseite</button>
    </form>
</div>
