<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="/pages/form-project.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <input class="form__input <?=isset($errors['name']) ? 'form__input--error' : ''?>" type="text" name="name" id="project_name" value="<?=isset($project['name']) ? $project['name'] : ''?>" maxlength="" placeholder="Введите название проекта" autocomplete="off">
            <?php if (isset($errors['name'])): ?>
                <p class="form__message">
                    <?=$errors['name']?>
                </p>
            <?php endif; ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>