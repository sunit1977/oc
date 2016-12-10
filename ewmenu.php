<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_customer_info", $Language->MenuPhrase("1", "MenuText"), "customer_infolist.php", -1, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}customer_info'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_subscribed_to", $Language->MenuPhrase("3", "MenuText"), "subscribed_tolist.php", -1, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}subscribed_to'), FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mci_Reports", $Language->MenuPhrase("11", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mi_recharge_due", $Language->MenuPhrase("5", "MenuText"), "recharge_duelist.php", 11, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}recharge_due'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mci_Lookups", $Language->MenuPhrase("10", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_services", $Language->MenuPhrase("2", "MenuText"), "serviceslist.php", 10, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}services'), FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
