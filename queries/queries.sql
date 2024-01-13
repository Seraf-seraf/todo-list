INSERT INTO USER
	(email,	username, password)
VALUES	
	('serafim123sima@mail.ru', 'Серафим', '123sima09'),
	('test@mail.ru', 'User', 'admin');
	
	
INSERT INTO PROJECT 
	(user_id, project_name)
VALUES 
	(1, 'Учёба'),
	(2, 'Работа'),
	(1, 'Авто'),
	(1, 'Домашние дела'),
	(2, 'Прочее');


INSERT INTO TASK 
	(user_id, project_id, task_name, date_create, file_url, task_completed, task_deadline)
VALUES 
	(1, 1, 'Выучить sql', '2022-01-01 00:00:00', NULL, 0, '2024-01-01 00:00:00'),
	(2, 2, 'Сходить на работу', '2023-12-06 00:00:00', NULL, 0, '2023-12-08 00:00:00'),
	(2, 3, 'Разбить машину', '2022-01-01 00:00:00', NULL, 0, '2024-01-01 00:00:00'),
	(1, 5, 'Погулять', '2022-01-01 00:00:00', NULL, 0, '2024-01-01 00:00:00'),
	(1, 4, 'Помыть посуду', '2023-12-05 00:00:00', NULL, 1, '2023-12-07 00:00:00');


-- SELECT project_name FROM PROJECT WHERE	user_id = 1;

-- SELECT task_name FROM TASK WHERE project_id = 1;

-- UPDATE TASK SET task_complited = 1 WHERE id = 2;

-- UPDATE TASK SET task_name = 'Выучить sql и php' WHERE id = 1;

-- SELECT * FROM TASK JOIN PROJECT ON PROJECT.id = TASK.project_id AND TASK.user_id = 2 WHERE PROJECT.project_name = 'Временное';

SELECT * FROM TASK WHERE user_id = 2;