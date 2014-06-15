/**
 * Class GooglePositionFinder
 */
class GooglePositionFinder {

    /**
     * @param        $keyword
     * @param string $page
     * @param null   $country
     * @return array
     */
    public function query($keyword, $page = '0', $country = null)
	{
	
		$source = $this->getSource($keyword, $page, $country);
		$data = $this->parse($source);
		return $data;
		
	}

    /**
     * @param $source
     * @return array
     */
    public function parse($source)
	{
	
		preg_match_all('/<h3 class="r"><a href="(.*?)">(.*?)<\/a><\/h3>/', $source, $result);
		$title = array_map('strip_tags', $result[2]);
		foreach ( $result[1] as $key => $url ){
			parse_str(str_replace('/url?', '', $url), $output);
			$data[] = array('url' => $output['q'], 'title' => $title[$key]);
		}
		return $data;
		
	}

    /**
     * @param      $keyword
     * @param      $page
     * @param null $country
     * @return mixed|string
     */
    public function getSource($keyword, $page, $country = null)
	{
		
		// page
		if ( $page > 1 ) $page = ($page - 1) * 10;
		else $page = '0';
		
		// country
		if ( $country ) $country = '.'.$country;
		
		$source = file_get_contents('https://www.google.com'.$country.'/search?q='.rawurlencode($keyword).'&start='.$page);
		$source = str_replace(array("\n","\r","\t"), NULL, $source);
		return $source;
		
	}
	
}

$google = new GooglePositionFinder();
$data = $google->query('uzman cevap', 1, 'tr');
print_r($data);
