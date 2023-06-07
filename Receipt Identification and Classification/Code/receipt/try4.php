    <?php
    function calltrain() {
        $command = escapeshellcmd('python C:/xampp3/htdocs/receipt/recognition/train.py');
        $output = shell_exec($command);
        
        echo "<p> <font color=white>Training Model<br>";
        echo $output;
        echo "</font> </p>";
    } 
    function calltest() {
        $command2 = escapeshellcmd('python C:/xampp3/htdocs/receipt/recognition/test.py');
        $output = shell_exec($command2);
        echo "<p> <font color=white>Testing Model<br>"; 
        echo $output;
        echo "</font> </p>";
    }
    function callextract() {
        $command3 = escapeshellcmd('python C:/xampp3/htdocs/receipt/ExtractReceipt.py');
        $output = shell_exec($command3);
        echo "<p> <font color=white>Extract Data from Receipt<br>"; 
        echo $output;
        echo "</font> </p>";
    }
        if ($_POST) {
            if (isset($_POST['trainmodel'])) {
                calltrain();
            }
            elseif (isset($_POST['testmodel'])) {
                calltest();
            }
            elseif (isset($_POST['extract'])) {
                callextract();
            }
        }
    ?> 