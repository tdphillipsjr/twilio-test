<?php
/**
 * This file just handles responding to incoming texts.
 */
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Message>Thanks for your text. We will respond ASAP!</Message>
</Response>
