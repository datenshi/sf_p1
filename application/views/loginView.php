<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo form_open('login/validateLogin'); ?>
User name:<br>
<input type="text" name="userName"><br>
Password:<br>
<input type="password" name="password"><br><br>
<input type="submit" value="Submit">
</form>
