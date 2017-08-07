<?php
    
    defined('ABSPATH') OR exit;
?>
<div class="aendere-pwt">
    <form action="" method="post" autocomplete="off" novalidate>
	<div class="title">Passwort ändern</div>
	 <?php  \muv\KundenKonto\Classes\Flash::echoMsg() ?>
	<div class="form-group <?php echo  \muv\KundenKonto\Classes\Flash::getValue('muv-kk.auth.aendere-vergesssenes-pwt.passwort-1.error') ?>">
	    <label for="muv-kk-passwort-1">Neues Passwort</label>
	    <input id="muv-kk-passwort-1" name="muv-kk-passwort-1" value="<?php echo esc_html($v['passwort-1']) ?>" type="password" maxlength="100" autofocus>
	</div>
	<div class="form-group <?php echo  \muv\KundenKonto\Classes\Flash::getValue('muv-kk.auth.aendere-vergesssenes-pwt.passwort-2.error') ?>">
	    <label for="muv-kk-passwort-2">Bestätigung</label>
	    <input id="muv-kk-passwort-2" name="muv-kk-passwort-2" value="<?php echo esc_html($v['passwort-2']) ?>" type="password" maxlength="100">
	</div>
	<button type="submit"><i class="fa fa-fw fa-key"></i> Neues Passwort speichern</button>
    </form>
    <div>
	<span class="pull-left"><a href="<?php echo esc_url($v['link-seite']) ?>">Mit bestehendem Zugang anmelden</a></span>
	<span class="pull-right"><a href="<?php echo esc_url($v['link-erstelle-konto']) ?>">Neuen Zugang erstellen</a></span>
    </div>
</div>
