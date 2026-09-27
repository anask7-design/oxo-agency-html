<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
function finish(int $status, string $title, string $message): never {
    http_response_code($status);
    $title = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $title . ' | OXO Agency</title><link rel="stylesheet" href="oxo-site.css"></head><body><main class="wrap section"><h1>' . $title . '</h1><p>' . $message . '</p><a class="btn btn-primary" href="site-contact.html">Retour au contact</a></main></body></html>';
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') finish(405, 'Méthode non autorisée', 'Veuillez utiliser le formulaire de contact.');
if (!empty($_POST['website'])) finish(200, 'Message reçu', 'Merci pour votre message.');
$name = trim((string)($_POST['nom'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$subject = trim((string)($_POST['sujet'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
if ($name === '' || mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 160 || !in_array($subject, ['Question générale','Projet web','SEO','Maintenance','Autre'], true) || mb_strlen($message) < 10 || mb_strlen($message) > 3000) finish(422, 'Informations à vérifier', 'Vérifiez les champs et réessayez.');
foreach ([$name,$email,$subject] as $value) if (strpbrk($value, "\r\n") !== false) finish(422, 'Informations à vérifier', 'Vérifiez les champs et réessayez.');
$body = "Nouveau message de contact OXO\n\nSujet : {$subject}\nNom : {$name}\nEmail : {$email}\n\nMessage :\n{$message}\n";
$headers = ['From' => 'OXO Agency <no-reply@oxo-agency.com>', 'Reply-To' => $email, 'Content-Type' => 'text/plain; charset=UTF-8'];
if (!mail('contact@oxo-agency.com', 'Contact OXO - ' . $subject, $body, $headers)) {
    error_log('OXO contact: envoi mail indisponible');
    finish(503, 'Envoi indisponible', 'Votre message n’a pas été transmis. Écrivez-nous directement à contact@oxo-agency.com.');
}
finish(200, 'Message envoyé', 'Merci ! Nous vous répondrons dès que possible.');
