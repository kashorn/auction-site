<?php
require '/u/ashorn49/openZdatabase.php';

$loggedInUserInfoQuery = $database->prepare('
	SELECT
		USER_ID,
		USERNAME,
		FIRST_NAME,
		LAST_NAME
		FROM PERSON
		WHERE USER_ID = :userId;		
	');
$loggedInUserInfoQuery->bindValue(':userId', 2, PDO::PARAM_INT);
$loggedInUserInfoQuery->execute();
$loggedInUserInfo = $loggedInUserInfoQuery->fetch();
$loggedInUserInfoQuery->closeCursor();

$closeTimeQuery = $database->prepare('
	SELECT
		CLOSE_TIME
		FROM AUCTION
		WHERE AUCTION_ID = :id;
	');
$closeTimeQuery->bindValue(':id', htmlspecialchars($_POST['id']), PDO::PARAM_STR);
$closeTimeQuery->execute();
$closeTime = $closeTimeQuery->fetch();
$closeTimeQuery->closeCursor();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Keith's Auction Site</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=htmlspecialchars($loggedInUserInfo['FIRST_NAME'])?></a>. (<a href="index.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>
    </header>

<?php
if(time() > strtotime(htmlspecialchars($closeTime['CLOSE_TIME']))) {
?>
    <script>
      alert("This auction is already closed.\nYou cannot cancel an auction that has closed.");
      history.back();
    </script>
<?php
} else {
?>
	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p> Action successfully completed </p>
<?php
$updateQuery = $database->prepare('
	UPDATE AUCTION
		SET STATUS = 2
		WHERE AUCTION_ID = :id;
	');
$updateQuery->bindValue(':id', htmlspecialchars($_POST['id']), PDO::PARAM_INT);
$updateQuery->execute();
$updateQuery->closeCursor();

?>
      <form>
        <button class="submit" formaction="myAccount.php">Accept</a>
      </form>
    </div>
<?php
}
?>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
