<?php
require '/u/ashorn49/openZdatabase.php';

$loggedInUserId = 2;

$loggedInUserInfoQuery = $database->prepare('
	SELECT
		USERNAME,
		FIRST_NAME,
		LAST_NAME
		FROM PERSON
		WHERE USER_ID = :userId;		
	');
$loggedInUserInfoQuery->bindValue(':userId', $loggedInUserId, PDO::PARAM_INT);
$loggedInUserInfoQuery->execute();
$loggedInUserInfo = $loggedInUserInfoQuery->fetch();
$loggedInUserInfoQuery->closeCursor();

$categoriesQuery = $database->prepare('
	SELECT
		ITEM_CATEGORY_ID,
		NAME
		FROM ITEM_CATEGORY;
	');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Keith's Auction Site: Listings</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=htmlspecialchars($loggedInUserInfo['FIRST_NAME'])?></a>. (<a href="index.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>

      <form class="search" action="listings.php" method="get">
        <fieldset class="search">
          <legend>Search</legend>
          <select name="category">
            <option value="0">All Categories</option>
<?php
foreach ($categories as $curr):
?>
            <option value="<?=htmlspecialchars($curr['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($curr['NAME'])?></option>
<?php
endforeach;
?>
          </select>
          <input class="search" name="query" type="search" />
          <button class="search" type="submit" formaction="listings.php">Search</button>
<!--          <select>
            <option>Item Name</option>
            <option>Seller Name</option>
          </select>
          <button class="search" type="submit" formaction="listings.php">Search</button>
          <div class="filterOrder">
            <input type="checkbox" />
            <label>Filter by price</label>
            <select>
              <option>Less than</option>
              <option>Greater than</option>
            </select>
            <label>   $</label>
            <input type="number" />
            <br />
            <input type="checkbox" />
            <label>Order By</label>
            <select>
              <option>Relevance</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Closing Soon</option>
            </select>
          </div>-->
        </fieldset>
      </form>
    
    </header>

	<h2 class="pageTitle">Item Listings</h2>
    <br/>

    <div class="pageContent">

<?php
$auctionsQuery = $database->prepare('
    SELECT
		AUCTION_ID,
		SELLER AS SELLER_ID,
        ITEM_NAME,
        ITEM_DESCRIPTION,
		CURRENT_BID_ID,
		RESERVE_PRICE,
        ITEM_PHOTO
        FROM AUCTION
        WHERE STATUS = 1 AND (ITEM_NAME LIKE :q OR ITEM_DESCRIPTION LIKE :q) AND ITEM_CATEGORY LIKE :category
		ORDER BY AUCTION_ID DESC;
    ');
$query = htmlspecialchars($_GET['query']);
$category = htmlspecialchars($_GET['category']);
$auctionsQuery->bindValue(':q', "%$query%", PDO::PARAM_STR);
$auctionsQuery->bindValue(':category', ($category) ?: "%", PDO::PARAM_STR);
$auctionsQuery->execute();
$auctions = $auctionsQuery->fetchAll();
$auctionsQuery->closeCursor();

foreach ($auctions as $auction):
$thisAuctionId = $auction['AUCTION_ID'];
?>
      <div class="listings">
        <a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><img src="itemPhoto.php?id=<?=$thisAuctionId?>" class="floatLeft" alt="Item Image"/></a>
        <h4><a href="item.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>"><?=htmlspecialchars($auction['ITEM_NAME'])?></a> -- 
<?php

if(isset($auction['CURRENT_BID_ID'])){
	$bidQuery = $database->prepare('
		SELECT
			BIDDER,
			AMOUNT
			FROM BID
			WHERE BID_ID = :id;
		');
	$bidQuery->bindValue(':id', $auction['CURRENT_BID_ID'], PDO::PARAM_INT);
	$bidQuery->execute();
	$currentBid = $bidQuery->fetch();
	$bidQuery->closeCursor();
	echo 'Current bid: $'.htmlspecialchars($currentBid['AMOUNT']);
} elseif($loggedInUserId == $auction['SELLER_ID']) {
	echo 'No bids have been placed yet';
} elseif($loggedInUserId != $auction['SELLER_ID']) {
	echo 'Place the first bid!  Minimum: $'.htmlspecialchars($auction['MIN_BID']);
}
?>
        </h4>
<?php 
if($loggedInUserId != $auction['SELLER_ID'] and $auction['RESERVE_PRICE'] != '0.00') {
        echo '<h5>Reserve this item for $'.htmlspecialchars($auction['RESERVE_PRICE']).'</h5>';
}
?>
        <p><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></p>
      </div>

<?php
endforeach;
?>
      <hr />

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
