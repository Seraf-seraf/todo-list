# Описание проекта

Это мой первый проект на PHP, который представляет собой веб-приложение для управления списком проектов и связанных с ними задач. Основной функционал включает возможность добавления проектов и задач, а также отметку выполнения задач.

Проект также удовлетворяет следующим требованиям Технического Задания (ТЗ):

- Регистрация и авторизация пользователей.
- Полнотекстовый поиск по задачам.
- Фильтрация задач по датам и проектам.
- Пагинация для удобного просмотра списка задач.
- Уведомление пользователей по электронной почте о предстоящих задачах в текущий день.

Весь функционал, за исключением отправки писем, реализован без использования сторонних библиотек. Были вспомнены и применены SQL-запросы для работы с базой данных.

Также в ходе разработки проекта я изучил и применил следующие аспекты:

- Загрузка сайта на хостинг и использование планировщика задач cron.
- Установка библиотек на хостинг через SSH.
- Защита от XSS-атак путем правильного экранирования пользовательских данных.
- Использование JavaScript для изменения адресной строки и добавления GET-параметров.

Этот проект стал для меня отличной возможностью применить полученные знания и навыки в разработке на PHP.