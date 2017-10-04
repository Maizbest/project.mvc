
<h1>Просмотр пользователя</h1>
<?php 
$user = $data['user'];
echo <<<ROW
    <img src="/uploads/{$user['photo']}" alt="" height="150" >
    <div><p>Имя:{$user['name']}</p></div>
    <div><p>Возраст:{$user['age']}</p></div>
    <div><p>О себе: {$user['description']}</p></div>
ROW;
?>

<h3>Изменить фото: </h3>
    <form id="form-profile"action="">
        <div >
        <label for="inputPic" >Изображение:</label>
            <div >
                <input id="sort-picture" type="file" name="pic"  id="inputPic">
            </div>
        </div>
        <div >
            <div >
                <button type="submit" id="btn-upload" >Изменить</button>
            </div>
        </div>
    </form>