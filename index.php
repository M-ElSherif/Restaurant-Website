<!-- Menu is populated with desired menu items here -->
<?php
include('menuItem.php');
$i = 1;
$menuItems = Array();
while ($i <= 4) {
    $itemName = '';
    $description = '';
    $price = 0;
    $stars = '';    // number of '*' to concatenate to menu itemName
    // Another loop to add a number of '*' based on value i
    for ($j = 1; $j <= $i; $j++) {
        $stars = $stars . '*';
    }
    // if counter i is even, add menu item Kebabs. If odd, add menu item Burger
    // add new menuItem object to the array
    if ($i % 2 === 0) {
        $menuItem = new MenuItem('WP Kebabs' . " " . $stars . $i, 'Tender cuts of beef and chicken, served with your choice of side', '$17');
    } else {
        $menuItem = new MenuItem('The WP Burger' . " " . $stars . $i, 'Freshly made all-beef patty served up with homefries', '$14');

    }
    $menuItems[] = $menuItem;
    $i++;    // increment counter by 1
}
?>

<!-- Header Section -->
<?php
include('shared/header.php');
?>

<!-- Body Content implemented using PHP -->
<?php $day = date('l') . '\'s'; ?> <!-- Retreive the day in 7 day format -->
<div id="content" class="clearfix">
	<aside>
		<h2><?php echo $day; ?> Specials</h2>
		<hr>
        <?php
        foreach ($menuItems as $menuItem) {
            $name = $menuItem->get_itemName();
            if (strpos($name, 'Burger') !== false) {
                echo '<img src="images/burger_small.jpg" alt="Burger" title=' . $day . ' Special - Burger">';
            } elseif (strpos($name, 'Kebab') !== false) {
                echo '<img src="images/kebabs.jpg" alt="Kebabs" title="WP Kebabs">';
            }
            echo '<h3>' . $menuItem->get_itemName() . '</h3>';
            echo '<p>' . $menuItem->get_description() . ' - ' . $menuItem->get_price() . '</p>';
            echo '<hr>';
        }
        ?>
	</aside>

	<div class="main">
		<h1>Welcome</h1>
		<img src="images/dining_room.jpg" alt="Dining Room" title="The WP Eatery Dining Room" class="content_pic">
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
			dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
			ex
			ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
			fugiat
			nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
			mollit
			anim id est laborum</p>
		<h2>Book your Christmas Party!</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
			dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
			ex
			ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
			fugiat
			nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
			mollit
			anim id est laborum</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
			dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
			ex
			ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
			fugiat
			nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
			mollit
			anim id est laborum</p>
	</div><!-- End Main -->
</div><!-- End Content -->

<!-- Footer Section -->
<?php include('shared/footer.php'); ?>
