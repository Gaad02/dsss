<?php 

function tryLink($link, $regExp)
{
	return preg_match($regExp, $link);
}

function createLink($link, $relativeLink)
{
	return $link.$relativeLink;
}

function curlGetContents($page_url, $base_url, $pause_time, $retry)
{
    /*
    $page_url - адрес страницы-источника
    $base_url - адрес страницы для поля REFERER
    $pause_time - пауза между попытками парсинга
    $retry - 0 - не повторять запрос, 1 - повторить запрос при неудаче
    */
    $error_page = [];
    $ch = curl_init();


    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0");
    curl_setopt($ch, CURLOPT_COOKIEJAR, str_replace("\\", "/", getcwd()).'/gearbest.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, str_replace("\\", "/", getcwd()).'/gearbest.txt');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Автоматом идём по редиректам
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Не проверять SSL сертификат
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Не проверять Host SSL сертификата
    curl_setopt($ch, CURLOPT_URL, $page_url);          // Куда отправляем
    curl_setopt($ch, CURLOPT_REFERER, $base_url);      // Откуда пришли
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Возвращаем, но не выводим на экран результат

    $response['html'] = curl_exec($ch);

    $info = curl_getinfo($ch);
    if($info['http_code'] != 200 && $info['http_code'] != 404) {
        $error_page[] = array(1, $page_url, $info['http_code']);
        if($retry) {
            sleep($pause_time);
            $response['html'] = curl_exec($ch);
            $info = curl_getinfo($ch);
            if($info['http_code'] != 200 && $info['http_code'] != 404)
                $error_page[] = array(2, $page_url, $info['http_code']);
        }
    }
    $response['code'] = $info['http_code'];
    $response['errors'] = $error_page;
    curl_close($ch);
    return $response;

}
/*
function getDomen($link)
{
	preg_match_all('#http[s]?://(.+?)\.[a-zA-Z]{2,3}#su', $link, $domen);
	return $domen[1];
}
*/

//Функция возращает массив ссылок на $link
//$typeLink = 1 - значит, что мы собираем только относительные ссылки на странице
//2 - значит, что обсолютные 
function getLinksOnPage($link, $base_url = 'google.com', $typeLink = '1', $primaryLink)
{	

	$visitedLinks = [];
	$arrayLink = [];
	$array = [];
	$page = curlGetContents($link, $base_url, 1, 0);

		/*$regExp = "#<a.*?href\s?=\s?[\'\"](https?://$domen[0]\.[a-zA-Z/]{2,3}.*?)[\'\"][^>]*?>(.*?)<\s?/\s?a[^>]*?>#su";	*/

	if ($typeLink == 1) {
		/*$domen = getDomen($link);
		var_dump($getDomen);*/
		$regExp = "#<a.*?href\s?=\s?[\'\"](/.*?/?)[\'\"][^>]*?>(.*?)<\s?/\s?a[^>]*?>#su";	
	} else {
		$regExp = "#<a.*?href\s?=\s?[\'\"](https?://(.*?)\.[a-zA-Z/]{2,3}.*?)[\'\"][^>]*?>(.*?)<\s?/\s?a[^>]*?>#su";	
	}
		
	preg_match_all($regExp, $page['html'], $arrayLink);

	foreach ($arrayLink[1] as $value) {
		$value = substr($value, 1);
		$array[] = createLink($primaryLink,$value);
	}
	return $array;
		
	
}
/*
function getLinkOnAllWebSite($array, $primaryLink)
{	
	$arrayVisitedLinks = [];
	$arrayLink = [];
	foreach ($array as $value) {
		$arrayLink[] = getLinksOnPage($value, 'google.com', 1, $primaryLink);
		$arrayVisitedLinks[] = $value;
	}
	return $arrayVisitedLinks;
}*/


if (isset($_REQUEST['buttomName'])) {
	$primaryLink = $_REQUEST['primaryLink'];
	$regExp      = $_REQUEST['regExp'];
	$typeLink    = $_REQUEST['typeLink'];
	$array = getLinksOnPage($primaryLink, 'google.com', $typeLink, $primaryLink);
	var_dump($array);
	$arrayBrokenLinks = [];
	$arrayVisitedLinks = [];
	// В $array лежит массив ссылок с главной страниц сайта
	foreach ($array as &$value) {
		$truOrFalse = array_search($value, $arrayVisitedLinks);
		if (!$truOrFalse) {
			echo '<br>Посетил - '.$value;
			$page = curlGetContents($link, 'google.com', 1, 0);
			if ($page['code'] != 404) {
				$arrayVisitedLinks[] = $value;
				$arrayLinkFunction[] = getLinksOnPage($value, 'google.com', $typeLink, $primaryLink);
			} else {
				$arrayVisitedLinks[] = $value;
				$arrayBrokenLinks[] = $value;
			}
		}
	}
	echo '$arrayBrokenLinks';
	var_dump($arrayBrokenLinks);

	echo '$arrayVisitedLinks';
	var_dump($arrayVisitedLinks);

	echo '$array';
	var_dump($array);
	/*if (!empty($regExp)) {
		echo tryLink($primaryLink, $regExp);	
	}*/
	
	
} else {
	include('test2html.html');
}

