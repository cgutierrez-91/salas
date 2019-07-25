<span class="text-danger"><span class="mdi mdi-close-circle"></span> <?php echo $mensaje; ?></span><?php
if ( isset($focus) ) {
    ?>
<script>$('#<?php echo $focus;?>').focus();</script>
    <?php
}

if ( isset($reset) && $reset != '' ) {
    ?>
<script>document.getElementById('<?php echo $reset;?>').reset();</script>
    <?php
}