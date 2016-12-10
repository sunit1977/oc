<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_customer_info", $Language->MenuPhrase("1", "MenuText"), "customer_infolist.php", -1, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}customer_info'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_subscribed_to", $Language->MenuPhrase("3", "MenuText"), "subscribed_tolist.php", -1, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}subscribed_to'), FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mmci_Reports", $Language->MenuPhrase("11", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_recharge_due", $Language->MenuPhrase("5", "MenuText"), "recharge_duelist.php", 11, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}recharge_due'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mmci_Lookups", $Language->MenuPhrase("10", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_services", $Language->MenuPhrase("2", "MenuText"), "serviceslist.php", 10, "", IsLoggedIn() || AllowListMenu('{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}services'), FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
