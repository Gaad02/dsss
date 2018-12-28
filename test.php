<?php
if (isset($_REQUEST['buttomName'])) {
  require_once('phpQuery/phpQuery.php');

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

  $link = $_REQUEST['link'];

  $paeg = curlGetContents($link, 'google.com', 0, 0);
  $str = $paeg['html'];
  $objeckPage = phpQuery::newDocument($str);
  switch ($_REQUEST['elems']) {
    case 'title':
      $elems  = $objeckPage->find('title');
      break;

    case 'body':
          $elems  = $objeckPage->find('body');
        break;
  }
  $text = $elems->html();
  var_dump($text);

} else {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
  	<meta charset="UTF-8">
  	<title>Тестовая html страница</title>
  </head>
  <body>
  	<form action="#" method="POST">
      Ссылка: <input type="text" name="link">
      <select name="elems">
        <option value="title">title</option>
        <option value="body">body</option>
      </select>
  		<input type="submit" name="buttomName">
  	</form>
  </body>
  </html>
  <?
}
