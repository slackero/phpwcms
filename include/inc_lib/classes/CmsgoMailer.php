<?php

use PHPMailer\PHPMailer\PHPMailer;

class CmsgoMailer extends PHPMailer
{
    public function __construct($config = []) {
        parent::__construct();

        $this->Mailer = $config['SMTP_MAILER'];
        $this->Host = $config['SMTP_HOST'];

        $config['SMTP_PORT'] = empty($config['SMTP_PORT']) ? 0 : (int)$config['SMTP_PORT'];
        if ($config['SMTP_PORT']) {
            $this->Port = $config['SMTP_PORT'];
        }
        if ($config['SMTP_AUTH']) {
            $this->SMTPAuth = 1;
            $this->Username = $config['SMTP_USER'];
            $this->Password = $config['SMTP_PASS'];
        }

        if (!empty($config['SMTP_SECURE'])) {
            $config['SMTP_SECURE'] = strtolower($config['SMTP_SECURE']);
            if ($config['SMTP_SECURE'] === PHPMailer::ENCRYPTION_STARTTLS) {
                $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                if (empty($config['SMTP_PORT'])) {
                    $this->Port = 587;
                }
            } elseif ($config['SMTP_SECURE'] === PHPMailer::ENCRYPTION_SMTPS) {
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                if (empty($config['SMTP_PORT'])) {
                    $this->Port = 465;
                }
            } else {
                throw new RuntimeException('Invalid SMTP_SECURE value');
            }
        }

        if (!empty($config['SMTP_AUTH_TYPE'])) {
            $config['SMTP_AUTH_TYPE'] = strtoupper($config['SMTP_AUTH_TYPE']);
            if ($config['SMTP_AUTH_TYPE'] === 'NTLM') {
                throw new RuntimeException('NTLM authentication is not supported any longer');
            }
            $this->AuthType = $config['SMTP_AUTH_TYPE'];
        }

        $this->CharSet = $config['charset'];

        if (!empty($config['default_lang']) && strtolower($config['default_lang']) !== 'en') {
            $this->setLanguage($config['default_lang']);
        }
    }
}
