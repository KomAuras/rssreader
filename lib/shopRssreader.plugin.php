<?php
/**
 * @author Плагины Вебасист <info@wa-apps.ru>
 * @link http://wa-apps.ru/
 */
class shopRssreaderPlugin extends shopPlugin
{
    var $posts = array();

    public function frontendHomepage()
    {
        $file_or_url = $this->getSettings("feed_url");
        $file_or_url = $this->resolveFile($file_or_url);
        if (!($x = simplexml_load_file($file_or_url)))
            return;

        foreach ($x->channel->item as $item)
        {
            $post = new BlogPost();
            $post->date  = (string) $item->pubDate;
            $post->ts    = strtotime($item->pubDate);
            $post->link  = (string) $item->link;
            $post->title = (string) $item->title;
            $post->text  = (string) $item->description;

            // Create summary as a shortened body and remove images, 
            // extraneous line breaks, etc.
            $post->summary = $this->summarizeText($post->text);

            $this->posts[] = $post;
        }
        $text = "<div style='background:lightblue;'>";
        foreach($this->posts as $post)
        {
            $text .= $post->title . "<br>";
        }
        $text .= "</div>";
        return $text;
    }

    private function resolveFile($file_or_url) {
        if (!preg_match('|^https?:|', $file_or_url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $file_or_url;
        else
            $feed_uri = $file_or_url;

        return $feed_uri;
    }

    private function summarizeText($summary) {
        $summary = strip_tags($summary);

        // Truncate summary line to 100 characters
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return $summary;
    }
}

class BlogPost
{
    var $date;
    var $ts;
    var $link;

    var $title;
    var $text;
}