<?php

return array(
    'name' => 'RSS Reader',
    'description' => _wp('Allows you to display RSS feed on your site'),
    'version' => '1.0.0',
    'vendor' => 1010465,
    'img' => 'img/rss.png',
    'shop_settings' => true,
    'handlers' => array(
        'frontend_homepage' => 'frontendHomepage',
    )
);