<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param  string $name Путь к файлу шаблона
 * @param  array  $data Ассоциативный массив с
 *                      данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    include $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Показывает страницу с ошибкой
 */
function showError404Page() {
    $page_error = include_template('templates/error404.php');
    print($page_error);
    die();
}

/**
 * Счетчик задач для проекта
 *
 * @param  string $need_type Считает количество проектов у данного проекта
 * @return string Кол-во задач у проекта
 */
function count_category($need_type) {
    global $connect;

    if (!$connect) {
        return mysqli_connect_error();
    }

    $sql = 'SELECT COUNT(project_name) AS count FROM PROJECT JOIN TASK ON TASK.project_id = PROJECT.id WHERE PROJECT.project_name = ?';

    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $need_type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);

    return isset($row['count']) ? $row['count'] : 0;
}


/**
 * Функция для показа, что скоро истекает время на выполнение задачи
 *
 * @param  string $date Принимает дату, которая указывается в форме добавления задачи (/templates/add-task.php  $task['task-deadline'])
 * @return bool True - если осталось меньше одного дня до выполнения задачи
 */
function get_diff_time($date) {
    $deadline = date_create($date);
    $current_date = date_create('now');
    $diff = date_diff($current_date, $deadline);
    
    $remaining_days = date_interval_format($diff, '%d');
    $remaining_hours = date_interval_format($diff, '%h');

    if ($remaining_days <= 1 && $remaining_hours <= 24) {
        return true;
    } else {
        return false;
    }
}


/**
 * Функция для валидации текстового поля
 *
 * @param  string $value Значение для проверки
 * @param  string $min   Минимальное
 *                       значение
 *                       символов
 * @param  string $max   Максимальное
 *                       значение
 *                       символов
 * @param  bool   $bool  Использование
 *                       фильтра
 *                       спец.
 *                       символов.
 * @return string Возвращает сообщение об ошибке, если $value не удовлетворяет условиям
 */
function validate_value_string($value, $min, $max, $bool = null) {
    $filter = ['<', '>', '&', '"', "'", ';', '?', '[', ']', '{', '}', '(', ')', '|', '^', '~', '#', '@', '=', '*', '+', '-', '/', '\\', '%', '_', '!', '`', '$'];

    if ($value) {
        if ($bool) {
            foreach ($filter as $char) {
                if (strpos($value, $char) !== false) {
                    return 'Не используйте спецсимволы!';
                }
            }
        }

        $len = mb_strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно иметь длину от $min до $max символов";
        }
    }
}

/**
 * Проверка существования проекта на сервере
 *
 * @param  string $value    Значение
 *                          для
 *                          проверки
 * @param  array  $projects Массив со всеми
 *                          проектами
 *                          пользователя
 * @return string Возвращает сообщение об ошибке, 
 *                если $value не удовлетворяет условиям
 */
function validate_project($value, $projects) {
    if (!in_array($value, $projects)) {
        return 'Указан несуществующий проект';
    }
}

/**
 * Проверка даты, которая устанавливается как дедлайн к задаче на соответсвие формату 'Y-m-d' и что дата не меньше текущего дня
 *
 * @param  string $date Значение для проверки
 * @return string Возвращает сообщение об ошибке, если $date не удовлетворяет условиям
 */
function validate_date($date) {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    if ($dateTimeObj === false) {
        return 'Неправильный формат даты';
    }

    $date_now = date_create('now');
    $date = date_create($date);

    if ($date->format('d') < $date_now->format('d')) {
        return 'Дата выполнения задания не может быть в прошедшие дни!';
    }
}

/**
 * Проверка почты на валидность
 *
 * @param  string $value Значение для проверки
 * @return string Возвращает сообщение об ошибке, если $value не удовлетворяет условиям
 */
function validate_email($value) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return 'Неправильно написана почта!';
    }
}


/**
 * Создает сообщения ошибок, если неправильно заполнена форма
 *
 * @param  array $form     Форма на
 *                         странице
 * @param  array $required Массив с полями обязательными для заполнения
 * @param  array $rules    Массив с функциями для проверки
 *                         полей на соответсвие требованиям
 * @return array Возвращает ассоциативный массив с сообщениями об ошибках
 */
function get_errors_messages($form, $required, $rules) {
    $errors = [];

    foreach ($form as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        } 
        if (in_array($field, $required) && empty($value)) {
            $errors[$field] = 'Поле нужно заполнить!';
        }
    }
    return $errors;
}

