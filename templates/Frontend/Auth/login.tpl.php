<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="login">
    <form action="<?php echo esc_url( $v['link-login'] ) ?>" method="post" autocomplete="off" novalidate>
        <div class="title">Benutzer Anmeldung</div>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.auth.do-login.login.error' ) ?>">
            <label for="muv-kk-login">E-Mail</label>
            <input id="muv-kk-login" name="muv-kk-login" value="<?php echo esc_html( $v['login'] ) ?>" type="text"
                   maxlength="100" autofocus autocomplete="off">
        </div>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.auth.do-login.passwort.error' ) ?>">
            <label for="muv-kk-passwort">Passwort</label>
            <input id="muv-kk-passwort" name="muv-kk-passwort" type="password" value="" autocomplete="off">
        </div>
        <button type="submit"><i class="fa fa-fw fa-sign-in"></i> Anmelden</button>
        <input type="hidden" name="muv-kk-seite" value="<?php echo esc_url( $v['link-seite'] ) ?>">
    </form>
    <div>
        <span class="pull-left"><a href="<?php echo esc_url( $v['link-pwt-vergessen'] ) ?>">Passwort vergessen?</a></span>
        <span class="pull-right"><a href="<?php echo esc_url( $v['link-erstelle-konto'] ) ?>">Neuen Zugang erstellen</a></span>
    </div>
</div>
