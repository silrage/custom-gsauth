<?php
	// Первым делом проверяем наличие настроек
	if(file_exists('settings.php')){
		// Загружаем пользовательские настройки
		include 'settings.php';
	}else{
		echo 'Ошибка! Не найден файл настроек.';
		die();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- GS - Edition -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Панель авторизации в платформу для мониторинга</title>
		<meta name="description" content="Fleet Management &amp; Tracking Software.">
		
		<link rel="stylesheet" href="templates/default/base.css" type="text/css">

		<!-- Load custom styles -->
		<link rel="shortcut icon" href="templates/<?=$template['name'];?>/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="templates/<?=$template['name'];?>/styles.css" type="text/css">
		<!-- ::Load custom styles -->

		<meta http-equiv="cache-control" content="no-cache">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	</head>
	<body class="login">

		<noindex>
	      <noscript style="  color: #FA5A5A;  text-shadow: -1px -1px 0 #A11E1E;  font-size: 24px;  font-weight: bold;  text-align: center;  float: left;  position: fixed;  z-index: 9999999;  background-color: rgba(0, 0, 0, 0.7);  width: 100%;  height: 100%;  margin: auto;  top: 0; padding-top: 35px;">
	        Упс! В вашем браузере не включен JavaScript ;-( <br>
	        Включите его <a href="http://enable-javascript.com/ru" target="_blank" style="color: #FDFAFA;">в настройках</a> и обновите страницу
	      </noscript>
	    </noindex>

		<div id="login_body" style="display: block; height: 680px;">
			<div class="login-bg">

				<div class="login-padding">
					<form method="GET" name="login_form" action="/" id="login_form">
						<div class="logo-img" style=""></div>
						<div class="logo-data" id="logo_data">
					
							<table>
								<tbody>
									<tr>
										<th id="user_td">Пользователь:</th>
										<td>
											<input type="text" name="login" id="user" onkeydown="checkInput(event)" autocomplete="on" placeholder="Пользователь">
										</td>
									</tr>
									<tr>
										<th id="password_td">Пароль:</th>
										<td>
											<input type="password" name="password" id="passw" onkeydown="checkInput(event)" autocomplete="on" placeholder="Пароль">
										</td>
									</tr>
								</tbody>
							</table>
							<input type="submit" id="null_form_submit" style="display: none">

							<input type="hidden" name="client_id" value="">

						</div>
<!-- 						<div class="logo-cookie">
							<input type="checkbox" id="store_cookie" onclick="storeClick(event)">&nbsp;<label for="store_cookie" id="remeber_on_this_computer_label">Запомнить</label>
						</div> -->
						<div class="logo-action">
							<div id="anchors_div" style="margin-top: 50px;">
								
								<a class="reset-password-link" id="reset_password_link" href="javascrip:void(0);" onclick="showResetPage(); return false;"><span class="icon" title="Забыли пароль?"></span><span id="reset_password_link_span" class="text">Забыли пароль?</span></a>
							
							</div>
							
							<div id="operate_as_table_div" style="display:none;">
								<table>
									<tr>
										<td style="width:40%;">
											<a href="http://xn--80agatawhcebcsg0a.xn--p1ai/" id="back_link" onclick="hideResetPage(); return false;">Назад</a>
										</td>
										<td style="text-align:right;width:60%;padding:0;">
											<input style="margin:0;padding:0;" type="text" id="operate_as" onkeydown="checkInput(event)">
										</td>
									</tr>
								</table>
							</div>
							
							<div class="logo-copyright">
								<a target="_blank" class="copyright-link" href="http://www.ximagro.ru/">©&nbsp;ХимАгро</a>
							</div>
						</div>

						<div class="logo-err" id="login_err"></div>

						<div class="logo-enter">
							<input type="button" value="Войти" onclick="return Login();">
							<input type="submit" style="display: none;">
						</div>
					</form>
				</div>
			</div>
		</div>

		
		<div id="reset_body" class="login-gradient-bg" style="display: none; height: 680px;">
			<div class="login-bg">
				<div class="login-padding">
					<form method="POST" name="reset_form" action="http://hosting.glonasssoft.ru/#/restore" id="reset_form">
						<div class="logo-img"></div>
						<div class="logo-info">
							Пожалуйста, введите Ваш логин и адрес электронной почты. Вам будет отправлена ссылка на страницу сброса пароля.
						</div>
						<div class="logo-data" id="logo_data_reset">
							<table>
								<tbody>
									<tr>
										<th id="user_td">E-mail:</th>
										<td>
											<input type="text" name="email" id="email" onkeydown="checkInput(event)" autocomplete="on" placeholder="E-mail">
										</td>
									</tr>
								</tbody>
							</table>
							<div class="logo-action">
								<div class="logo-copyright">
									<a target="_blank" class="copyright-link" href="http://www.ximagro.ru/">©&nbsp;ХимАгро</a>
								</div>
							</div>
						</div>
						<div class="logo-err" id="reset_err"></div>
						<div class="logo-reset" id="logo_reset">
							<input id="reset_submit" type="submit" onclick="resetPassword();return false;" value="Сбросить пароль">
						</div>
						<div class="logo-action-reset">
							<a href="#" id="reset_back_link" onclick="hideResetPage(); return false;">Вернуться на страницу входа</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div id="newPassword_body" class="login-gradient-bg" style="display: none; height: 680px;">
			<div class="login-bg">
				<div class="login-padding">
					<form method="POST" name="new_password_form" action="/" id="new_password_form">
						<div class="logo-img"></div>
						<div class="logo-info">
							Пожалуйста, введите новый пароль.
						</div>
						<div class="logo-data" id="logo_data_reset">
							<table>
								<tbody>
									<tr>
										<th id="user_td">Пароль:</th>
										<td>
											<input type="password" name="new_password" id="newPassword" onkeydown="checkInput(event)" autocomplete="on" placeholder="Новый пароль">
										</td>
									</tr>
								</tbody>
							</table>
							<div class="logo-action">
								<div class="logo-copyright">
									<a target="_blank" class="copyright-link" href="http://www.ximagro.ru/">©&nbsp;ХимАгро</a>
								</div>
							</div>
						</div>
						<div class="logo-err" id="new_password_err"></div>
						<div class="logo-reset" id="logo_reset">
							<input id="reset_submit" type="submit" onclick="setPassword();return false;" value="Сохранить">
						</div>
						<div class="logo-action-reset">
							<a href="#" id="reset_back_link" onclick="hideNewPasswordPage(); return false;">Вернуться на страницу входа</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="jquery.min.js"></script>
		<!--[if lte IE 9]>
		<script type="text/javascript">var old_browser=true</script>
		<script type='text/javascript' src='jquery.xdomainrequest.min.js'></script>
		<![endif]-->
		<script type="text/javascript" src="scripts.js"></script>

	</body>
</html>
