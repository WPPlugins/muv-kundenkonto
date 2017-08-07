<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="loesche-konto">
    <form action="" method="post" autocomplete="off" novalidate>
        <div class="title">Konto löschen</div>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.loesche-konto.passwort.error' ) ?>">
            <label for="muv-kk-passwort">Ihr Passwort (zur Autorisierung)</label>
            <input id="muv-kk-passwort" name="muv-kk-passwort"
                   value="<?php echo esc_html( $v['passwort'] ) ?>"
                   type="password" maxlength="100" autofocus>
        </div>
        <button type="submit"><i class="fa fa-fw fa-trash"></i> Ich will mein Konto löschen!</button>
    </form>
</div>
