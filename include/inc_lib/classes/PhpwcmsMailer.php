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

    /**
     * @var string|null Holds an initialization error message if constructor validation fails
     */
    protected ?string $initError = null;

    public function __construct(array $config = [], ?bool $exceptions = null) {

        if (!empty($config['SMTP_DEBUG'])) {
            $this->SMTPDebug = (int)$config['SMTP_DEBUG'];
        }

        $this->edebug('Init PHPMailer');
        $this->edebug('Debug level set to ' . $this->SMTPDebug);

        $exceptions = $exceptions ?? (isset($config['exceptions']) ? (bool)$config['exceptions'] : null);
        parent::__construct($exceptions);

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

        $this->Host = (string)($config['SMTP_HOST'] ?? 'localhost');
        $this->edebug('Host set to ' . $this->Host);

        $config['SMTP_PORT'] = empty($config['SMTP_PORT']) ? 0 : (int)$config['SMTP_PORT'];
        if ($config['SMTP_PORT']) {
            $this->Port = $config['SMTP_PORT'];
        }
        if (!empty($config['SMTP_AUTH'])) {
            $this->SMTPAuth = true;
            $this->Username = (string)($config['SMTP_USER'] ?? '');
            $this->Password = (string)($config['SMTP_PASS'] ?? '');
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
                $this->initError = $errorMessage;
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
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
            }
            $this->edebug('Set AuthType to ' . $config['SMTP_AUTH_TYPE']);
            $this->AuthType = $config['SMTP_AUTH_TYPE'];
        }

        $charset = (string)($config['charset'] ?? 'utf-8');
        $this->edebug('Set CharSet to ' . $charset);
        $this->CharSet = $charset;

        if (!empty($config['default_lang']) && strtolower($config['default_lang']) !== 'en') {
            $this->edebug('Try to set language to ' . $config['default_lang']);
            $this->setLanguage($config['default_lang']);
        }

        if ($this->AuthType === 'XOAUTH2') {
            $hasOAuthError = false;

            if (empty($config['SMTP_XOAUTH_PROVIDER'])) {
                $errorMessage = 'SMTP_XOAUTH_PROVIDER is required for OAuth2 authentication';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }
            $providerKey = strtolower((string)($config['SMTP_XOAUTH_PROVIDER'] ?? ''));
            $allowedProviders = implode(', ', array_keys(self::OAUTH_PROVIDERS));
            if (!$hasOAuthError && !isset(self::OAUTH_PROVIDERS[$providerKey])) {
                $errorMessage = 'Invalid SMTP_XOAUTH_PROVIDER value (must be one of: ' . $allowedProviders . ')';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }
            if (empty($config['SMTP_CLIENT_ID'])) {
                $errorMessage = 'SMTP_CLIENT_ID is required for OAuth2 authentication';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }
            if (empty($config['SMTP_CLIENT_SECRET'])) {
                $errorMessage = 'SMTP_CLIENT_SECRET is required for OAuth2 authentication';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }
            if (empty($config['SMTP_REFRESH_TOKEN'])) {
                $errorMessage = 'SMTP_REFRESH_TOKEN is required for OAuth2 authentication';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }
            if (empty($config['SMTP_USER'])) {
                $errorMessage = 'SMTP_USER is required for OAuth2 authentication';
                $this->initError = $errorMessage;
                $this->setError($errorMessage);
                $this->edebug($errorMessage);
                if ($this->exceptions) {
                    throw new RuntimeException($errorMessage);
                }
                $hasOAuthError = true;
            }

            $this->isSMTP();
            $this->SMTPAuth = true;
            $this->Password = '';

            $provider = null;

            if (!$hasOAuthError) {
                if ($providerKey === 'google') {
                    $this->edebug('XOAUTH2 provider is Google');
                    if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                        $this->edebug('SMTP_HOST is empty or localhost, set Host to smtp.gmail.com');
                        $this->Host = 'smtp.gmail.com';
                    }
                    if (empty($config['SMTP_PORT'])) {
                        $this->edebug('Default Port to 465');
                        $this->Port = 465;
                    }
                    if (empty($config['SMTP_SECURE'])) {
                        $this->edebug('Default SMTPSecure to ssl');
                        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    }

                    $provider = new Google(
                        [
                            'clientId'     => $config['SMTP_CLIENT_ID'],
                            'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                        ]
                    );
                } elseif ($providerKey === 'azure' || $providerKey === 'microsoft') {
                    $this->edebug('XOAUTH2 provider is ' . self::OAUTH_PROVIDERS[$providerKey]);

                    if (empty($config['SMTP_TENANT_ID'])) {
                        $errorMessage = 'SMTP_TENANT_ID is required for OAuth2 authentication';
                        $this->initError = $errorMessage;
                        $this->setError($errorMessage);
                        $this->edebug($errorMessage);
                        if ($this->exceptions) {
                            throw new RuntimeException($errorMessage);
                        }
                    } else {
                        if (empty($config['SMTP_HOST']) || $config['SMTP_HOST'] === 'localhost') {
                            $this->edebug('SMTP_HOST is empty or localhost, set Host to smtp.office365.com');
                            $this->Host = 'smtp.office365.com';
                        }
                        if (empty($config['SMTP_PORT'])) {
                            $this->edebug('Default Port to 587');
                            $this->Port = 587;
                        }
                        if (empty($config['SMTP_SECURE'])) {
                            $this->edebug('Default SMTPSecure to tls');
                            $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        }

                        $provider = new Azure(
                            [
                                'clientId'     => $config['SMTP_CLIENT_ID'],
                                'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                                'tenantId'     => $config['SMTP_TENANT_ID'],
                            ]
                        );
                    }
                }
            }

            if ($provider !== null) {
                $this->edebug('Set OAuth');

                $this->setOAuth(
                    new OAuth(
                        [
                            'provider'     => $provider,
                            'clientId'     => $config['SMTP_CLIENT_ID'],
                            'clientSecret' => $config['SMTP_CLIENT_SECRET'],
                            'refreshToken' => $config['SMTP_REFRESH_TOKEN'],
                            'userName'     => $config['SMTP_USER'],
                        ]
                    )
                );
            }
        }

        $this->edebug('Init phpwcmsMailer done');
    }

    /**
     * Prepares message for sending.
     * Overridden to prevent sending if constructor validation failed.
     *
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function preSend()
    {
        if ($this->initError !== null) {
            $this->setError($this->initError);
            if ($this->exceptions) {
                throw new \PHPMailer\PHPMailer\Exception($this->initError);
            }
            return false;
        }

        return parent::preSend();
    }
}
