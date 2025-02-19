<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address
  function loadEnv($path = '.env') {
      if (!file_exists($path)) return;

      $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($lines as $line) {
          if (strpos(trim($line), '#') === 0) continue; // Skip comments

          list($key, $value) = explode('=', $line, 2);
          $key = trim($key);
          $value = trim($value);

          putenv("$key=$value");
          $_ENV[$key] = $value;
          $_SERVER[$key] = $value;
      }
  }

  // Load environment variables
  loadEnv();

  $receiving_email_address = getenv('SMTP_USER');

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
  $contact->smtp = array(
    'host' => getenv('SMTP_HOST'),
    'username' => getenv('SMTP_USER'),
    'password' => getenv('SMTP_PASS'),
    'port' => getenv('SMTP_PORT')
  );

  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  echo $contact->send();
?>
