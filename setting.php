<?php

// メール情報
// メールホスト名・gmailでは smtp.gmail.com
define('MAIL_HOST','送信サーバーのホスト名');

// メールユーザー名・アカウント名・メールアドレスを@込でフル記述
define('MAIL_USERNAME','メールアカウント');

// メールパスワード・上で記述したメールアドレスに即したパスワード
define('MAIL_PASSWORD','パスワード');

// SMTPプロトコル(sslまたはtls)
define('MAIL_ENCRPT','ssl');

// 送信ポート(ssl:465, tls:587)
define('SMTP_PORT', 465);

// メールアドレス・ここではメールユーザー名と同じでOK
define('MAIL_FROM','送信者のメールアドレス');

// 表示名
define('MAIL_FROM_NAME','送信者の名前');

// メールタイトル
define('MAIL_SUBJECT','お問い合わせいただきありがとうございます');

