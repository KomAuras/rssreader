<?php

class shopRssreaderPluginSettingsAction extends waViewAction
{
    /**
     *  Передаем настройки плагина в нашу вьюшку
     */
    public function execute()
    {
        //waLog::log("", 'debug.log');
        $this->view->assign('settings', wa('shop')->getPlugin('rssreader')->getSettings());
    }

}
