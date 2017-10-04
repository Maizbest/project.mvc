<h1>Мои данные</h1>
<?php 
$user = $data['user'];
echo <<<ROW
    <img src="/uploads/{$user['photo']}" alt="" height="150" >
    <div><p>Имя:{$user['name']}</p></div>
    <div><p>Возраст:{$user['age']}</p></div>
    <div><p>О себе: {$user['description']}</p></div>
ROW;
?>
<h3>Изменить данные: </h3>
    <form id="form-profile"action="">
        <div>
        <label for="inputName" >Ваше имя:</label>
        <div >
            <input type="text" name="name"  id="inputName" placeholder="Имя">
        </div>
        </div>
        <div >
        <label for="inputAge" >Ваш возраст:</label>
        <div >
            <input type="text" name="age"  id="inputAge" placeholder="Возраст">
        </div>
        </div>
        <div >
        <label for="inputDesc" >О себе:</label>
        <div >
            <input type="textarea" name="desc"  id="inputDesc" placeholder="О себе">
        </div>
        </div>
        <div >
        <label for="sort-picture" >Изображение:</label>
        <div >
            <input id="sort-picture" type="file" name="pic">
        </div>
        </div>
        <div >
        <div >
            <button type="submit" id="btn-upload" >Изменить</button>
        </div>
        </div>
    </form>