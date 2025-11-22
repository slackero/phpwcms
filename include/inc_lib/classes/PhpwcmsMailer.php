<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use Greew\OAuth2\Client\Provider\Azure;
use League\OAuth2\Client\Provider\Google;

class PhpwcmsMailer extends PHPMailer
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

        if (!empty($config['SMTP_DEBUG'])) {
            $this->SMTPDebug = (int)$config['SMTP_DEBUG'];
        }

        $this->edebug('Init PHPMailer');
        $this->edebug('Debug level set to ' . $this->SMTPDebug);
        parent::__construct();

        $this->edebug('Start to configure phpwcmsMailer');

        if (empty($config['SMTP_MAILER'])) {
            $this->edebug('Set Mailer to mail');
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
        $this->edebug('Mailer set to ' . $this->Mailer);

        $this->Host = $config['SMTP_HOST'];
        $this->edebug('Host set to ' . $config['SMTP_HOST']);

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
                $this->edebug('Set SMTPSecure to tls');
                $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                if (empty($config['SMTP_PORT'])) {
                    $this->edebug('Force Port to 587');
                    $this->Port = 587;
                }
            } elseif ($config['SMTP_SECURE'] === PHPMailer::ENCRYPTION_SMTPS) {
                $this->edebug('Set SMTPSecure to ssl');
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                if (empty($config['SMTP_PORT'])) {
                    $this->edebug('Force Port to 465');
                    $this->Port = 465;
                }
            } else {
                $errorMessage = 'Invalid SMTP_SECURE value (must be "tls" or "ssl")';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
        }

        if (!empty($config['SMTP_AUTH_TYPE'])) {
            $config['SMTP_AUTH_TYPE'] = strtoupper($config['SMTP_AUTH_TYPE']);
            if ($config['SMTP_AUTH_TYPE'] === 'NTLM') {
                $errorMessage = 'NTLM authentication is not supported any longer';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            $this->edebug('Set AuthType to ' . $config['SMTP_AUTH_TYPE']);
            $this->AuthType = $config['SMTP_AUTH_TYPE'];
        }

        $this->edebug('Set CharSet to ' . $config['charset']);
        $this->CharSet = $config['charset'];

        if (!empty($config['default_lang']) && strtolower($config['default_lang']) !== 'en') {
            $this->edebug('Try to set language to ' . $config['default_lang']);
            $this->setLanguage($config['default_lang']);
        }

        if ($this->AuthType === 'XOAUTH2') {
            if (empty($config['SMTP_XOAUTH_PROVIDER'])) {
                $errorMessage = 'SMTP_XOAUTH_PROVIDER is required for OAuth2 authentication';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            $config['SMTP_XOAUTH_PROVIDER'] = strtolower($config['SMTP_XOAUTH_PROVIDER']);
            $allowedProviders = implode(', ', array_keys(self::OAUTH_PROVIDERS));
            if (!isset(self::OAUTH_PROVIDERS[$config['SMTP_XOAUTH_PROVIDER']])) {
                $errorMessage = 'Invalid SMTP_XOAUTH_PROVIDER value (must be one of: ' . $allowedProviders . ')';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            if (empty($config['SMTP_CLIENT_ID'])) {
                $errorMessage = 'SMTP_CLIENT_ID is required for OAuth2 authentication';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            if (empty($config['SMTP_CLIENT_SECRET'])) {
                $errorMessage = 'SMTP_CLIENT_SECRET is required for OAuth2 authentication';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            if (empty($config['SMTP_REFRESH_TOKEN'])) {
                $errorMessage = 'SMTP_REFRESH_TOKEN is required for OAuth2 authentication';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            if (empty($config['SMTP_USER'])) {
                $errorMessage = 'SMTP_USER is required for OAuth2 authentication';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }

            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->Password = '';

            if ($config['SMTP_XOAUTH_PROVIDER'] === 'google') {
                $this->edebug('XOAUTH2 provider is Google');
                if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                    $this->edebug('SMTP_HOST is empty or localhost, set Host to smtp.gmail.com');
                    $this->Host = 'smtp.gmail.com';
                }
                $this->edebug('Force Port to 465');
                $this->Port = 465;
                $this->edebug('Set SMTPSecure to ssl');
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
                $this->edebug('XOAUTH2 provider is ' . self::OAUTH_PROVIDERS[$config['SMTP_XOAUTH_PROVIDER']]);

                if (empty($config['SMTP_TENANT_ID'])) {
                    $errorMessage = 'SMTP_TENANT_ID is required for OAuth2 authentication';
                    $this->setError($errorMessage);
                    $this->edebug($errorMessage);
                    if ($this->exceptions) {
                        throw new RuntimeException($errorMessage);
                    }
                }

                if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                    $this->edebug('SMTP_HOST is empty or localhost, set Host to smtp.office365.com');
                    $this->Host = 'smtp.office365.com';
                }
                $this->edebug('Force Port to 587');
                $this->Port = 587;
                $this->edebug('Set SMTPSecure to tls');
                $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $provider = new Azure(
                    [
                        'clientId' => $config['SMTP_CLIENT_ID'],
                        'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                        'tenantId' => $config['SMTP_TENANT_ID'],
                    ]
                );
            } else {
                $provider = null;
                $errorMessage = 'Invalid SMTP_XOAUTH_PROVIDER value (must be one of: ' . $allowedProviders . ')';
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }

            if ($provider !== null) {
                $this->edebug('Set OAuth');

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

        $this->edebug('Init phpwcmsMailer done');
    }
}
