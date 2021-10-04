<div class="fair-page">
    <div class="nav-fair">
      <ul class"links"="">
        <li class="fair data"><a class="btn">Честная игра</a></li>
      </ul>
    </div>
    <div ng-app="" ng-init="checked = false" class="ng-scope">
		  <div class="fair-details ng-pristine ng-valid fair" action="" method="post" name="form">
          <label for="username">Введите ваш хэш</label>
		  <input class="form-styling" type="text" class="input" id="hash" placeholder="хххххххххххххххх" value="<?php echo e($hash); ?>">
		  <a class="checkHash">ПРОВЕРИТЬ</a>
		  <div class="col" style="display: none;">
          <label for="password">Номер игры</label>
		  <input class="form-styling" type="text" class="input" id="round" value="" disabled>
		  </div>
		  <div class="col" style="display: none;">
			<label for="password">Победное число</label>
			<input class="form-styling"type="text" class="input" id="number" value="" disabled>
			</div>
		</div>
      </div>
  </div><?php /**PATH /var/www/html/resources/views/pages/fair.blade.php ENDPATH**/ ?>