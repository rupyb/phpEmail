<?php 
 
  $errors = [];
  $missing = [];

  if (isset($_POST['send'])) {
    $expected = ['name', 'email', 'comments'];
    $required = ['name', 'comments'];
    $to = 'Reuven <rupybrisman@yahoo.com>';
    $subject = 'Feedback from form';
    $headers = [];
    $headers[] = 'From: php@me.com';
    $headers[] = 'Cc: anotherphp@me.com';
    $headers[] = 'Content-type: test/plain; charset=utf-8';
    $authorized = null;

    include './includes/process_mail.php';

    if ($mailsent) {
      header('Location: thanks.php');
      exit;
    }
  }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Conditional error messages</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Contact Us</h1>
<?php if ($_POST && ($suspect || isset($errors['mailfail']))):?>
  <p class="warning">Sorry your email couldn't be sent</p>
<?php elseif ($errors || $missing):?>
<p class="warning">Please fix the items(s) indicated</p>
<?php endif; ?>
<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
  <p>
    <label for="name">Name:</label>
    <?php if ($missing && in_array('name', $missing)):?>
    <span class="warning">Please enter your name</span>
  <?php endif;?>
    <input type="text" name="name" id="name"
      <?php 
        if ($errors || $missing) {
          echo 'value=" ' . htmlentities($name) . ' " ';
        }
      ?>
    >
  </p>
  <p>
    <label for="email">Email:</label>
    <?php if ($missing && in_array('email', $missing)):?>
      <span class="warning">Please enter your email</span>
    <?php elseif (isset($errors['email'])):?>
      <span class="warning">Invalid email address</span>
    <?php endif;?>
    <input type="email" name="email" id="email" 
    <?php 
        if ($errors || $missing) {
          echo 'value=" ' . htmlentities($email) . ' " ';
        }
      ?>
      >
  </p>
  <p>
    <label for="comments">Comments:
      <?php if ($missing && in_array('comments', $missing)):?>
    <span class="warning">Please enter a comment</span>
  <?php endif;?>
    </label>
    <textarea name="comments" id="comments"><?php 
        if ($errors || $missing) {
          echo htmlentities($comments);
        }
      ?></textarea>
  </p>
  <p>
    <input type="submit" name="send" id="send" value="Send Comments" >
  </p>
</form>
</body>
</html>