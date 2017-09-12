<?php

class BlogPost
{
    var $date;
    var $ts;
    var $link;
    var $title;
    var $text;
}

class shopRssreaderPlugin extends shopPlugin
{
    private $posts = array();
    private static $plugin_reference;

    public function __construct($info)
    {
        parent::__construct($info);
        if (!self::$plugin_reference) {
            self::$plugin_reference = &$this;
        }
    }

    public static function getPluginReference()
    {
        if (!self::$plugin_reference) {
            self::$plugin_reference = wa()->getPlugin('rssreader');
        }
        return self::$plugin_reference;
    }

    public function frontendHomepage()
    {
        if ($this->getSettings("def_feed")) {
            return self::show($this->getSettings("feed_url"), $this->getSettings("feed_posts"));
        } else {
            return '';
        }
    }

    // {shopRssreaderPlugin::show('http://feeds.feedburner.com/webasystcom/ru',10,'<ul class="rss">','</ul>','<li><a href="%link%">%title%</a></li>')}
    public static function show($file_or_url, $nums = 10, $before = '<ul class="rssreader">', $after = '</ul>', $line = '<li><a href="%link%">%title%</a></li>')
    {
        $rssreader = self::getPluginReference();
        if (!$rssreader->getSettings("state"))
            return '';

        if ($cache = wa('shop')->getCache()) {
            $cache_key = 'rss_' . str_replace('/', '_', $file_or_url);
            $feed_text = $cache->get($cache_key, 'rss');
            if ($feed_text !== null) {
                return $file_or_url . $feed_text;
            }
        }

        $file_or_url = $rssreader->resolveFile($file_or_url);
        if (!($x = simplexml_load_file($file_or_url)))
            return '';

        unset($rssreader->posts);
        foreach ($x->channel->item as $item) {
            $post = new BlogPost();
            $post->date = (string)$item->pubDate;
            $post->ts = strtotime($item->pubDate);
            $post->link = (string)$item->link;
            $post->title = (string)$item->title;
            $post->text = (string)$item->description;
            $post->summary = $rssreader->summarizeText($post->text);
            $rssreader->posts[] = $post;
        }
        if (count($rssreader->posts)) {
            $num = count($rssreader->posts) > $nums ? $nums : count($rssreader->posts);
            $feed_text = $before;
            for ($i = 0; $i < $num; $i++) {
                $replaces = array(
                    '%link%' => $rssreader->posts[$i]->link,
                    '%date%' => wa_date('humandatetime', $rssreader->posts[$i]->ts),
                    '%title%' => $rssreader->posts[$i]->title,
                    '%text%' => $rssreader->posts[$i]->text,
                    '%summary%' => $rssreader->posts[$i]->summary,
                );
                $feed_text .= str_replace(array_keys($replaces), array_values($replaces), $line);
            }
            $feed_text .= $after;
            if (!empty($cache)) {
                $cache->set($cache_key, $feed_text, 1200, 'rss');
            }
        }
        return $feed_text;
    }

    private function resolveFile($file_or_url)
    {
        if (!preg_match('|^https?:|', $file_or_url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] . '/shared/xml/' . $file_or_url;
        else
            $feed_uri = $file_or_url;

        return $feed_uri;
    }

    private function summarizeText($summary)
    {
        $summary = strip_tags($summary);
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = mb_substr($summary, 0, $max_len) . '...';
        return $summary;
    }
}
