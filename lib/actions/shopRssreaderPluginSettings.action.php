<?php
/**
 * @author Плагины Вебасист <info@wa-apps.ru>
 * @link http://wa-apps.ru/
 */
class shopRssreaderPluginSettingsAction extends waViewAction
{
    public function execute()
    {
        $plugin = wa('shop')->getPlugin('rssreader');
        $this->view->assign('settings', $plugin->getSettings());
    }

}
