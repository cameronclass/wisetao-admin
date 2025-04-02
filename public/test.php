<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received: " . json_encode($_POST);
} else {
    echo '<form method="POST" action="/test.php"><input type="text" name="test" value="123"><button type="submit">Send</button></form>';
}
