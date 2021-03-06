**Текстовое описание classmap (черновик**).

Cхема classmap из https://app.diagrams.net:
https://drive.google.com/file/d/133fhiKxh281fv0gw37lzsgma5Vrgb3of/view?usp=sharing


Страницы:

###### лендинг:
- сформирование списка последних 4х заданий

###### регистрация пользователя
- валидация формы регистрации (yii?)

###### Вход на сайт пользователя:
- валидация формы авторизации (yii?)
- проверка на авторизованность пользователя

###### список заданий:
- вывод списка заданий в статусе "новое" и из города текущего пользователя
- обработка времени к формату «минут/часов/дней назад» с округлением в меньшую сторону
- пагинация при >5 заданий
- чекбоксы "категории" - вывод определенных новостей из категории
- 2 доп. условия к фильтру заданий: "удаленка" (без гео-привязки) + "без откликов" (с пустой историей откликов)

###### Добавление задания:
- проверка статуса юзера, страница видна только роли "заказчик"
- валидация формы:
  - «Мне нужно» - обязательное поле, мин. 10 непробельных символов (yii?)
  - «Подробности задания» - обязательное поле, мин. 30 непробельных символов (yii?)
  - «Категория» - обязательное поле, категория должна быть в БД (yii?)
  - Файлы, прикрепление файлов и сохранение в БД к заданию
  - Локация - При вводе текста следует показывать список автодополнения из geocoder api (ограничение на город из профиля пользователя). Итог сохраняет как координаты
  - Бюджет, проверка на целое положительное
  - Срок исполнения. В идеале проверка на дату из будущего

###### Просмотр задания:
- Просмотр информации задания
  - вывод информации о расположении, полученной на основе координат через geocoder api
- добавление отклика (исполнитель):
  - добавление комментария и стоимости работ для заказчика
- Управление статусом задания;
  - Кнопки статуса:
    - заказчик:
      - кнопка "Отменить"(при задании в статусе "новое"):
      - кнопка "завершить": обязат. поле «Комментарий», + обяз. выборка "Оценка работы" выбирается как кол-во звездочек от 1 до 5. 
        Страница  перезагружается, сохраняется новый отклик, а задание переходит в статус «Завершено».
      
    - Исполнитель:
      - кнопка «Откликнуться»(при задании в статусе "новое"): 2 необязательных поля, отправить перезагружает страницу, добавляет отклик
      - кнопка «Отказаться»(при условии принятого задания):
   - Выбор исполнителя (заказчик)
      - вывод всех откликов списком.
      - кнопки:
        - "Подтвердить" исполнителя вызывает процесс «Старт задания»
        - "отклонить" исполнителя помечает отклик как отклонённый и больше не показывает кнопки доступных действий для этого отклика		
- Добавление отзыва об исполнителе


###### Профиль пользователя:
- если залогинен заказчиком, то страница показывается. Иначе 404
- вывод информации:
  - "Специализации" ведут на страницу списка заданий с фильтрацией по выбранной категории.
  - Рейтинг пользователя: нужен расчет
  - список отзывов: показывается, если не пустой. Оценка + текст по каждому заказу.
  - Статистика и контакты:
    - Место в рейтинге: нужен расчет
    - статус:
      - свободен (Открыт для новых заказов)
      - занят (уже выполняет заказ)
    - Блок с контактами: проверка в профиле чекбокса "показывать только заказчику":
      - да: скрываем всем остальным
      - нет: показываем всем

###### Настройки аккаунта:
- валидация формы:
  - «Имя» и «Email»: обязательны для заполнения
  -  «День рождения»:  валидная дата из прошлого в формате дд.мм.гггг
  - «Номер телефона»:  строка из чисел длиной в 11 символов
  - «Telegram»: строка до 64 символов
  - выбор специализаций: минимум одна?
- Безопасность:
  - старый пароль: если заполнено, то делаем валидацию дальше. Должно быть равно текущему паролю
  - новый пароль: если валидация, то обязательно к заполнению
  - повтор нового пароля: если валидация, то должно совпадать с "новый пароль"
- успешная валидация: переадресация на страницу профиля юзера


###### Мои задания:
Заказчик:
- Задания:
  - «Новые»: не выбран исполнитель
  - «В процессе»
  - «Закрытые: в статусах «Отменено», «Выполнено», «Провалено»

Исполнитель:
- Задания:
  - «В процессе»
  - «Просрочено»: еще в работе, но срок "протух"
  - «Закрытые»:  в статусах «Выполнено» и «Провалено»
