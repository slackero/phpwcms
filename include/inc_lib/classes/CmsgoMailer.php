<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use Greew\OAuth2\Client\Provider\Azure;
use League\OAuth2\Client\Provider\Google;

class CmsgoMailer extends PHPMailer
{
    /**
     * SMTP class debug output mode.
     *
     * @var int
     *
     * @see PHPMailer::$SMTPDebug
     */
    public $SMTPDebug = 0;

    /**
     * @var array<string, string> List of supported OAuth providers
     */
    private const OAUTH_PROVIDERS = [
        'google' => 'Google',
        'microsoft' => 'Microsoft',
        'azure' => 'Microsoft Azure',
        //'yahoo' => 'Yahoo'
    ];

    public function __construct($config = []) {
        parent::__construct();

        if (empty($config['SMTP_MAILER'])) {
            $this->isMail();
        } else {
            $config['SMTP_MAILER'] = strtolower($config['SMTP_MAILER']);
            if ($config['SMTP_MAILER'] === 'smtp') {
                $this->isSMTP();
            } elseif ($config['SMTP_MAILER'] === 'sendmail') {
                $this->isSendmail();
            } elseif ($config['SMTP_MAILER'] === 'qmail') {
                $this->isQmail();
            } else {
                $this->isMail();
            }
        }

        $this->Host = $config['SMTP_HOST'];

        $config['SMTP_PORT'] = empty($config['SMTP_PORT']) ? 0 : (int)$config['SMTP_PORT'];
        if ($config['SMTP_PORT']) {
            $this->Port = $config['SMTP_PORT'];
        }
        if (!empty($config['SMTP_AUTH'])) {
            $this->SMTPAuth = true;
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
                throw new RuntimeException(
                    'Invalid SMTP_SECURE value (must be "tls" or "ssl")'
                );
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

        if ($this->AuthType === 'XOAUTH2') {
            if (empty($config['SMTP_XOAUTH_PROVIDER'])) {
                throw new RuntimeException(
                    'SMTP_XOAUTH_PROVIDER is required for OAuth2 authentication'
                );
            }
            $config['SMTP_XOAUTH_PROVIDER'] = strtolower($config['SMTP_XOAUTH_PROVIDER']);
            $allowedProviders = implode(', ', array_keys(self::OAUTH_PROVIDERS));
            if (!isset(self::OAUTH_PROVIDERS[$config['SMTP_XOAUTH_PROVIDER']])) {
                throw new RuntimeException(
                    'Invalid SMTP_XOAUTH_PROVIDER value (must be one of: ' . $allowedProviders . ')'
                );
            }
            if (empty($config['SMTP_CLIENT_ID'])) {
                throw new RuntimeException(
                    'SMTP_CLIENT_ID is required for OAuth2 authentication'
                );
            }
            if (empty($config['SMTP_CLIENT_SECRET'])) {
                throw new RuntimeException(
                    'SMTP_CLIENT_SECRET is required for OAuth2 authentication'
                );
            }
            if (empty($config['SMTP_REFRESH_TOKEN'])) {
                throw new RuntimeException(
                    'SMTP_REFRESH_TOKEN is required for OAuth2 authentication'
                );
            }
            if (empty($config['SMTP_USER'])) {
                throw new RuntimeException(
                    'SMTP_USER is required for OAuth2 authentication'
                );
            }

            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->AuthType = 'XOAUTH2';
            $this->Password = '';

            if ($config['SMTP_XOAUTH_PROVIDER'] === 'google') {

                if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                    $this->Host = 'smtp.gmail.com';
                }
                $this->Port = 465;
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

                $provider = new Google(
                    [
                        'clientId' => $config['SMTP_CLIENT_ID'],
                        'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                    ]
                );

            } elseif (
                $config['SMTP_XOAUTH_PROVIDER'] === 'azure'
                ||
                $config['SMTP_XOAUTH_PROVIDER'] === 'microsoft'
            ) {

                if (empty($config['SMTP_TENANT_ID'])) {
                    throw new RuntimeException(
                        'SMTP_TENANT_ID is required for Microsoft Azure OAuth2 authentication'
                    );
                }

                if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                    $this->Host = 'smtp.office365.com';
                }
                $this->Port = 587;
                $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $provider = new Azure(
                    [
                        'clientId' => $config['SMTP_CLIENT_ID'],
                        'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                        'tenantId' => $config['SMTP_TENANT_ID'],
                    ]
                );
            } else {
                throw new RuntimeException(
                    'Invalid SMTP_XOAUTH_PROVIDER value (must be one of: ' . $allowedProviders . ')'
                );
            }

            $this->setOAuth(
                new OAuth(
                    [
                        'provider' => $provider,
                        'clientId' => $config['SMTP_CLIENT_ID'],
                        'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                        'refreshToken' => $config['SMTP_REFRESH_TOKEN'],
                        'userName' => $config['SMTP_USER'],
                    ]
                )
            );
        }
    }
}
