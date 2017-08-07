<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="aendere-email">
    <form action="" method="post" autocomplete="off" novalidate>
        <div class="title">E-Mail Adresse ändern</div>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.aendere-email.passwort.error' ) ?>">
            <label for="muv-kk-passwort">Ihr Passwort (zur Autorisierung)</label>
            <input id="muv-kk-passwort" name="muv-kk-passwort"
                   value="<?php echo esc_html( $v['passwort'] ) ?>"
                   type="password" maxlength="100" autofocus>
        </div>
        <hr>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.kunde.aendere-email.email-neu.error' ) ?>">
            <label for="muv-kk-email-neu">Neue E-Mail Adresse</label>
            <input id="muv-kk-email-neu" name="muv-kk-email-neu" value="<?php echo esc_html( $v['email-neu'] ) ?>"
                   type="text" maxlength="100">
        </div>
        <button type="submit"><i class="fa fa-fw fa-envelope"></i> Neue E-Mail Adresse speichern</button>
    </form>
</div>