/**
 * Получение данных о пользователе по почте
 *
 * @param  mysqli $connect соединение с бд с помощью mysqli_connect(...)
 * @param  string $email   Почта, по которой
 *                         делается запрос
 * @return array|string Возвращает ассоциативный массив с результатом | В случае ошибки подключения возвращает сообщение об ощибке
 */
function get_user_data_by_email($connect, $email) {
    $sql = 'SELECT id, username, password FROM USER WHERE email = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$connect) {
        return mysqli_connect_error();
    } else {
        return $row;
    }

    return mysqli_error($connect);
}

/**
 * Получение задач пользователя для составления уведомления на почту пользователя о предстоящих задачах
 *
 * @param  mysqli $connect соединение с бд с помощью mysqli_connect(...)
 * @return array|string Возвращает ассоциативный массив с результатом | В случае ошибки подключения возвращает сообщение об ощибке
 */
function get_user_tasks_in_day($connect) {
    $sql = 'SELECT USER.email, USER.username, GROUP_CONCAT(TASK.task_name) as tasks_in_day ' 
        .  'FROM TASK '
        .  'JOIN USER ON USER.id = TASK.user_id '
        .  'WHERE task_completed = 0 AND task_deadline = CURDATE() '
        .  'GROUP BY USER.email';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!$connect) {
        return mysqli_connect_error();
    } else {
        return $row;
    }

    return mysqli_error($connect);
}


/**
 * Создает пользователя в бд
 *
 * @param  mysqli $connect  соединение с бд с
 *                          помощью mysqli_connect(...)
 * @param  string $email    Почта, по которой
 *                          делается запрос
 * @param  string $username Имя пользователя, отображается в шапке страницы, если пользователь зашел на сайт
 * @param  string $password Хэш пароля
 * @return void|string Создает пользователя | В случае ошибки подключения возвращает сообщение об ощибке
 */
function create_user($connect, $email, $username, $password) {
    $sql = 'INSERT INTO USER (email, username, password, date_create_user) VALUES (?, ?, ?, NOW())';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $email, $username, $password);
    mysqli_stmt_execute($stmt);

    if (!$connect) {
        return mysqli_connect_error();
    }    
    return mysqli_error($connect);
}

/**
 * Возвращает id выбранного проекта, используется перед занесением задачи под определенный проект
 *
 * @param  mysqli $connect      соединение с бд с
 *                              помощью mysqli_connect(...)
 * @param  string $project_name Значение из массива $_GET в index.php
 * @param  string $user_id      id пользователя, присваивается в session.php из $_SESSION['user_id']. При авторизации пользователя $_SESSION['user_id']
 *                              присваивается id пользователя из результата функции get_user_data()
 * @return string|string Возвращает id проекта по названию | В случае ошибки подключения возвращает сообщение об ощибке
 */
function get_id_project($connect, $project_name, $user_id) {
    $sql = 'SELECT id AS project_id FROM PROJECT WHERE project_name = ? AND user_id = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $project_name, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$connect) {
        return mysqli_connect_error();
    } else {
        return $row['project_id'] ?? null;
    }
    return mysqli_error($connect);
}


/**
 * Создает задачу
 *
 * @param  mysqli $connect    соединение с бд с
 *                            помощью mysqli_connect(...)
 * @param  string $project_id Результат функции get_id_project(), которая возвращает id проекта
 * @param  string $user_id    id пользователя, присваивается в session.php из $_SESSION['user_id']. При авторизации пользователя $_SESSION['user_id']
 *                            присваивается id пользователя из результата функции get_user_data()
 * @param  array  $task       Ассоциативный массив со значениями
 *                            из формы для создания задачи.
 *                            Страница: pages/form-task.php; Шаблон:
 *                            templates/add-task.php
 * @return void|string Создает задачу | В случае ошибки подключения возвращает сообщение об ощибке
 */
function create_task($connect, $project_id, $user_id, $task) {
    if (!$connect) {
        return mysqli_connect_error();
    }

    $sql = 'INSERT INTO TASK (project_id, user_id, task_name, date_create, file_url, task_completed, task_deadline) VALUES (?, ?, ?, NOW(), ?, 0, ?)';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'iisss', $project_id, $user_id, $task['name'], $task['path'], $task['date']);
    mysqli_stmt_execute($stmt);
}

/**
 * Создает проект. Перед тем как пользователь сможет добавлять задачи, нужно создать проект.
 *
 * @param  mysqli $connect      соединение с бд с
 *                              помощью mysqli_connect(...)
 * @param  string $user_id      id пользователя, присваивается в session.php из $_SESSION['user_id']. При авторизации пользователя $_SESSION['user_id']
 *                              присваивается id пользователя из результата функции get_user_data()
 * @param  string $project_name Название проекта, получаемое из формы. Страница: pages/form-project.php; Шаблон: templates/add-project.php
 * @return void|string Создает проект | В случае ошибки подключения возвращает сообщение об ощибке
 */
