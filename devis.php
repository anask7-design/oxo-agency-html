<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

function finish(int $status, string $title, string $message): never {
    http_response_code($status);
    $title = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo "<!doctype html><html lang=\"fr\"><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width,initial-scale=1\"><title>{$title} | OXO Agency</title><style>body{font:18px/1.6 system-ui;background:#0a0c0b;color:#fff;max-width:680px;margin:12vh auto;padding:24px}h1{color:#c4e92e}a{color:#c4e92e}</style><h1>{$title}</h1><p>{$message}</p><p><a href=\"site-vitrine.html\">Retour au site</a></p></html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    finish(405, 'Méthode non autorisée', 'Veuillez utiliser le formulaire du site.');
}
if (!empty($_POST['website'])) {
    finish(200, 'Demande reçue', 'Merci pour votre message.');
}

$name = trim((string)($_POST['nom'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['telephone'] ?? ''));
$project = trim((string)($_POST['type_projet'] ?? ''));
$estimate = trim((string)($_POST['estimation'] ?? ''));
$detail = trim((string)($_POST['detail'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
if ($name === '' || mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 160 || $phone === '' || mb_strlen($phone) > 40 || !in_array($project, ['Site vitrine', 'Site e-commerce', 'Landing page', 'SEO', 'Maintenance'], true) || mb_strlen($message) > 2000 || mb_strlen($detail) > 4000 || mb_strlen($estimate) > 50) {
    finish(422, 'Informations à vérifier', 'Veuillez vérifier vos coordonnées et réessayer.');
}
foreach ([$name, $email, $phone, $project, $estimate] as $value) {
    if (strpbrk($value, "\r\n") !== false) {
        finish(422, 'Informations à vérifier', 'Veuillez vérifier vos coordonnées et réessayer.');
    }
}

$body = "Nouvelle demande de devis OXO\n\n";
$body .= "Projet : {$project}\nNom : {$name}\nEmail : {$email}\nTéléphone : {$phone}\nEstimation indicative : {$estimate}\n\nOptions :\n{$detail}\n\nMessage :\n{$message}\n";
$subject = 'Nouvelle demande de devis OXO - ' . $project;
$headers = [
    'From' => 'OXO Agency <no-reply@oxo-agency.com>',
    'Reply-To' => $email,
    'Content-Type' => 'text/plain; charset=UTF-8',
];
if (!mail('devis@oxo-agency.com', $subject, $body, $headers)) {
    error_log('OXO devis: envoi mail indisponible');
    finish(503, 'Envoi indisponible', 'Votre demande n’a pas été transmise. Veuillez réessayer plus tard.');
}
finish(200, 'Demande reçue', 'Merci ! OXO Agency reviendra vers vous pour parler de votre projet.');
