<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дела в порядке</title>
</head>
<body>
    <p>Уважаемый, <?=$username?>. У вас на сегодня(<?=$current_date?>)
        <?php if (count(explode(',', $tasks_in_day)) > 1):?>
            запланированы задачи:</br>
            <ul>
                <?php foreach (explode(',', $tasks_in_day) as $task): ?>
                    <li><?=$task?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            запланирована задача - <?=$tasks_in_day?>
        <?php endif; ?>
    </p>
</body>
</html>