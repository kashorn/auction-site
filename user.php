<?php
require 'httpsRedirect.php';
require 'db.php';
session_start();

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
    <title>Keith's Auction Site</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <meta charset="utf-8" />
  </head>

  <body>
    <header>
      <div class="loginInfo">
        You are logged in as <a href="myAccount.php"><?=$_SESSION['first_name']?></a>. (<a href="successLogout.php">Logout</a>)
      </div>

	  <h1 class="title"><a href="index.php">Keith's Auction Site</a></h1>

      <form class="search" action="listings.php" method="get">
        <fieldset class="search">
          <legend>Search</legend>
          <select name="category">
            <option value='0'>All Categories</option>
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
        </fieldset>
      </form>
    </header>

	<h2 class="pageTitle center">&laquoThis user info page is under development&raquo</h2>
	<h2 class="pageTitle">User: [userId]</h2>

    <div class="pageContent">
      <img class="medium" src="notFound.jpg" alt="User Profile Image"/>

      <h3>[City, State]</h3>

      <h4>Current user satisfaction rating is [percentage]%.</h4>
      <h4><a href="review.php">Write a review for this user</a></h4>

      <h3>User's Auctions</h3>
      <table>
        <thead>
          <tr>
            <th>Item ID.</th>
            <th>Date</th>
            <th>Current Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>[<a href="item.php">number</a>]</td>
            <td>[date]</td>
            <td>[current price]</td>
            <td>[Open/Closed]</td>
          </tr>
        </tbody>
      </table>    
    </div>

    <footer>
      <br />
      <h5>Keith Ashorn 2013</h5>
    </footer>
  </body>
</html>
