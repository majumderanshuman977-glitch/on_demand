<?php 
    include("includes/connection.php"); // Database Connection
    include("includes/session_check.php"); //Verify if admin is logged in to prevent unathorised access

  // Get file name
  $currentFile = $_SERVER["SCRIPT_NAME"];
  $parts = explode('/', $currentFile);
  $currentFile = $parts[count($parts) - 1];
  
  // Fetch all distinct o_type values from tbl_offers
$querytime = "SELECT DISTINCT o_type FROM tbl_offers";
$resulttime = mysqli_query($mysqli, $querytime);
$o_types = [];
while ($row = mysqli_fetch_assoc($resulttime)) {
    $o_types[] = $row['o_type'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="author" content="">
<meta name="description" content="">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="assets/css/vendor.css">
<link rel="stylesheet" type="text/css" href="assets/css/flat-admin.css">
<link rel="stylesheet" type="text/css" href="assets/css/new.css">

<!-- Theme -->
<link rel="stylesheet" type="text/css" href="assets/css/theme/blue-sky.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/blue.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/red.css">
<link rel="stylesheet" type="text/css" href="assets/css/theme/yellow.css">

<link rel="icon" href="../images/<?php echo APP_LOGO;?>" type="image/x-icon">

<!-- Icon -->
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-straight/css/uicons-regular-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-straight/css/uicons-solid-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


<script src="assets/ckeditor/ckeditor.js"></script>

</head>
<style>

    .sidebar-nav li.active .icon i {
            color: white !important; /* Set the icon color to white */
        }
        
    .notification > li { 
    display: inline-block;
    position: relative; /* For submenu positioning */
    }
    ul#remove {
        
            list-style-type: none;
             padding-top: 1px; /* Adjust the value as needed */
        }
        .sidebar-menu-item {
      position: relative;
    }
    .sidebar-dropdown &gt; a::after {
      content: '\f078';
      font-family: 'FontAwesome';
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
    }
    .sidebar-dropdown.active &gt; a::after {
      content: '\f077';
    }
    .sidebar-submenu {
      display: none;
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar-submenu ul {
      padding-left: 20px; /* Remove default left padding */
      list-style-type: none; /* Remove the bullet points */
    }

    .sidebar-submenu ul li {
        
    }
    .sidebar-submenu ul li a {
      
    }
    .sidebar-submenu ul li a .menu-icon {
    }
    .sidebar-submenu ul li a:hover {
     
    }
    .sidebar-dropdown.active .sidebar-submenu {
      display: block;
    }
    .title {
      color: white;
    }
   .navbar-search {
    position: relative;
  }
  .navbar-search input {
  border-radius: 4px 4px 4px 4px; /* Sets rounded corners for all sides */
  padding: 5px;
  border: 1px solid #ccc;
}
  .navbar-search button {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    border: none;
    background: transparent;
    cursor: pointer;
    padding: 0 10px;
  }
  .navbar-search .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    border: 1px solid #ccc;
    background: #fff;
    z-index: 1000;
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 300px;
    overflow-y: auto;
  }
  .navbar-search .dropdown-menu.show {
    display: block;
  }
  .navbar-search .dropdown-menu li {
    padding: 10px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
  }
  .navbar-search .dropdown-menu li:last-child {
    border-bottom: none;
  }
  .navbar-search .dropdown-menu li a {
    color: #333;
    text-decoration: none;
    flex-grow: 1;
  }
  .navbar-search .dropdown-menu li i {
    margin-right: 10px;
  }
  .navbar-search .dropdown-menu li .arrow {
    margin-left: auto;
  }

 /* CSS */
.menu-item {
    /* padding: 10px; */
    position: relative;
    cursor: pointer;
    width: 100%;
    background-color:#033d53;
}

.menu-item a {
    text-decoration: none;
    color: #fff;
    display: block;
    height: 41px;
    width: 100%;
    transition: background-color 0.3s;
    padding: 10px;
}


.arrow {
  position: absolute;
    right: 13px;
    top: 13px;
    color: white;
    font-size: 10px;
}
.submenu ul li .icon, .icon_div {
    margin: 10px;
    transition: transform 0.3s ease-in-out;
}
.submenu {
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.3s ease-out;
}

.submenu ul {
    padding: 0;
    list-style: none;
}

.submenu ul li {
    display: flex;
    align-items: center;
    width: 100%;
    /* padding: 5px 10px; */
}

.submenu ul li .icon {
    margin: 10px;
}

.submenu ul li .title {
    flex-grow: 1;
    font-size: 14px;
}

.submenu a {
    text-decoration: none;
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 0.9em;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.submenu a:hover {
    background-color: #faf6f62c;
}

.active > a {
    background-color: #faf6f62c;
}
.dropdown_menu{
  display: flex;
    flex-direction: row;
    align-items: center;
    padding-left: 8px;
    color:white;
    background-color:#12606d;
}
.dropdown_menu:hover{
  background-color:#faf6f62c;
}
.icon_div{
  margin-left:10px;
}
.dropdown_menu:hover .icon_div,
.submenu ul li a:hover .icon {
    transform: scale(1.5); /* Enlarge the icon slightly */
}
.submenu ul li.active .icon {
    transform: scale(1.5); /* Enlarge the icon for active submenu items */
}


  
</style>
<!-- color->#004f6d -->
<body>
  <nav class="notification-tab">
    <ul id= "remove" class="notification">
        <center><li><div class="icon"> <h5><i class="fi fi-ss-badge-check"></i>&nbsp;&nbsp;<?php echo APP_NAME;?> has been updated with <strong>new features</strong> on August 15, 2024. <a href="update.html" target="_blank">(Know More)</a></h5> </div></li></center>
    </ul>
</nav>

<div class="app app-default">
  <aside class="app-sidebar" id="sidebar">
    <div class="sidebar-header"> <a class="sidebar-brand" href="home.php"><img src="../images/<?php echo APP_LOGO;?>" alt="app logo" /></a>
      <button type="button" class="sidebar-toggle"> <i class="fa fa-times"></i> </button>
    </div>
    <div class="sidebar-menu">
      <ul class="sidebar-nav">
        <li <?php if($currentFile=="home.php"){?>class="active"<?php }?>> <a href="home.php">
          <div class="icon"> <i class="fa fa-home" aria-hidden="true"></i> </div>
          <div class="title">Dashboard</div>
          </a> 
        </li>
        
        <!-- --------------------------------------Sub Menu Start------------------------------------ -->
          <div class="menu-item">
              
                  <div class="dropdown_menu">
                    <div class="icon icon_div"><i class="fa fa-user" aria-hidden="true"></i></div>
                    <a href="#">Users</a>
                    <span class="arrow">&#x25BC;</span>
                    </div>
                    <div class="submenu">
                        <ul>
                            <li <?php if($currentFile=="users.php"){?>class="active"<?php }?>> 
                                <a href="users.php">
                                    <div class="icon"> <i class="fa fa-user" aria-hidden="true"></i> </div>
                                    <div class="title">Users</div>
                                </a> 
                            </li>
                            <li <?php if($currentFile=="referrals.php"){?>class="active"<?php }?>> 
                                <a href="referrals.php">
                                    <div class="icon"> <i class="fa fa-users" aria-hidden="true"></i> </div>
                                    <div class="title">Referrals</div>
                                </a> 
                            </li>
                            <li <?php if($currentFile=="kyc.php"){?>class="active"<?php }?>> 
                                <a href="kyc.php">
                                    <div class="icon"> <i class="fa fa-id-card" aria-hidden="true"></i> </div>
                                    <div class="title">KYC Verification</div>
                                </a> 
                            </li>
                        </ul>
                    </div>
                </div>
        
          <!-- ------------------------------------------Sub Menu End----------------------------------- -->
          
        <li <?php if($currentFile=="items.php"){?>class="active"<?php }?>> <a href="items.php">
          <div class="icon"> <i class="fa fa-shopping-basket" aria-hidden="true"></i> </div>
          <div class="title">Items</div>
          </a> 
        </li>
        
        <li <?php if($currentFile=="category.php"){?>class="active"<?php }?>> <a href="category.php">
          <div class="icon"> <i class="fa fa-list-alt" aria-hidden="true"></i> </div>
          <div class="title">Category</div>
          </a> 
        </li>
        
  <!-- --------------------------------------Sub Menu Start------------------------------------ -->
  <div class="menu-item">
      
          <div class="dropdown_menu">
            <div class="icon icon_div"><i class="fa fa-ticket" aria-hidden="true"></i></div>
            <a href="#">Lottery</a>
            <span class="arrow">&#x25BC;</span>
            </div>
            <div class="submenu">
                <ul>
                    <li <?php if($currentFile=="ticket_purchases.php"){?>class="active"<?php }?>> 
                        <a href="ticket_purchases.php">
                            <div class="icon"> <i class="fa fa-history" aria-hidden="true"></i> </div>
                            <div class="title">Ticket Purchases</div>
                        </a> 
                    </li>
                    <li <?php if($currentFile=="lottery.php"){?>class="active"<?php }?>> 
                        <a href="lottery.php">
                            <div class="icon"> <i class="fa fa-ticket" aria-hidden="true"></i> </div>
                            <div class="title">Lottery</div>
                        </a> 
                    </li>
                    <li <?php if($currentFile=="lottery_design.php"){?>class="active"<?php }?>> 
                        <a href="lottery_design.php">
                            <div class="icon"> <i class="fa fa-futbol-o" aria-hidden="true"></i> </div>
                            <div class="title">Ticket Configuration</div>
                        </a> 
                    </li>
                </ul>
            </div>
        </div>

  <!-- ------------------------------------------Sub Menu End----------------------------------- -->
  
        <li <?php if($currentFile=="winners.php"){?>class="active"<?php }?>> <a href="winners.php">
          <div class="icon"> <i class="fa fa-trophy" aria-hidden="true"></i> </div>
          <div class="title">Winners</div>
          </a> 
        </li>
        
        <!-- --------------------------------------Sub Menu Start------------------------------------ -->
          <div class="menu-item">
              
                  <div class="dropdown_menu">
                    <div class="icon icon_div"><i class="fa fa-gear" aria-hidden="true"></i></div>
                    <a href="#">Settings</a>
                    <span class="arrow">&#x25BC;</span>
                    </div>
                    <div class="submenu">
                        <ul>
                            <li <?php if($currentFile=="general_settings.php"){?>class="active"<?php }?>> 
                                <a href="general_settings.php">
                                    <div class="icon"> <i class="fa fa-gears" aria-hidden="true"></i> </div>
                                    <div class="title">General Settings</div>
                                </a> 
                            </li>
                            <li <?php if($currentFile=="payment_settings.php"){?>class="active"<?php }?>> 
                                <a href="payment_settings.php">
                                    <div class="icon"> <i class="fa fa-credit-card" aria-hidden="true"></i> </div>
                                    <div class="title">Payment Methods</div>
                                </a> 
                            </li>
                            <li <?php if($currentFile=="privacy_policy.php"){?>class="active"<?php }?>> 
                                <a href="privacy_policy.php">
                                    <div class="icon"> <i class="fa fa-file-text" aria-hidden="true"></i> </div>
                                    <div class="title">Privacy Policy</div>
                                </a> 
                            </li>
                            <li <?php if($currentFile=="city.php"){?>class="active"<?php }?>> 
                                <a href="city.php">
                                    <div class="icon"> <i class="fa fa-map" aria-hidden="true"></i> </div>
                                    <div class="title">Manage Region</div>
                                </a> 
                            </li>
                        </ul>
                    </div>
                </div>
        
          <!-- ------------------------------------------Sub Menu End----------------------------------- -->
        
        <li <?php if($currentFile=="banners.php"){?>class="active"<?php }?>> <a href="banners.php">
          <div class="icon"> <i class="fa fa-image" aria-hidden="true"></i> </div>
          <div class="title">Banners</div>
          </a> 
        </li>
        
  <!-- --------------------------------------Sub Menu Start------------------------------------ -->
  <div class="menu-item">
      
          <div class="dropdown_menu">
            <div class="icon icon_div"><i class="fa fa-ticket" aria-hidden="true"></i></div>
            <a href="#">Wallet Recharge</a>
            <span class="arrow">&#x25BC;</span>
            </div>
            <div class="submenu">
                <ul>
                    <li <?php if($currentFile=="coin_packages.php"){?>class="active"<?php }?>> 
                        <a href="coin_packages.php">
                            <div class="icon"> <i class="fa fa-percent" aria-hidden="true"></i> </div>
                            <div class="title">Recharge Offers</div>
                        </a> 
                    </li>
                    <li <?php if($currentFile=="manual_payment.php"){?>class="active"<?php }?>> 
                        <a href="manual_payment.php">
                            <div class="icon"> <i class="fa fa-check-square" aria-hidden="true"></i> </div>
                            <div class="title">Manual Purchases</div>
                        </a> 
                    </li>
                    <li <?php if($currentFile=="coin_purchases.php"){?>class="active"<?php }?>> 
                        <a href="coin_purchases.php">
                            <div class="icon"> <i class="fa fa-history" aria-hidden="true"></i> </div>
                            <div class="title">Coin Purchases</div>
                        </a> 
                    </li>
                </ul>
            </div>
        </div>

  <!-- ------------------------------------------Sub Menu End----------------------------------- -->
  
        <!-- --------------------------------------Sub Menu Start------------------------------------ -->
        <div class="menu-item">
            
                <div class="dropdown_menu">
                  <div class="icon icon_div"><i class="fi fi-rr-token" aria-hidden="true"></i></div>
                  <a href="#">Earning Options</a>
                  <span class="arrow">&#x25BC;</span>
                  </div>
                  <div class="submenu">
                      <ul>
                          <li <?php if($currentFile=="games.php"){?>class="active"<?php }?>> 
                              <a href="games.php">
                                  <div class="icon"> <i class="fa fa-gamepad" aria-hidden="true"></i> </div>
                                  <div class="title">Play & Earn</div>
                              </a> 
                          </li>
                          <li <?php if($currentFile=="scratchcard.php"){?>class="active"<?php }?>> 
                              <a href="scratchcard.php">
                                  <div class="icon"> <i class="fi fi-rr-playing-cards" aria-hidden="true"></i> </div>
                                  <div class="title">Scratch & Earn</div>
                              </a> 
                          </li>
                      </ul>
                  </div>
              </div>

        <!-- ------------------------------------------Sub Menu End----------------------------------- -->

        
        <li <?php if($currentFile=="shop.php"){?>class="active"<?php }?>> <a href="shop.php">
          <div class="icon"> <i class="fa fa-shopping-basket" aria-hidden="true"></i> </div>
          <div class="title">Shop</div>
          </a> 
        </li>
        
        <li <?php if($currentFile=="orders.php"){?>class="active"<?php }?>> <a href="orders.php">
          <div class="icon"> <i class="fa fa-shopping-bag" aria-hidden="true"></i> </div>
          <div class="title">Orders</div>
          </a> 
        </li>
        
        
        <!--<li <?php if($currentFile=="send_notification.php"){?>class="active"<?php }?>> <a href="send_notification.php">
          <div class="icon"> <i class="fa fa-bell" aria-hidden="true"></i> </div>
          <div class="title">Send Notification</div>
          </a> 
        </li>-->
        
        
         <li <?php if($currentFile=="vendors.php"){?>class="active"<?php }?>> <a href="vendors.php">
          <div class="icon"> <i class="fi fi-rr-seller" aria-hidden="true"></i> </div>
          <div class="title">Merchant</div>
          </a> 
        </li>
  
      </ul>
    </div>
     
  </aside>   
  <div class="app-container">
      <nav class="navbar navbar-default" id="navbar">
        <div class="container-fluid">
          <div class="navbar-collapse collapse in">
            <ul class="nav navbar-nav navbar-mobile">
              <li>
                <button type="button" class="sidebar-toggle"> <i class="fi fi-rr-apps"></i> </button>
              </li>
              <li class="logo">
                <a class="navbar-brand" href="#"><?php echo APP_NAME;?> Admin Panel</a>
              </li>
              <li>
                <button type="button" class="navbar-toggle">
                  <?php if(PROFILE_IMG) { ?>
                    <img class="profile-img" src="../images/profile.png">
                  <?php } else { ?>
                    <img class="profile-img" src="assets/images/profile.png">
                  <?php } ?>
                </button>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
              <li class="navbar-title">Admin Panel</li>
              <!-- Search bar start -->
              <li class="navbar-search">
                <input type="text" id="searchInput" placeholder="Search..." onfocus="showDropdown()" oninput="filterMenu()">
                <button onclick="searchMenu()"><i class="fi fi-rr-search"></i></button>
                <ul id="dropdownMenu" class="dropdown-menu">
                  <li>
                    <i class="fi fi-rr-house-chimney"></i>
                    <a href="home.php">Dashboard</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-settings"></i>
                    <a href="general_settings.php">General Settings</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fa fa-credit-card"></i>
                    <a href="payment_settings.php">Payment Settings</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fa fa-file-text"></i>
                    <a href="privacy_policy.php">Privacy Policy</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-ticket-alt"></i>
                    <a href="lottery.php">Lottery</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                  <i class="fa fa-ticket"></i>
                  <a href="ticket_purchases.php">Ticket Purchases</a>
                  <span class="arrow">&rarr;</span>
                </li>
                <li>
                  <i class="fa fa-ticket"></i>
                  <a href="lottery_design.php">Ticket Settings</a>
                  <span class="arrow">&rarr;</span>
                </li>
                <li>
                  <i class="fa fa-trophy"></i>
                  <a href="winners.php">Winners</a>
                  <span class="arrow">&rarr;</span>
                </li>
                  <li>
                    <i class="fi fi-rr-box-open"></i>
                    <a href="items.php">Items</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-users-alt"></i>
                    <a href="users.php">Users</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-bell"></i>
                    <a href="send_notification.php">Notifications</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-playing-cards"></i>
                    <a href="scratchcard.php">Scratch Card</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-gamepad"></i>
                    <a href="games.php">Games</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-category"></i>
                    <a href="category.php">Category</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-house-building"></i>
                    <a href="city.php">Regions</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-shop"></i>
                    <a href="coin_packages.php">Wallet Recharge</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-seller"></i>
                    <a href="vendors.php">Merchant (Sellers)</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-id-badge"></i>
                    <a href="kyc.php">KYC Document</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-truck-box"></i>
                    <a href="orders.php">Orders</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-box-open"></i>
                    <a href="shop.php">Ecommerce (Shop)</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-gift"></i>
                    <a href="redeem.php">Withdrawl</a>
                    <span class="arrow">&rarr;</span>
                  </li><li>
                    <i class="fi fi-rr-banner-5"></i>
                    <a href="banners.php">Banners</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                  <li>
                    <i class="fi fi-rr-token"></i>
                    <a href="coin_purchases.php">Coin Purchase</a>
                    <span class="arrow">&rarr;</span>
                  </li><li>
                    <i class="fi fi-rr-refer"></i>
                    <a href="referrals.php">Referrals</a>
                    <span class="arrow">&rarr;</span>
                  </li>
                </ul>
              </li>
              <!-- Search bar end -->
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown profile">
                <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown">
                  <?php if(PROFILE_IMG) { ?>
                    <img class="profile-img" src="../images/profile.png">
                  <?php } else { ?>
                    <img class="profile-img" src="assets/images/profile.png">
                  <?php } ?>
                  <div class="title">Profile</div>
                </a>
                <div class="dropdown-menu">
                  <div class="profile-info">
                    <h4 class="username"><i class="fi fi-rs-admin-alt">&nbsp;</i>Admin</h4>
                  </div>
                  <ul class="action">
                    <li><a href="profile.php"><i class="fi fi-rr-user">&nbsp;</i>Profile</a></li>
                    <li><a href="logout.php"><i class="fi fi-rs-sign-out-alt">&nbsp;</i>Logout</a></li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <script>
        // javascript for submenu logic
        // &#x25BC;down
        // &#x25B2;up

document.addEventListener('DOMContentLoaded', function () {
    var menuItems = document.querySelectorAll('.menu-item');
   

    // Check for stored submenu state and apply it
    var activeMenuClass = localStorage.getItem('activeMenu');
    if (activeMenuClass) {
        var activeMenuItem = document.querySelector('.menu-item.' + activeMenuClass);
        if (activeMenuItem) {
            var submenu = activeMenuItem.querySelector('.submenu');
            submenu.style.maxHeight = submenu.querySelector('ul').scrollHeight + 'px';
            activeMenuItem.querySelector('.arrow').innerHTML = '&#x25B2;'; // thin down arrow
        }
    }

    menuItems.forEach(function (menuItem) {
        menuItem.addEventListener('click', function () {
          

            var submenu = this.querySelector('.submenu');
            var arrow = this.querySelector('.arrow');
            var element=this.querySelector('.active');
            var isVisible = submenu.style.maxHeight !== '0px' && submenu.style.maxHeight !== '';

            // Close all other submenus
            document.querySelectorAll('.submenu').forEach(function (sub) {
                sub.style.maxHeight = null;
            });

            // Reset all arrow icons
            document.querySelectorAll('.arrow').forEach(function (arr) {
                arr.innerHTML = '&#x25BC;'; // reset to thin up arrow
            });

            // Toggle the clicked submenu and arrow icon
            if (!isVisible) {
                submenu.style.maxHeight = submenu.querySelector('ul').scrollHeight + 'px';
                arrow.innerHTML = '&#x25B2;'; // thin down arrow

                // Store active submenu state
                var uniqueClass = this.classList[1];
                if (uniqueClass) {
                    localStorage.setItem('activeMenu', uniqueClass); // Save the unique class/identifier of the menu item
                }
           } 
           else {
                submenu.style.maxHeight = null;
                arrow.innerHTML = '&#x25BC;'; // thin up arrow

                // Remove active submenu state
                localStorage.removeItem('activeMenu');
            }
            
        });
        
    });


    
    // Keep the submenu open if the current URL matches one of the submenu links
    var currentURL = window.location.pathname.split("/").pop(); // Get the current file name
    var submenuLinks = document.querySelectorAll('.submenu a');

    submenuLinks.forEach(function (link) {
        if (link.getAttribute('href') === currentURL) {
            var submenu = link.closest('.submenu');
            if (submenu) {
                submenu.style.maxHeight = submenu.querySelector('ul').scrollHeight + 'px';
                submenu.closest('.menu-item').querySelector('.arrow').innerHTML = '&#x25BC;'; // thin down arrow
            }
        }
    });

   

  
});

        // JavaScript to handle search functionality
        function searchMenu() {
          // Placeholder function for handling search logic
          console.log('Search clicked');
        }

        function showDropdown() {
          document.getElementById('dropdownMenu').classList.add('show');
        }

        function hideDropdown() {
          document.getElementById('dropdownMenu').classList.remove('show');
        }

        function filterMenu() {
          var input, filter, ul, li, a, i, txtValue;
          input = document.getElementById('searchInput');
          filter = input.value.toUpperCase();
          ul = document.getElementById('dropdownMenu');
          li = ul.getElementsByTagName('li');

          for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
            } else {
              li[i].style.display = "none";
            }
          }
        }

        document.addEventListener('click', function(event) {
          var isClickInside = document.querySelector('.navbar-search').contains(event.target);

          if (!isClickInside) {
            hideDropdown();
          }
        });
      </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      var dropdowns = document.querySelectorAll('.sidebar-dropdown > a');

      dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('click', function () {
          var parent = this.parentElement;
          var submenu = parent.querySelector('.sidebar-submenu');

          if (parent.classList.contains('active')) {
            parent.classList.remove('active');
            submenu.style.display = 'none';
          } else {
            // Close all other open dropdowns
            var activeDropdowns = document.querySelectorAll('.sidebar-dropdown.active');
            activeDropdowns.forEach(function (activeParent) {
              activeParent.classList.remove('active');
              activeParent.querySelector('.sidebar-submenu').style.display = 'none';
            });

            parent.classList.add('active');
            submenu.style.display = 'block';
          }
        });
      });

      // Close the dropdown if clicking outside of it
      document.addEventListener('click', function (event) {
        var isClickInside = event.target.closest('.sidebar-menu-item');
        if (!isClickInside) {
          var openDropdowns = document.querySelectorAll('.sidebar-dropdown.active');
          openDropdowns.forEach(function (dropdown) {
            dropdown.classList.remove('active');
            dropdown.querySelector('.sidebar-submenu').style.display = 'none';
          });
        }
      });
    });
  </script>
