<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="aendere-pwt">
    <form action="" method="post" autocomplete="off" novalidate>
        <div class="title">Passwort ändern</div>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.aendere-pwt.passwort-alt.error' ) ?>">
            <label for="muv-kk-passwort-alt">Altes Passwort</label>
            <input id="muv-kk-passwort-alt" name="muv-kk-passwort-alt"
                   value="<?php echo esc_html( $v['passwort-alt'] ) ?>"
                   type="password" maxlength="100" autofocus>
        </div>
        <hr>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.aendere-pwt.passwort-1.error' ) ?>">
            <label for="muv-kk-passwort-1">Neues Passwort</label>
            <input id="muv-kk-passwort-1" name="muv-kk-passwort-1" value="<?php echo esc_html( $v['passwort-1'] ) ?>"
                   type="password" maxlength="100">
        </div>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.aendere-pwt.passwort-2.error' ) ?>">
            <label for="muv-kk-passwort-2">Bestätigung</label>
            <input id="muv-kk-passwort-2" name="muv-kk-passwort-2" value="<?php echo esc_html( $v['passwort-2'] ) ?>"
                   type="password" maxlength="100">
        </div>
        <button type="submit"><i class="fa fa-fw fa-key"></i> Neues Passwort speichern</button>
    </form>
</div>
