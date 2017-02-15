var main_api_url = 'http://dev.glonasssoft.ru/',
	front_end_url = 'http://web.glonasssoft.ru/',
	auth_id = null,
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
	username = $('input[name=login]');
	password = $('input[name=password]');
	email = $('input[name=email]');
	loader = $('<div class="loader">');
}
function api(type, api_url, url, data, callback) {
    url = api_url + url;
    // log('Запрос: ' + type + ' ' + url);
    log(loader);
    $.support.cors = true;
    $.ajax({
        type: type,
        url: url,
        data: data,
		contentType: 'application/json',
        crossDomain: true,
        headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
            'X-Auth': auth_id
        },
        success: function (data) {
            // log('OK!');
            if (callback)
                callback(data);
        },
        error: function (error) {
            log('Ошибка: "' + error.statusText + '"', true);
        }
    });
}
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
	}
}
function showResetPage() {
	active_form = 'reset';
	login_form.fadeOut();
	reset_form.fadeIn();
}
function hideResetPage() {
	active_form = 'login';
	login_form.fadeIn();
	reset_form.fadeOut();
}
function get(url, callback) {
    api('GET', main_api_url, url, null, callback);
}
function post(url, data, callback) {
    api('POST', main_api_url, url, JSON.stringify(data), callback);
}
function Login() {
    get('auth/login?username=' + username.val() + '&password=' + password.val(), function (data) {
    	log(data.Error);
        if (data.Error)
            log(data.Error, true);
        else {
        	document.getElementById('login_form').submit();
        }
    });
}
function resetPassword() {
    post('auth/resetPassword', {email: email.val(), clientUrl: document.URL}, function (data) {
    	log(data.error);
        if (data.error)
            log(data.error, true);
        else {
        	log("На указанную электронную почту выслано письмо с дальнейшими указаниями.");
        }
    });
}