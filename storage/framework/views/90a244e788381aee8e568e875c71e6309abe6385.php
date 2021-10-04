<div class="popup popup-mute">
	<div class="modal-title flex flex-between flex-align-center">
		<span>Замутить игрока</span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div id="mute_user" class="banner"></div>
			<div class="bx-input">
				<h4>Введите время бана (минуты)</h4>
				<input id="mute_time" type="text" class="input-sum" name="num" min="0"  placeholder="Введите время (минуты)...">
				<input style="display:none;" id="mute_id" value="">
			</div>
			<div class="bx-input">
				<button id="send_mute" type="submit">Отправить</button>
			</div>
	</div>
</div><?php /**PATH /var/www/html/resources/views/modals/mute.blade.php ENDPATH**/ ?>