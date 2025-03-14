<?php
include "../include/connessione.inc";

$email = $_POST['email'];
$password = $_POST['password'];

// Prepara la query per selezionare l'utente con l'email e la password forniti
$stmt = $conn->prepare("SELECT id_user, surname, name, password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
//verifica se l'utente esiste e se le credenziali sono corrette
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // Verifica la password
    if (password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];

        // Passa i dati dell'utente alla pagina index.php
        header("Location: ../index.php");
        exit();
    } else {
        // Password non valida
        echo "Email o password non validi. <a href='../registrazione.php'>Non hai un account? Registrati</a>";
    }
} else {
    // Email non valida
    echo "Email o password non validi. <a href='../registrazione.php'>Non hai un account? Registrati</a>";
}

include "../include/connessione.inc";
