<?php 
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
$links = [];
	for ($i=0; $i <40 ; $i++) { 
		$link = 'https://prnt.sc/'.str_shuffle('nmlig8');
		var_dump($link);
		$page = curlGetContents($link, 'google.com', 1, 0);
		preg_match_all('#<img\s?class\s?=\s?["\'].*?["\']\s*src=["\'](https?://.*?)["\']\s?crossorigin#su', $page['html'], 
			$pageLinkImage);
		$tare = $pageLinkImage[1][0];
		echo $tare;
		echo $page['code'];
		if ($page['code'] != 403) {
			$links[] = $tare;
		}
		}
	
	foreach ($links as $value) {
		echo "<button><a href='$value' target='_blank'>Сюда</a></button>";
	}
	