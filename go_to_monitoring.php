<?php

	/**
	 * =========================================================================
	 * Этот файл выполняется на сервере с PHP, выдаёт ответы в JSON для обёрток
	 * =========================================================================
	 * Обязательные параметры для доступа к платформе:
	 * @param main_api_url 	- Ссылка для доступа к API
	 * @param front_end_url 	- Ссылка для самой платформы, которую видит в результате работы скриптов
	 * @param $Session_AuthId 	- Сохраненный в сессии ключ доступа к платформе
	 *
	 * Основные функции скрипта:
	 * - Проверка данных для входа в платформу
	 * - Авторизация
	 * - Восстановление доступа
	 * - Обновлен js для защиты от вытаскивания доступа к API
	 *
	 * Что будет в следующем обновлении:
	 * - Быстрая загрузка без повторной авторизации, при успешной проверке сессии
	 *
	 */
	define("main_api_url",     "http://hosting.glonasssoft.ru/api/");
	define("front_end_url",     "http://hosting.glonasssoft.ru/");

	header('Content-type:application/json;charset=utf-8');

	$output = [
		'status'=>FALSE,
		'Error'=>''		
	];


	// Различные функции для работоспособности таскера
	function _isCurl(){
	    return function_exists('curl_version');
	}
	function json_output($array) {
		return print_r( json_encode($array) );
	}

	// ::Различные функции для работоспособности таскера


	// Скрипт мини-таскера
	function tasker($task) {
		$returnTo = 'client';
		$out = [
			'task'=>$task
		];
		switch ($task) {
			// Every refresh page this task is running
			case 'check':
				if(_isCurl()) $out['status'] = TRUE;
				// Load saved session and check AuthId is active
				// if(isset($_SESSION['auth_id']))
				break;

			case 'login':
				$data = json_decode($_GET['data'], TRUE);
				if(
					!empty($data['username']) && !empty($data['password'])
				) {
					$arr = [
					    'username'=>$data['username'],
						'password'=>base64_encode($data['password'])
					];
					$request = curl_init( main_api_url.'auth/login' );
					curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=UTF-8']);
					curl_setopt($request, CURLOPT_POST, TRUE);
					curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($arr));
					curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
					$response = curl_exec($request);
					$get_Response = json_decode($response, TRUE);
					curl_close($request);
					if(!isset($get_Response['Error'])) {
						// В сессии храним authId
						session_start();
						$_SESSION['auth_id'] = base64_encode($get_Response['AuthId']);
						$out['status'] = TRUE;
						// При успешной авторизации направляем на выбранный узел платформы
						$url = 'http://hosting.glonasssoft.ru/index.html#/login?authId=' . $_SESSION['auth_id'] . '&returnTo=' . $returnTo;
						$out['url'] = $url;
						$out['message'] = '';
					}else{
						$out['message'] = $get_Response['Error'];
					}
				}else{
					$out['message'] = 'Укажите имя пользователя и пароль для входа';
				}
				break;

			case 'resetPassword':
				$data = json_decode($_GET['data'], TRUE);
				if(!empty($data['email']) && !empty($data['clientUrl'])) {
					$arr = [
					    'email'=>$data['email'],
						'clientUrl'=>$data['clientUrl'].'?change_password=true&code={confirm}'
					];
					$request = curl_init( main_api_url.'auth/resetPassword' );
					curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=UTF-8']);
					curl_setopt($request, CURLOPT_POST, TRUE);
					curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($arr));
					curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
					$response = curl_exec($request);
					$get_Response = json_decode($response, TRUE);
					curl_close($request);
					if(!isset($get_Response['error'])) {
						$out['status'] = TRUE;
						// $out['response'] = $get_Response;
						$out['message'] = "На указанную электронную почту выслано письмо с дальнейшими указаниями.";
					}else{
						$out['message'] = $get_Response['error'];
					}
				}else{
					$out['message'] = 'Укажите имя пользователя и пароль для входа';
				}
				break;

			case 'setPassword':
				$data = json_decode($_GET['data'], TRUE);
				if(!empty($data['new_password']) && !empty($data['code'])) {
					$arr = [
						'resetCode'=>$data['code'],
					    'password'=>$data['new_password'],
					];
					// $out['test'] = $arr;
					$request = curl_init( main_api_url.'auth/resetPasswordConfirm' );
					curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=UTF-8']);
					curl_setopt($request, CURLOPT_POST, TRUE);
					curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($arr));
					curl_setopt($request, CURLOPT_RETURNTRANSFER, TRUE);
					$response = curl_exec($request);
					$get_Response = json_decode($response, TRUE);
					curl_close($request);
					if(!isset($get_Response['error'])) {
						$out['status'] = TRUE;
						// $out['response'] = $get_Response;
						$out['message'] = "Пароль был изменен.";
					}else{
						$out['message'] = $get_Response['error'];
					}
				}else{
					$out['message'] = 'Ошибка, вы не указали пароль или ссылка для сброса пароля повреждена.';
				}
				break;
			
			default:
				$out['status'] = FALSE;
				break;
		}
		return $out;
	}

	if(isset($_GET['task'])) {
		// Запускаем мини-таскер
		$tasker = tasker($_GET['task']);
		$output['tasker'] = $tasker;
		if($tasker['status']) $output['status'] = TRUE;
	}else{
		$output['Error'] = 'Не верный запрос';
	}

	// //допустим в сессии храним authId
	// $auth_id = base64_encode($_SESSION['auth_id']);

	// //что будем открывать админку или мониторинг
	// $returnTo = $_GET['returnTo'];


	// Основной вывод всех функций
	json_output( $output );	

?>