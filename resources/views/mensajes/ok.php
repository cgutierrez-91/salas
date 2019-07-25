<span class="text-success"><span class="mdi mdi-check-circle"></span> <?php echo $mensaje ?? ''; ?></span><?php
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