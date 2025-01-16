<?php

function postDataIsCorrect(): bool
{
    $params = ['name', 'email', 'message'];

    //no post request found
    if(!$_SERVER['REQUEST_METHOD'] == 'POST') return false;

    //not the right request
    if(!isset($_POST['cambia_col_nome_del_tasto_submit'])) return false;

    //if post params are not set, return true (params _are_ invalid)
    foreach($params as $param) {
        if(!isset($_POST[$param])) return false;
    }

    //invalid email address
    if(!emailIsValid($_POST['email'])) return false;

    return true;
}

function emailIsValid($email): bool
{
    /**
     * regex checks for:
     * username @ domain . extension
     * username can contain:
     *      letters
     *      numbers
     *      _ - . % +
     *
     * domain can contain:
     *      letters
     *      numbers
     *      . -
     *
     * extension:
     *      letters only
     *      at least length 2
     */
    $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($regex, $email);
}

function clenaupBody($body)
{
    //stripping away and encoding HTML tags to make the body mail-safe
    $body = strip_tags($body);
    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5);

    return body;
}

function buildHeaders($mail)
{
    return "From: $mail\r\n";
}

//i need true if error, function returns false if error, hence I invert function result
$error = !postDataIsCorrect();

$resultMessage = "";
$mailWasSent = false;

if(!$error) {
    //building mail data array
    $mailData = [
        'author' => $_POST['name'],
        'email' => $_POST['email'],
        'body' => cleanupBody($_POST['message'])
    ];

    //building send data for recipient
    $sendData = [
        'email' => 'soaiorizzo@gmail.com',
        'subject' => 'New Contact Form Submission',
        'headers' => buildHeaders($mailData['email'])
    ];

    //mail function returns true if the email is sent correctly, otherwise it returns false

    $mailWasSent = mail(
        $sendData['email'],
        $sendData['subject'],
        $mailData['body'],
        $sendData['headers']
    );

    if($mailWasSent) $resultMessage = "Thank you! Your message has been sent.";
    else $resultMessage = "Woopsie! I lost your message, and therefore, I was not able to send it! Sorryyy :3";
} else {
    $resultMessage = "Invalid request. Either the server is busy, or you did input wrong data";
}

echo $resultMessage
?>