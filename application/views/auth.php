<html>
<head>
<title>My Form</title>
</head>
<body>

<?php

echo validation_errors();

echo form_open('?c=user&m=register');

?>
<label for="email">El. paštas:</label>
<input type="email" name="email" value="">

<label for="password">Slaptažodis:</label>
<input type="password" name="password" value="">

<label for="password">Slaptažodis (dar kartą):</label>
<input type="password" name="password_repeat" value="">

<input type="submit" value="Submit" />
<?php

echo form_close();
?>

<?php
echo validation_errors();

echo form_open('?c=user&m=login');

?>
<label for="email">El. paštas:</label>
<input type="email" name="email" value="">

<label for="password">Slaptažodis:</label>
<input type="password" name="password" value="">

<input type="submit" value="Submit" />
<?php

echo form_close();
?>

</body>
</html>