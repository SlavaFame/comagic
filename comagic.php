<!-- comagic формы заявок -->
<form id='myform2'>
	<input type="text" name='name' value='' placeholder="Введите имя" /><br>
	<input type="text" name='phone' value='' placeholder="Введите номер телефона" /><br>
	<input type="hidden" name='site_key'/>
	<input type="hidden" name='visitor_id'/>
	<input type="hidden" name='hit_id'/>
	<input type="hidden" name='session_id'/>
	<input type="hidden" name='consultant_server_url'/>
	<input type='button' value='Отправить' onclick="sendAjaxSubmit()"/>
</form> 

<script>
	function sendAjaxSubmit() {
		$.ajax({
			url: '/ajax/rms/comagicSend.php',
			method: 'POST',
			data: $('#myform2').serialize(),
			complete: function(response) {
				if (response.readyState === 4 && response.status === 200) {
					alert(response.responseText);
				}
			},
			beforeSend: function(jqXHR, settings) {
				var credentials = Comagic.getCredentials();
				settings.data += '&' + $.param(credentials);
			}
		})}
	</script>
	<!-- comagic формы заявок конец -->

	<? $success = true; /* Проверка того, что все выполнено корректно. */
	if ($success) {
		$url = 'https://server.comagic.ru/api/add_offline_message/';
		$data = array(
            'site_key' => $_POST['site_key'], //Значение без изменений из служебного поля site_key
            'visitor_id' => $_POST['visitor_id'], //Значение без изменений из служебного поля visitor_id
            'hit_id' => $_POST['hit_id'], //Значение без изменений из служебного поля hit_id
            'session_id' => $_POST['session_id'], //Значение без изменений из служебного поля session_id
            'name' => $_POST['name'], //Имя клиента
            'phone' => $_POST['phone'], //телефон
            'city' => $_POST['city'], //телефон
        );
        /* Если все поля в html-разметке формы называются так же как этого требует comagic, можно написать "$data = $_POST".
        В противном случае потребуются дополнительные преобразования. */
        $options = array(
        	'http' => array(
        		'header' => "Content-type: application/x-www-form-urlencoded; charset=UTF-8",
        		'method' => "POST",
        		'content' => http_build_query($data)
        	)
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultArray = json_decode($result, true);
        if ($result === false or $resultArray['success' === false]) {
        	/* Обработка случая, если отправка заявки завершилась ошибкой. */
        } else {
        	print 'Заявка успешно отправлена.';
        }
    } else {
    	print 'Произошла непредвиденная ошибка';
    }