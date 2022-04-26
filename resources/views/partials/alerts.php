<?php

$flash = load('session')->group('flash');

?>

<div class="session-alert" id="session-alert">
    <?php if ($flash->has('success')) : ?>
        <div class="session-alert-box alert-success">
            <?= $flash->id('success')->data ?>
        </div>
    <?php endif; ?>

    <?php if ($flash->has('error')) : ?>
        <div class="session-alert-box alert-danger">
            <?= $flash->id('error')->data ?>
        </div>
    <?php endif; ?>
</div>
