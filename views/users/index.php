<h1>Список пользователей</h1>
<a href="/users/index/age/asc">Сортировка по возрастанию по возраста</a>
<a href="/users/index/age/desc">Сортировка по убыванию по возраста</a><br><br>
<?php 
    $users = array_reverse($data['users']);
    while($user = array_pop($users)) {
        echo $user['id'] . '. ';
        echo $user['name'] . '<br>';
        echo '<img src="/uploads/' . $user['photo'] . '" alt="" height="50"><br>';
        echo $user['age'] .' лет, '. ($user['age'] >= 18 ? 'совершеннолетний ' : 'несовершеннолетний ');
        echo '<br><a href=/users/show/' . $user['id'] .'>Подробнее</a>';
        echo '<br><br>';
    }
?>

