<?php

if($admin_url = getenv('PRODUCTION_DOMAIN')) :
$env_notification_link = <<<HTML
    <a style="display: inline-block; padding: 0; color: #fff; font-weight: bold; text-decoration: underline;" href="$admin_url{$_SERVER['REQUEST_URI']}">
        Click here to update content on your live site.
    </a>
HTML;
endif;
    
$env_notification = <<<HTML
    <div style="height: 32px; padding: 0 10px; color: #fff; text-align: center; background-color: #d54e21;">
        It looks like you're on the <strong style="font-weight: bold;">STAGING</strong> site.
        $env_notification_link
    </div>
HTML;

return $env_notification;