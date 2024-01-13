<main class="content__main">
  <?php if ($types_tasks): ?>
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="/pages/form-task.php" method="post" autocomplete="off" enctype="multipart/form-data">
      <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input <?=isset($errors['name']) ? 'form__input--error' : ''?>" type="text" name="name" id="name" value="<?=isset($task['name']) ? $task['name'] : '' ?>" minlength="2" maxlength="64" placeholder="Введите название" autocomplete="off">
        <?php if (isset($errors['name'])): ?>
          <p class="form__message">
            <?=$errors['name']?>
          </p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select class="form__input form__input--select <?=isset($errors['project']) ? 'form__input--error' : ''?>" name="project" id="project">
          <?php 
            $selected = $_POST['project'] ?? '';
            foreach($types_tasks as $type) :?>
              <option value="<?=htmlspecialchars($type)?>" <?=$selected == $type ? 'selected' : ''?>>
              <?=$type?>
            </option>
          <?php endforeach; ?> 
        </select>
        <?php if (isset($errors['project'])): ?>
          <p class="form__message">
            <?=$errors['project']?>
          </p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date <?=isset($errors['date']) ? 'form__input--error' : ''?>" type="text" id="date" name="date" value="<?=isset($task['date']) ? $task['date'] : ''?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД" autocomplete="off">
        <?php if (isset($errors['date'])): ?>
          <p class="form__message">
            <?=$errors['date']?>
          </p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="file" id="file" accept="image/png, image/jpeg" value="<?=isset($task['file']) ? $task['file'] : ''?>" autocomplete="off">
          <label class="button button--transparent" for="file">
            <span>Выберите файл</span>
          </label>
          <?php if (isset($errors['file'])): ?>
            <p class="form__message">
              <?=$errors['file']?>
            </p>
          <?php endif; ?>
        </div>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  <?php else: ?>
    <h2 class="content__main-heading">У вас нет проектов! Сначала добавьте проект!</h2>
  <?php endif; ?>
</main>
