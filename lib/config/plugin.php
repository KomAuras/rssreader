<?php
/**
 * @author Плагины Вебасист <info@wa-apps.ru>
 * @link http://wa-apps.ru/
 */
return array(
    'name' => 'RSS Reader',
    'description' => 'Позволяет отобразить RSS feed на вашем сайте',
    'version'=>'1.0.0',
    'vendor' => 666,
    'img'=>'img/rss.png',
    'shop_settings' => true,
    'handlers' => array(
        'frontend_homepage' => 'frontendHomepage',
    )
);