<?php
// Sprawdzamy, czy użytkownik wysłał formularz
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobieramy dane z formularza
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    $consent = $_POST['consent'];

    // Walidujemy dane
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete all fields on the form and ensure that your email address is correct.";
        exit;
    }

    // Adres email, na który wysyłamy wiadomość
    $recipient = "przemek.pawlus@hotmail.com";

    // Temat wiadomości
    $subject = "Nowa wiadomosc z formularza kontaktowego P-SYSTEM od $name";

    // Treść wiadomości
    $email_content = "Imię i nazwisko: $name\n";
    $email_content .= "Adres email: $email\n\n";
    $email_content .= "Wiadomość:\n$message\n";
    $email_content .= "Wyrażam zgodę na przetwarzanie danych osobowych: " . ($consent ? 'Tak' : 'Nie') . "";

    // Nagłówki wiadomości
    $email_headers = "From: $name <$email>";

    // Wysyłamy wiadomość
    if (mail($recipient, $subject, $email_content, $email_headers)) {
    // Wyświetlanie komunikatu jako powiadomienie przeglądarki
    echo "<script>
            window.onload = function() {
              if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Thank you for your message!');
              } else {
                alert('Thank you for your message!');
              }
            }
          </script>";
  } else {
    http_response_code(500);
    echo "Something has gone wrong and the message could not be sent. Please try again later.";
  }
} else {
  http_response_code(403);
  echo "There was an error while processing the form. Please try again later.";
}
