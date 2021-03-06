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

	<h2 class="pageTitle">Success!</h2>
    <div class="pageContent">
      <p> Action successfully completed </p>
<?php
$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
$newIdQuery->bindValue(':seqGenName', 'AUCTION', PDO::PARAM_STR);
$newIdQuery->execute();
$auctionId = $newIdQuery->fetchColumn(0);
$newIdQuery->closeCursor();

$insertAuctionStmt = $database->prepare('
    INSERT INTO AUCTION
	(AUCTION_ID, STATUS, SELLER, CURRENT_BID_ID, STARTING_BID, RESERVE_PRICE, OPEN_TIME, CLOSE_TIME, ITEM_CATEGORY, ITEM_NAME, ITEM_DESCRIPTION, ITEM_CONDITION, ITEM_PHOTO)
        VALUES (:auctionId, :status, :seller, NULL, :startingBid, :reservePrice, CURRENT_TIMESTAMP, :closeTime, :category, :name, :description, :condition, :photo);
    ');

$insertAuctionStmt->bindValue(':auctionId', $auctionId, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':status', 1, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':seller', $loggedInUserInfo['USER_ID'], PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':startingBid', htmlspecialchars($_POST['starting_bid']), PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':reservePrice', htmlspecialchars($_POST['reserve_bid']), PDO::PARAM_STR);
$hour = $_POST['hour'];
if($_POST['am_pm'] == 'pm'){
	$hour += 12;
}
$month = htmlspecialchars($_POST['month']);
$insertAuctionStmt->bindValue(':closeTime', date('Y').'-'.$month.'-'.$_POST['day'].' '.$hour.':'.$_POST['minute'].':00', PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':category', $_POST['category'], PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':condition', $_POST['condition'], PDO::PARAM_INT);
if(isset($_FILES['photo'])){
	$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');
	$insertAuctionStmt->bindValue(':photo', $photoFile, PDO::PARAM_LOB);
} else {
$insertAuctionStmt->bindValue(':photo', NULL, PDO::PARAM_NULL);
}
$insertAuctionStmt->execute();
$insertAuctionStmt->closeCursor();

?>
      <form>
        <button class="submit" formaction="myAccount.php">Accept</button>
      </form>
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
