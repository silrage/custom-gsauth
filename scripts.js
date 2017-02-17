var main_tasker = 'go_to_monitoring.php',
	active_form = 'login',
	username,
	password,
	email,
	login_form,
	reset_form,
	loader;

$(run);

function run() {
	login_form = $('#login_body');
	reset_form = $('#reset_body');
    new_password_form = $('#newPassword_body');
	username = $('input[name=login]');
	password = $('input[name=password]');
	email = $('input[name=email]');
    new_password = $('input[name=new_password]');
	loader = $('<div class="loader">');
    // Проверяем параметры URL
    if( QueryString().change_password ) showNewPasswordPage();
}

function QueryString() {
  var query_string = {};
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
        // If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
        // If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
      query_string[pair[0]] = arr;
        // If third or later entry with this name
    } else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  }
  // Thanks to jcubic: http://stackoverflow.com/users/387194/jcubic
  return query_string;
}
// Old method
// function detectHash(url){
//     if(url != null) {
//         // Парсим команду из хэша и выполняем её
//         var link = url.substr(1,url.length),
//             command = link.substr(0,  link.indexOf('?'));
//         switch (command) {
//             case "newPassword":
//                 showNewPasswordPage();
//                 break;
//         }
//     }
// }
function log(msg, error) {
    var out = msg;
    if(error) out = '<span style="color: red;">'+msg+'</span>';
    $('#'+active_form+'_err').html(out);
}
function checkInput(e) {
	if(e.key === "Enter") {
		e.preventDefault();
		var fname = e.target.form.name;
		if(fname === 'login_form') Login();
		if(fname === 'reset_form') resetPassword();
        if(fname === 'new_password_form') setPassword();
	}
}
function showResetPage() {
	active_form = 'reset';
	login_form.fadeOut();
	reset_form.fadeIn();
    $('#email').focus();
}
function hideResetPage() {
	active_form = 'login';
	login_form.fadeIn();
	reset_form.fadeOut();
    $('#user').focus();
}
function showNewPasswordPage() {
    active_form = 'new_password';
    login_form.fadeOut();
    new_password_form.fadeIn();
    $('#newPassword').focus();
}
function hideNewPasswordPage() {
    active_form = 'login';
    new_password_form.fadeOut();
    login_form.fadeIn();
    $('#user').focus();
}
function tasker(task, data, callback) {
    $.ajax({
        url: main_tasker,
        data: {
            task: task,
            data: data
        },
        success: function(resp) {
            if(callback)
                callback(resp);
        },
        error: function (error) {
            log('Ошибка: "' + error.statusText + '"', true);
        }
    })
}
function Login() {
    var data = JSON.stringify({
        username: username.val(),
        password: password.val()
    });
    tasker('login', data, function(resp){
        if(resp.status){
            log(resp.tasker.message);
            window.location.href = resp.tasker.url;
        }else{
            log(resp.tasker.message, true)
        }
    });
}
function resetPassword() {
    var data = JSON.stringify({
        email: email.val(),
        clientUrl: document.URL
    });
    tasker('resetPassword', data, function(resp){
        if(resp.status){
            log(resp.tasker.message);
        }else {
            log(resp.tasker.message, true);
        }
    })
}
function setPassword() {
    var data = JSON.stringify({
            new_password: new_password.val(),
            code: QueryString().code
        });
    tasker('setPassword', data, function(resp){
        if(resp.status){
            log(resp.tasker.message);
        }else {
            log(resp.tasker.message, true);
        }
    })
}