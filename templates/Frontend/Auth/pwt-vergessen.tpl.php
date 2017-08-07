<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="pwt-vergessen">
    <form action="<?php echo esc_url( $v['link-pwt-vergessen'] ) ?>" method="post" autocomplete="off" novalidate>
        <div class="title">Passwort vergessen?</div>
        Gerne senden wir Ihnen weitere Informationen per E-Mail zu.<br><br>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.auth.pwt-vergessen.login.error' ) ?>">
            <label for="muv-kk-login">E-Mail</label>
            <input id="muv-kk-login" name="muv-kk-login" value="<?php echo esc_html( $v['login'] ) ?>" type="text"
                   maxlength="100" autofocus autocomplete="off">
        </div>
        <button type="submit"><i class="fa fa-fw fa-envelope"></i> Informationen zusenden</button>
    </form>
    <div>
        <span class="pull-left"><a
                    href="<?php echo esc_url( $v['link-seite'] ) ?>">Mit bestehendem Zugang anmelden</a></span>
        <span class="pull-right"><a href="<?php echo esc_url( $v['link-erstelle-konto'] ) ?>">Neuen Zugang erstellen</a></span>
    </div>
</div>
