<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>
    
    <form class="search-form" action="../index.php" method="get" autocomplete="on">
        <input class="search-form__input" type="text" name="search-form" value="<?=isset($search) ? $search : ''?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="<?=$date_filter == 'all' ? create_link($project_name, null, $show_completed, null, 1) : create_link($project_name, null, $show_completed, 'all', 1)?>" class="tasks-switch__item <?=$date_filter == 'all' ? 'tasks-switch__item--active' : ''?>">Все задачи</a>
            <a href="<?=$date_filter == 'today' ? create_link(null, null, $show_completed, null, 1) : create_link(null, null, $show_completed, 'today', 1)?>" class="tasks-switch__item <?=$date_filter == 'today' ? 'tasks-switch__item--active' : ''?>">Повестка дня</a>
            <a href="<?=$date_filter == 'tomorrow' ? create_link(null, null, $show_completed, null, 1) : create_link(null, null, $show_completed, 'tomorrow', 1)?>" class="tasks-switch__item <?=$date_filter == 'tomorrow' ? 'tasks-switch__item--active' : ''?>">Завтра</a>
            <a href="<?=$date_filter == 'passed' ? create_link(null, null, $show_completed, null, 1) : create_link(null, null, $show_completed, 'passed', 1)?>" class="tasks-switch__item <?=$date_filter == 'passed' ? 'tasks-switch__item--active' : ''?>">Просроченные</a>
        </nav>

        <label class="checkbox" for="show_completed">
            <!--добавить сюда атрибут "checked", если переменная $show_completed равна единице-->
            <input class="checkbox__input visually-hidden show_completed" id="show_completed" name="show_completed" type="checkbox" <?=$show_completed ? 'checked' : ''?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>
    <?php if(empty($tasks) && (isset($search) || isset($date_filter))):?>
        <p>Ничего не найдено по вашему запросу</p>
    <?php else: ?>
        <table class="tasks">
        <!--показывать следующий тег <tr/>, если переменная $show_completed равна единице-->
            <?php foreach($tasks as $task):
                if($show_completed == 0 && $task['task_completed']): continue;?>
                <?php else: ?>
                    <tr class="tasks__item task <?=strip_tags(htmlspecialchars($task['task_completed'])) ? 'task--completed' : ''?> <?=get_diff_time($task['task_deadline']) ? 'task--important' : ''?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox" for="<?=$task['id']?>">
                                <input class="checkbox__input visually-hidden" id="<?=$task['id']?>" <?=strip_tags($task['task_completed']) ? 'checked' : ''?> name="<?=$task['task_name']?>" type="checkbox">
                                <span class="checkbox__text"><?=isset($task['task_name']) ? $task['task_name'] : ''?></span>
                            </label>
                        </td>
                        <?php if(isset($task['file_url'])): ?>
                            <td class="task__file">
                                <a class="download-link" href="<?=$task['file_url']?>"><?=$task['id']?></a>
                            </td>
                        <?php else: ?>
                            <td class="task__file">&nbsp;</td> 
                        <?php endif; ?> 
                        <td class="task__date"><?=isset($task['task_deadline']) ? $task['task_deadline'] : ''?></td>
                        <td class="task__controls"></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
	
        <?php if($page_count > 1): ?>
            <ol class="task-pages">
                <?php for($i = 1; $i <= $page_count; $i++): ?>
                    <li class="task-pages__item <?=isset($page) && $page == $i ? 'task-pages__item--active' : '' ?>">
                        <a href="<?=create_link($project_name, $search, $show_completed, $date_filter, $i)?>">
                            <?=$i?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ol>
        <?php endif; ?>
    <?php endif; ?>
</main>
