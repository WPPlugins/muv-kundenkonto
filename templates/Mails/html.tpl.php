<?php

defined( 'ABSPATH' ) OR exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        body {
            font-family: 'Helvetica Neue Light', Helvetica, Arial, sans-serif;
            font-size: 16px;
            padding: 0px;
            margin: 0px;
            font-weight: 300;
            line-height: 100%;
            text-align: left;
            color: <?php echo sanitize_hex_color(get_option('muv-kk-email-vorlage-color-text')); ?>;
            line-height: 125%;
        }

        h1 {
            font-size: 26px;
            font-weight: 300;
        }

        a, a:focus, a:hover, a:active {
            color: <?php echo sanitize_hex_color(get_option('muv-kk-email-vorlage-color')); ?>;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-body' ) ); ?>">
<br>
<table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">
            <br><?php if ( $v['has-header-logo'] ) {
				echo '<img src="cid:email-logo"/>';
			} ?><br/><br/></td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-color' ) ); ?>"
            style="font-size:1px"><span style="font-size:1px;line-height:1px">&nbsp;</span></td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-color' ) ); ?>"
            style="font-size:1px"><span style="font-size:1px;line-height:1px">&nbsp;</span></td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-color' ) ); ?>"
            style="font-size:1px"><span style="font-size:1px;line-height:1px">&nbsp;</span></td>
    </tr>
    <tr>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">&nbsp;
        </td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">
            <br/>
			<?php echo nl2br( $v['html-content'] ); ?>
            <br/>
            <br/>
        </td>
        <td bgcolor="<?php echo sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content' ) ); ?>">&nbsp;
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <br/>
            <small><?php echo nl2br( get_option( 'muv-kk-email-vorlage-footer' ) ); ?></small>
            <br/>
            <br/>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
