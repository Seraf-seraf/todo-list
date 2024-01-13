<main class="content__main">
	<h2 class="content__main-heading">Вход на сайт</h2>

	<form class="form" action="/pages/form-authorization.php" method="post" autocomplete="off">
		<div class="form__row">
			<label class="form__label" for="email">E-mail <sup>*</sup></label>

			<input class="form__input <?=isset($errors['email']) ? 'form__input--error' : ''?>" type="email" name="email" id="email" value="<?=isset($_POST['email']) ? $_POST['email'] : ''?>" placeholder="Введите e-mail" autocomplete="off">

			<?php if (isset($errors['email'])): ?>
				<p class="form__message"><?=$errors['email']?></p>
			<?php endif; ?>
		</div>

		<div class="form__row">
			<label class="form__label" for="password">Пароль <sup>*</sup></label>

			<input class="form__input <?=isset($errors['password']) ? 'form__input--error' : ''?>" type="password" name="password" id="password" value="" placeholder="Введите пароль" autocomplete="off">
			<?php if (isset($errors['password'])): ?>
				<p class="form__message"><?=$errors['password']?></p>
			<?php endif; ?>
		</div>
		
		<?php if (isset($errors['error'])): ?>
			<p class="form__message"><?=$errors['error']?></p>
		<?php endif; ?>
				
		<div class="form__row form__row--controls">
            <?php if (isset($errors) && count($errors) > 0): ?>
                <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
            <?php endif; ?>

            <input class="button" type="submit" name="" value="Войти">
        </div>
	</form>
</main>