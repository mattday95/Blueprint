<div style="margin-bottom: 10px; padding: 10px; border: solid 1px #ebccd1; font-size: 14px; text-align: center; background-color: #f2dede;">
    <p>It looks like you're on the <strong>STAGING</strong> site.</p>
    <?php if ($production_domain = getenv('PRODUCTION_DOMAIN')): ?>
        <a style="display: block; margin-top: 10px;" href="<?php echo "$production_domain{$_SERVER['REQUEST_URI']}" ?>">
            Click here to update content on your live site.
        </a>
    <?php endif;?>
</div>