<?php

class shopRssreaderPluginBackendActions extends waJsonActions
{
    /**
     *  Сохраняем настройки плагина
     */
    public function saveSettingsAction()
    {
        //waLog::log("", 'debug.log');
        try {
            $post = warequest::post('settings');

            $settings = array(
                'state' => isset($post['state']) ? 1 : 0,
                'def_feed' => isset($post['def_feed']) ? 1 : 0,
                'feed_url' => $post['feed_url'],
                'feed_posts' => $post['feed_posts'],
            );

            wa()->getplugin('rssreader')->savesettings($settings);
            $this->response = array('msg' => _wp('saved'));

        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}
