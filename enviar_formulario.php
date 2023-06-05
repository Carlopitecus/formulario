<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    $adjunto = $_FILES['adjunto']['tmp_name'];
    $adjunto_nombre = $_FILES['adjunto']['name'];

    $destino = 'carpeta_destino/' . $adjunto_nombre;

    // Mueve el archivo adjunto a la carpeta de destino
    move_uploaded_file($adjunto, $destino);

    // Configuración del correo electrónico
    $to = 'carlosfarias@capellanpascal.cl';
    $subject = 'Formulario de contacto';
    $message = "Nombre: $nombre\n";
    $message .= "Email: $email\n";
    $message .= "Mensaje: $mensaje\n";
    $headers = "From: $email";

    // Adjunta el archivo al correo
    $file_attached = $destino;
    $headers .= "\nMIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"frontier\"\n";

    $message = "\n\n--frontier\n";
    $message .= "Content-Type: application/octet-stream\n";
    $message .= "Content-Transfer-Encoding: base64\n";
    $message .= "Content-Disposition: attachment; filename=\"" . $adjunto_nombre . "\"\n\n";
    $message .= chunk_split(base64_encode(file_get_contents($file_attached)));
    $message .= "--frontier--\n";

    // Envía el correo electrónico
    if (mail($to, $subject, $message, $headers)) {
        echo 'Correo enviado correctamente.';
    } else {
        echo 'Error al enviar el correo.';
    }
}
?>