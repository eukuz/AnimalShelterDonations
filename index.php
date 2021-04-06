<?php
    header('Content-Type: text/html; charset=utf-8');
 
    $db_host = 'localhost';
    $db_username = 'mysql';
    $db_password = 'mysql';
    $db_name = 'AnimalShelterDonations';
    $db_charset = 'utf8';

    $dsn = 'mysql:dbname='.$db_name.';host=localhost';
    $dbh = new PDO($dsn, $db_username, $db_password,array(PDO::ATTR_PERSISTENT => true));
 
    $is_connected = @mysql_connect($db_host, $db_username, $db_password);
    $is_db_selected = $is_connected ? @mysql_select_db($db_name) : FALSE; 
 
    $errors = array();
 
    if (!$is_connected) {$errors[] = 'Cannot connect to DB';}
    if (!$is_db_selected) {$errors[] = 'Cannot find DB';}
 
    if (!empty($_POST['f_submit']) AND $is_connected AND $is_db_selected)
    {
        if (empty($_POST['fName_text']) || !trim($_POST['fName_text']) ||
        empty($_POST['lName_text']) || !trim($_POST['lName_text'])||
        empty($_POST['pName_text']) || !trim($_POST['pName_text'])||
         empty($_POST['donate_num']) || !trim($_POST['donate_num']))
        {
            $errors[] = 'Plaese, fill all of the fields!';       
        }
        else
        {
            if (mb_strlen(trim($_POST['fName_text']), 'utf-8')>255 ||
            mb_strlen(trim($_POST['lName_text']), 'utf-8')>255 ||
            mb_strlen(trim($_POST['pName_text']), 'utf-8')>255 
            )
            {
                $errors[] = 'Length of the text in each of the fields should be less than 256 characters!';
            }
            else
            {
                try {
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->beginTransaction();
                $sth = $dbh->exec('INSERT INTO `Patron` SET `fName` = "'.mysql_real_escape_string(trim($_POST['fName_text'])).'", 
                    `lName` ="'.mysql_real_escape_string(trim($_POST['lName_text'])).'";');
                $sth = $dbh->exec('SELECT LAST_INSERT_ID() INTO @uId;');
                $sth = $dbh->exec('INSERT INTO `Pet` SET `Name` = "'.mysql_real_escape_string(trim($_POST['pName_text'])).'";');
                $sth = $dbh->exec('SELECT LAST_INSERT_ID() INTO @pId;');
                $sth = $dbh->exec('INSERT INTO `Donation` SET `date` = NOW(), `idPatron` = @uID, `idPet` =@pId ,
                    `sum` = "'.mysql_real_escape_string(trim($_POST['donate_num'])).'";');
                $dbh->commit();
                 } catch (Exception $e) {
                $dbh->rollBack();
                echo "Error: " . $e->getMessage();
                }
            }
        }
    }
?>
<html>
<head>
  <style type="text/css">
    body {font-size:11px; font-family:Arial;}
    .errors {color:red;}
    div.message {padding-bottom:5px; margin-bottom:5px; border-bottom:1px dotted silver;}
    div.message .date {color:blue;}
    div.message .text {color:green;}
  </style>
</head>
<body>
    <form action="?" method="post">
        First Name<input type="text" name="fName_text" value="" />
        Last Name <input type="text" name="lName_text" value="" />
        donate to a pet Named <input type="text" name="pName_text" value="" />
        the sum of <input type="number" name="donate_num" value="" />$
        <input type="submit" name="f_submit" value="Send"/>
    </form>

<?php    
 
    if (!empty($errors))
    {
        echo '<hr /><ul class="errors">';
        foreach ($errors as $err)
        {
            echo '<li>'.htmlspecialchars($err).'</li>';
        }
        echo '</ul>';
    }
 
    if ($is_connected AND $is_db_selected)
    {
 
        $sql = 'SELECT a.fName "fName", a.lName "lName", c.date "Date", c.sum "Sum", b.Name "pName"
        from Patron a, Pet b, Donation c
        where c.idPatron = a.idPatron and c.idPet = b.idPet ORDER BY `id` DESC';
        $result = mysql_query($sql) 
                  or die('Query error: <code>'.$sql.'</code>');
        if ( is_resource($result) ) 
        {
            echo '<hr />';
            while ( $row = mysql_fetch_assoc($result) )
            {
            ?>
                <div class="message">
                    <span class="text"><?=htmlspecialchars($row['Date'])?> -
                    <span class="text"><?=htmlspecialchars($row['fName'])?></span> 
                    <span class="text"><?=htmlspecialchars($row['lName'])?></span>  
                    <span class="text"> donated </span>
                    <span class="text"><?=htmlspecialchars($row['Sum'])?></span> 
                    <span class="text">$ for</span>
                    <span class="text"><?=htmlspecialchars($row['pName'])?></span> 
                </div>
 
            <?php
            }
        }
    }
 
?>
</body>
</html>