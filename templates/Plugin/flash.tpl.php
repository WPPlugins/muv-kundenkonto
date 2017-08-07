<?php

defined( 'ABSPATH' ) OR exit;
?>
<?php foreach ( $v['msg'] as $msg ) { ?>
    <div class="alert alert-<?php echo $msg['type'] ?>"> <?php echo $msg['text'] ?> </div>
<?php } ?>
