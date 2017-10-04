//функция получение объекта с значениями полей формы
function getFormData(selector) {
    var paramObj = {};
    $.each($(selector).serializeArray(), function(_, kv) {
        if (paramObj.hasOwnProperty(kv.name)) {
            paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
            paramObj[kv.name].push(kv.value);
        }
        else {
            paramObj[kv.name] = kv.value;
        }
    });
    return paramObj;
}


// обработчик формы регистрации и авторизации
$('#btn-submit').on('click', function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $.ajax({
            method: "POST",
            url: form.attr('id'),
            data: {
                dataJson: JSON.stringify(getFormData(form))
            },
            success: function(res) {
                    parsedRes = JSON.parse(res);
                   
                    if (parsedRes.success) {
                        location.reload();
                    } else {
                        alert(parsedRes.errors[0]);
                    }
                }
        });
});

//обработчик формы профиля
$('#btn-upload').on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents('form');
    var inputs_data = JSON.stringify(getFormData(form));
    var file_data = $('#sort-picture').prop('files')[0];
    var form_data = new FormData();
    form_data.append('image', file_data);
    form_data.append('inputs', inputs_data);

    $.ajax({
            method: "POST",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            url: location.href,
            data: form_data,
            success: function(res) {
                    console.log(res);
                    parsedRes = JSON.parse(res);                   
                    if (parsedRes.success) {
                        location.reload();
                    } else if (parsedRes.errors[0]) {
                        alert(parsedRes.errors[0]);
                    }                    
                }
        });    
});

$('.delete').on('click', function (e) {
    e.preventDefault();

    var path = './'; //скрипт рег-ии или автор-ии
    var data,
        form;

    if ( $(this).parents('.table-pics').length !== 0) {
        path += 'scripts/del-pic.php';
    } else if ( $(this).parents('.table-users').length !== 0) {
        path += 'scripts/del-user.php';
    } 

    $.ajax({
            method: "POST",
            url: path,
            data: {
                id: $(this).attr('data')
             } ,
            success: function(res) {
                    if (res) {
                        alert(res); 
                    } else {
                        location.reload();
                    }                    
                }
        });     
});

$('#exit').on('click', function (e) {
    e.preventDefault();
    location.href('auth/exit');     
});







