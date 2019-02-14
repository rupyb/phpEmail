<?php 
$mailsent = false;
$suspect = false;
$pattern = '/Content-type:|Bcc:|Cc:/i';

function isSuspect($value, $pattern, &$suspect) {
    if (is_array($value)) {
        foreach ($value as $item) {
            isSuspect($item, $pattern, $suspect);
        }
    } else {
        if (preg_match($pattern, $value)) {
            $suspect = true;
        }
    }
}

isSuspect($_POST, $pattern, $suspect);

if (!$suspect):
    foreach($_POST as $key => $value) {
        echo "$key:: $value<br>";
        $value = is_array($value) ? $value: trim($value);

        if (empty($value) && in_array($key, $required)) {
        $missing[] = $key;
        $$key = '';
        } elseif (in_array($key, $expected)) {
            $$key = $value;
        }
    }

    if(!$missing && !empty($email)) {
        $validemail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($validemail) {
            $headers[] = "Reply-to: $validemail";
        } else {
            $errors['email'] = true;
        }
    }

    if (!$errors && !$missing) {
        $headers = implode("\r\n", $headers);

        $message = '';
        foreach ($expected as $field) {
            if (isset($$field) && !empty($$field)){
                $val = $$field;
            } else {
                $val = 'Not selected';
            }
            
            if (is_array($val)) {
                $val = implode(', ', $val);
            }

            $field = str_replace('_', ' ', $field);

            $message .= ucfirst($field) . ": $val\r\n\r\n";
        }

        $message = wordwrap($message, 70);
        $mailsent = mail($to, $subject, $message, $headers, $authorized);
    }

endif;
?>