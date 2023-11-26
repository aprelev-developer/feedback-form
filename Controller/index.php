<?php

// Подключение класса модели
include '../Model/FeedbackForm.php';

/**
 * Обработка формы обратной связи.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Создание объекта модели
    $model = new FeedbackFormModel($email, $message);

    // Валидация данных
    $validationResult = $model->validate();
    if ($validationResult) {
        echo $validationResult;
    } else {
        // Отправка письма
        $sendResult = $model->sendEmail();
        if ($sendResult) {
            echo "Сообщение успешно отправлено.";
        } else {
            echo "Ошибка при отправке сообщения. Пожалуйста, попробуйте позже.";
        }
    }
}
