<?php

class FeedbackFormModel
{
    private $email;
    private $message;
    private $pdo;

    // Максимальная длина для email и сообщения
    const MAX_EMAIL_LENGTH = 255;
    const MAX_MESSAGE_LENGTH = 1000;

    /**
     * Конструктор класса.
     *
     * @param string $email Адрес электронной почты.
     * @param string $message Сообщение от пользователя.
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;

        // Подключение к базе данных
        $this->pdo = new PDO('mysql:host=localhost;dbname=feedback_db', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Сохранение данных в базе данных.
     *
     * @return bool Результат выполнения операции.
     */
    public function saveToDatabase()
    {
        // Подготовка SQL-запроса
        $sql = "INSERT INTO feedback (email, message) VALUES (:email, :message)";
        $statement = $this->pdo->prepare($sql);

        // Привязка параметров
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':message', $this->message);

        // Выполнение запроса
        return $statement->execute();
    }

    /**
     * Валидация данных формы.
     *
     * @throws Exception Если данные не проходят валидацию.
     */
    public function validate()
    {
        if (empty($this->email) || empty($this->message)) {
            throw new Exception("Заполните все поля формы.");
        }

        // Валидация email с использованием регулярного выражения
        if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $this->email)) {
            throw new Exception("Некорректный формат email.");
        }

        if (strlen($this->email) > self::MAX_EMAIL_LENGTH) {
            throw new Exception("Email не должен превышать " . self::MAX_EMAIL_LENGTH . " символов.");
        }

        if (strlen($this->message) > self::MAX_MESSAGE_LENGTH) {
            throw new Exception("Сообщение не должно превышать " . self::MAX_MESSAGE_LENGTH . " символов.");
        }
    }

    /**
     * Отправка сообщения по электронной почте.
     *
     * @return bool Результат отправки письма.
     */
    public function sendEmail()
    {
        // Настройки для отправки почты
        $to = "andrew.aprelev@yandex.ru";
        $subject = "Новое сообщение с формы обратной связи";
        $headers = "From: $this->email";

        // Отправка письма с использованием mail

        $message = wordwrap($this->message, 70, "\r\n");

        return mail($to, $subject, $message, $headers);
    }
}
