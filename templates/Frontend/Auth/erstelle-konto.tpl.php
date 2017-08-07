<?php

defined( 'ABSPATH' ) OR exit;
?>
<div class="erstelle-konto">
    <form action="<?php echo esc_url( $v['link-erstelle-konto'] ) ?>" method="post" autocomplete="off" novalidate>
        <div class="title">Neuen Zugang erstellen</div>
        Bitte geben Sie die von Ihnen gewünschten Daten für den neu zu erstellenden Zugang ein.<br><br>
		<?php \muv\KundenKonto\Classes\Flash::echoMsg() ?>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.auth.erstelle-konto.login.error' ) ?>">
            <label for="muv-kk-login">E-Mail</label>
            <input id="muv-kk-login" name="muv-kk-login" value="<?php echo esc_html( $v['login'] ) ?>" type="text"
                   maxlength="100" autofocus autocomplete="off">
        </div>
        <div class="form-group <?php echo \muv\KundenKonto\Classes\Flash::getValue( 'muv-kk.auth.erstelle-konto.passwort.error' ) ?>">
            <label for="muv-kk-passwort">Passwort</label>
            <input id="muv-kk-passwort" name="muv-kk-passwort" type="password" value="" autocomplete="off">
        </div>
        <button type="submit"><i class="fa fa-fw fa-user-plus"></i> Neuen Zugang erstellen</button>
    </form>
    <div>
        <span class="pull-left"><a href="<?php echo esc_url( $v['link-pwt-vergessen'] ) ?>">Passwort vergessen?</a></span>
        <span class="pull-right"><a href="<?php echo esc_url( $v['link-seite'] ) ?>">Mit bestehendem Zugang anmelden</a></span>
    </div>
</div>