function create_project($connect, $user_id, $project_name) {
    if (!$connect) {
        return mysqli_connect_error();
    }

    $sql = 'INSERT INTO PROJECT (user_id, project_name) VALUES (?, ?)';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $user_id, $project_name);
    mysqli_stmt_execute($stmt);
}


/**
 * Проверяет на валидность название проекта.
 *
 * @param  mysqli $connect      соединение с бд с
 *                              помощью mysqli_connect(...)
 * @param  string $user_id      id пользователя, присваивается в session.php из $_SESSION['user_id']. При авторизации пользователя $_SESSION['user_id']
 *                              присваивается id пользователя из результата функции get_user_data()
 * @param  string $project_name Название проекта, получаемое из формы. Страница: pages/form-project.php; Шаблон: templates/add-project.php
 * @return string Возвращает сообщение об ошибке, если не подходит по длине(Параметры $min и $max указаны в функции) или уже есть такой проект. Также не дает создать проект, если превышено максимальное кол-во проектов, параметр задается в функции. Если нет подключения, возращает сообщение об ошибке.
 */
function validate_create_project($connect, $user_id, $project_name) {
    if (!$connect) {
        return mysqli_connect_error();
    }

    $filter = ['<', '>', '&', '"', "'", ';', '?', '[', ']', '{', '}', '(', ')', '|', '^', '~', '#', '@', '=', '*', '+', '-', '/', '\\', '%', '_', '!', '`', '$'];

    foreach ($filter as $char) {
        if (strpos($project_name, $char) !== false) {
            return 'Не используйте спецсимволы!';
        }
    }

    $sql = 'SELECT id FROM PROJECT WHERE project_name = ? AND user_id = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $project_name, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        return 'Этот проект уже существует!';
    }

    $min = 3;
    $max = 64;
    $value = mb_strlen($project_name);
    if ($value < $min or $value > $max) {
        return "Пожалуйста, введите название проекта от $min до $max символов в длину.";
    }

    $sql = 'SELECT project_name FROM PROJECT WHERE user_id = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 10) {
        return 'Можно создать максимум 10 проектов!';
    }
}


/**
 * Получение списка проектов пользователя
 *
 * @param  mysqli $connect соединение с бд с помощью mysqli_connect(...)
 * @param  string $user_id id пользователя, присваивается в session.php из $_SESSION['user_id']. При авторизации пользователя $_SESSION['user_id'] присваивается id пользователя из результата функции get_user_data()
 * @return array|string Возвращает массив с проектами пользователя. Если нет подключения, возращает сообщение об ошибке.
 */
function get_types_tasks($connect, $user_id) {
    if (!$connect) {
        return mysqli_connect_error();
    }

    $sql = 'SELECT project_name FROM PROJECT WHERE user_id = ?';
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $types_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $types_tasks = array_column($types_tasks, 'project_name');

    return $types_tasks;
}

/**
 * Функция для создания ссылок
 *
 * @param  string   $project_name   = $_GET['project_name']
 *                                  имя проекта
 * @param  string   $search         = $_GET['search']
 *                                  поисковый
 *                                  запрос в main.php
 * @param  string   $date_filter    = $_GET['date_filter'] фильтр
 *                                  по дате по всем
 *                                  задачам
 * @param  int|null $show_completed = $_GET['show_completed'] показать выполненные задачи или нет
 * @param  int|null $page           номер
 *                                  страницы
 * @return string возвращает ссылку с параметрами
 */
function create_link($project_name, $search, $show_completed = null, $date_filter = null, $page = null) {
    $params = array();

    if ($page != null) {
        $params['page'] = $page;
    }

    if ($project_name) {
        $params['project_name'] = $project_name;
    }

    if ($search) {
        $params['search'] = $search;
    }

    if ($show_completed) {
        $params['show_completed'] = $show_completed;
    }

    if ($date_filter != null) {
        $params['date_filter'] = $date_filter;
    }

    $link = http_build_query($params);

    return "../index.php?$link";
}


/**
 * Экранирует данные полученные от пользователя
 */
function shielding_data(&$target) {
    if (is_array($target)) {
        foreach ($target as $field => $value) {
            $target[$field] = strip_tags(htmlspecialchars($value));
        }
    } else {
        $target = strip_tags(htmlspecialchars($target));
    }

    return $target;
}
