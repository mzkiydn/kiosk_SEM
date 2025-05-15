<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <?php
    $role = $_SESSION['Role'];

    if ($role == 1) {
        echo <<<HTML
        <div class="app-brand demo">
        <!--Logo-->
        <a href="../Kiosk/kiosk_dashboard.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="100" src="../assets/img/logo.png">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <!--Sidebar-->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item">
            <a href="../Kiosk/kiosk_dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <!-- Layouts for Vendor -->
        <li class="menu-item">
            <a href="../Kiosk/manage_menu.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-folder"></i>
                <div data-i18n="Layouts">Manage Menu</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../Kiosk/manageOrder.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Account Settings">Manage Collected Order</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../Kiosk/InstoreCart.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store-alt"></i>
                <div data-i18n="Account Settings">Manage In-Store Purchase</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../Kiosk/ViewInvoice.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Account Settings">View In-Store Invoice</div>
            </a>
        </li>
        <!-- Layouts for Vendor -->
    </ul>
    HTML;
    } else if ($role == 2) {
        echo <<<HTML
        <div class="app-brand demo">
        <!--Logo-->
        <a href="../user/displayKiosk.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="100" src="../assets/img/logo.png">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <!--Sidebar-->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item">
            <a href="../user/user_dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <!-- Layouts for Customer -->
        <li class="menu-item">
            <a href="../user/displayKiosk.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-edit"></i>
                <div data-i18n="Layouts">Make Order</div>
            </a>
        </li>
        <!-- Layouts for Customer -->
    </ul>
    HTML;
    } else {
        echo <<<HTML
        <div class="app-brand demo">
        <!--Logo-->
        <a href="../admin/admin_dashboard.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img width="100" src="../assets/img/logo.png">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <!--Sidebar-->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item">
            <a href="../admin/admin_dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../admin/manageUser.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Manage User</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../admin/manageVendor.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Analytics">Manage Vendor</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../admin/manageMenu.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div data-i18n="Analytics">Manage Menu</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="../admin/manageKiosk.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Manage Kiosk</div>
            </a>
        </li>
    </ul>
    HTML;
    }
    ?>
</aside>