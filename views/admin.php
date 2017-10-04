<h3>Создать пользователя</h3>
    <form id="form-create" action="">
        <label for="inputLogin">Логин</label><br>
        <input type="text" name="username" id="inputLogin" placeholder="Логин"><br><br>
        <label for="inputPassword">Пароль</label><br>
        <input type="password" name="pass" id="inputPassword" placeholder="Пароль"><br><br>
        <label for="pass2">Повтор пароля</label><br>
        <input type="password" name="pass2" id="pass2" placeholder="Повтор пароля"><br><br>
        <div>
        <label for="inputName" >Имя:</label>
        <div >
            <input type="text" name="name"  id="inputName" placeholder="Имя">
        </div>
        </div>
        <div >
        <label for="inputAge" >Возраст:</label>
        <div >
            <input type="text" name="age"  id="inputAge" placeholder="Возраст">
        </div>
        </div>
        <div >
        <label for="inputDesc" >Описание:</label>
        <div >
            <input type="textarea" name="desc"  id="inputDesc" placeholder="Описание">
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
            <button type="submit" id="btn-upload" >Создать</button>
        </div>
        </div>
    </form>