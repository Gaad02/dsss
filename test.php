<?php
$data = $_GET;
if (!isset($data['nameFile'])) {

  function file_error($number = 3)
  {
    switch ($number) {
      case '1':
        echo 'Файл отсутстует в директории';
        break;

      case '2':
       echo 'Херня с файлом происходит<br>';
        break;

      case '3':
        echo '404 :)<br>';
        break;

    }
  }

  function includeFiles($arrayNames = [])
  {
    if (!empty($arrayNames)) {
      foreach ($arrayNames as $key => $value) {
        if (file_exists("$value")) {
          checkFile("$value");
        } else {
          file_error(1);
        }
      }
    } else {
      return 0;
    }
  }

  function checkFile($nameFile)
  {
    if (!empty($nameFile)) {
      $contentFile = file_get_contents("$nameFile");
      if (empty($contentFile)) {
        echo 'Файл - '.$nameFile.' <b>пустой</b><br>';
      } else {
        echo 'Файл - '.$nameFile.' - '.filesize("$nameFile").' (Байта)<br>';
      }
    } else {
      return 0;
    }
  }

  $arrayNames = ['work1.php', 'work2.txt', 'work3.php'];
  includeFiles($arrayNames);


} else {
  if (!file_exists('dir')) {
    mkdir('dir');
  }

  $nameFile = $data['nameFile'].'.php';
  var_dump($nameFile);
  file_put_contents("dir/$nameFile", ' ');
  if (file_exists("$nameFile")) {
    echo 'Создание прошло успешно';
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form action="index.php" method="get">
    <input type="text" name="nameFile">
    <input type="submit" name="nameButtom" value="Создать файл">
  </form>
</body>
</html>
