<?php 
$data = $_POST;

function v($value)
{
	var_dump($value);
}


if (isset($data['buttom'])) {
	$regular_Expression = "#".$data['regExp']."#su"; //Принимаем из формы регулярку
	$str = $data['string']; //Принимаем из формы строку 
	$whatReplace = $data['whatReplace'];

	v($regular_Expression);
	v($str);
	v($whatReplace);

	if (isset($data['packetRadio'])) {
		//Действия если указаны краманы 
	}
	echo preg_replace($regular_Expression, $whatReplace, $str);
	echo "<br><a href='index.php'>Вернуться на главную</a>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>preg_replace</title>
</head>
<body>
	<form action="#" method="POST">
		Строка : <br> <textarea name="string" id="" cols="30" rows="10"></textarea> <br><br>
		Регулярное выражение : <br> <input type="text" name="regExp"><br><br>
		На что заменить: <br> <input type="text" name="whatReplace"><br><br>
		<div>
			Включить карманы ? <br> 
			<input type="radio" name="packetRadio"> <br> 
			<input type="text" name="packet">

			<br> <br>
			Использовать: 
			<select name='useTool'>
				<option value="1">preg_replace</option>
				<option value="2"></option>
				<option value="3"></option>
			</select>
		</div>
		<input type="submit" name="buttom">
	</form>
</body>
</html>
<?php 
}
<\stitle[^>]*?>(.*?)</title[^>]*?>