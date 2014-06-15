<?php

/**
 * Class GooglePositionFinder
 */
class GooglePositionFinder
{

    public $source;
    public $data = array();
    public $find = 0;

    /**
     * @param        $keyword
     * @param string $page
     * @param null   $country
     * @return array
     */
    public function query($keyword, $page = '0', $domain = 'com')
    {

        $source = $this->getSource($keyword, $page, $domain);
        $data = $this->parse($source);
        return $data;
    }

    /**
     * @param $source
     * @return array
     */
    public function parse($source)
    {

        preg_match_all('/<h3 class="r"><a href=\"(.+?)\"(.+?)>(.+?)<\/a><\/h3>/', $source, $result);

        $title = array_map('strip_tags', $result[3]);
        foreach ($result[1] as $key => $url) {
            $this->data[] = array('url' => $url, 'title' => $title[$key]);
        }
        return $this->data;


    }

    /**
     * @param      $keyword
     * @param      $page
     * @param null $country
     * @return mixed|string
     */
    public function getSource($keyword, $page, $domain)
    {

        // page
        if ($page > 1) $page = ($page - 1) * 10;
        else $page = '0';

        // $useragent = "Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.14912/870; U; id) Presto/2.4.15";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://www.google.' . $domain . '/search?q=' . rawurlencode($keyword) . '&start=' . $page);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // set user agent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $source = curl_exec($ch);
        curl_close($ch);
        $this->source = str_replace(array("\n", "\r", "\t"), NULL, $source);
        return $this->source;

    }

    /**
     * Domaine göre eşleşen
     *
     * @param $domain
     * @return int
     */
    public function matchResult($domain)
    {
        foreach ($this->data as $result) {
            if (strstr($result['url'], $domain))
                $this->find += 1;
        }
        return $this->find;
    }

}
