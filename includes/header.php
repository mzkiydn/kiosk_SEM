<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <?php 
    $userID = $_SESSION['User'];
    $role = $_SESSION['Role'];
  ?>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- User -->
      <?php
      if($role == 2){
        echo <<<HTML
        <a href="cart.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Cart</div>
        </a>
        HTML;
      }
      
      ?>
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar ">
            <img src="../assets/img/avatars/default_user.png" alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-grow-1">
                <?php
                  if($role == 1){
                    $username = getVendorUsername($userID);
                  }else{
                    $username = getUsername($userID);
                  }
                  
                  ?>
                  <span class="fw-semibold d-block">
                    <?php echo "$username"; ?>
                  </span>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="../user_enhance/profile.php">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">My Profile</span>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="../logout.php">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Log Out</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
    
  </div>
</nav>