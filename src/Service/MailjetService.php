<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;
use Twig\Loader\FilesystemLoader;

class MailjetService
{
    private string $apiKey;
    private string $apiSecret;

    private array $config;
    private Client $client;

    public function __construct()
    {
        $this->apiKey = "e57e90c724c237d6281fef3b45a2099f";
        $this->apiSecret = "30b78c49b95260ef2c7f46b0a6b6b977";
        $this->config = [];
        $this->client = new Client($this->apiKey, $this->apiSecret, true, ['version' => 'v3.1']);
    }
    public function setSender(string $email, string $name): void
    {
        $this->config['Messages'][0]['From']['Email'] = $email;
        $this->config['Messages'][0]['From']['Name'] = $name;
    }

    public function setRecipient(string $email, string $name): void
    {
        $this->config['Messages'][0]['To'][0]['Email'] = $email;
        $this->config['Messages'][0]['To'][0]['Name'] = $name;
    }

    public function setSubject(string $subject): void
    {
        $this->config['Messages'][0]['Subject'] = $subject;
    }

    public function setBody(string $template, array $vars): void
    {
        $loader = new FilesystemLoader('../templates/email');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);
        $template = $twig->load($template);
        $msg = $template->render($vars);
        $this->config['Messages'][0]['HTMLPart'] = $msg;
        $this->config['Messages'][0]['TextPart'] = html_entity_decode(strip_tags($msg));

    }
    public function addAttachment(string $attachment, string $name, string $type): void
    {
        $total = count($this->config['Messages'][0]['Attachments']);
        $file = $attachment;
        $this->config['Messages'][0]['Attachments'][$total]['ContentType'] = $type;
        $this->config['Messages'][0]['Attachments'][$total]['Filename'] = $name;
        $this->config['Messages'][0]['Attachments'][$total]['Base64Content'] = $file;
    }
    public function send(): ?bool
    {
        $response = $this->client->post(Resources::$Email, ['body' => $this->config]);
        return $response->success();
    }
}