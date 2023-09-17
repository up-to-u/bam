<?php
function template_header() {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, maximum-scale=1.0" />
        <title>ระบบบริหารความเสี่ยง | บริษัทบริหารสินทรัพย์ กรุงเทพพาณิชย์ จำกัด (มหาชน)</title>
        <link rel="stylesheet" href="inc/theme/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="inc/theme/vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="inc/theme/css/style.css">
        <link rel="shortcut icon" href="inc/theme/images/logo.png" />
        <meta name="keywords" content="">
        <meta name="author" content="BAM">
        <meta name="fragment" content="!">
        <meta property="og:title"              content="ธนาคารพัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย" />
        <meta property="og:description"        content="เป็นสถาบันการเงินหลักของรัฐที่มั่นคงยั่งยืน เพื่อช่วยเหลือและสนับสนุน SMEs ไทย" />
        <meta property="og:image"              content="img/share.jpg" />
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-123748083-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-123748083-1');
		</script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@200;300&display=swap');
    
    body{
      font-family: 'Prompt' !important;
    }
    </style>
</head>
<body>
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>ระบบบริหารความเสี่ยง</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.html">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-info badge-pill">2</div>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="mdi mdi-palette menu-icon"></i>
            <span class="menu-title">UI Elements</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/forms/basic_elements.html">
            <i class="mdi mdi-view-headline menu-icon"></i>
            <span class="menu-title">Form elements</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/charts/chartjs.html">
            <i class="mdi mdi-chart-pie menu-icon"></i>
            <span class="menu-title">Charts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/tables/basic-table.html">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/icons/mdi.html">
            <i class="mdi mdi-emoticon menu-icon"></i>
            <span class="menu-title">Icons</span>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p>Pages</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">User Pages</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="auth">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/samples/login-2.html"> Login 2 </a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/samples/register-2.html"> Register 2 </a></li>
              <li class="nav-item"> <a class="nav-link" href="pages/samples/lock-screen.html"> Lockscreen </a></li>
            </ul>
          </div>
        </li>       
        <li class="nav-item">
          <a class="nav-link" href="index.php?action=logout">
            <button class="btn bg-danger btn-sm menu-title">ออกจากระบบ</button>
          </a>
        </li>
      </ul>
    </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
          <!-- partial:./partials/_navbar.html -->
          <nav class=" col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
              <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
              </button>
              
              <ul class="navbar-nav navbar-nav-right">
               
                
                
              </ul>
              <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
              </button>
            </div>
          </nav>
          <!-- partial -->
          
          <!-- partial -->
   
      <!-- partial:./partials/_navbar.html -->
   
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-12 col-xl-12 grid-margin stretch-card">
              
                  <div class="card">
                    <div class="card-body pb-0">
                    <?}?>
                    <?php
function template_footer() {
?>

                    </div>
                  </div>
               
            </div>
          </div>

          <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
        <footer class="footer">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © BAM 2022</span>
                </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
  <script src="inc/theme/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="inc/theme/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="inc/theme/js/off-canvas.js"></script>
  <script src="inc/theme/js/hoverable-collapse.js"></script>
  <script src="inc/theme/js/template.js"></script>
  <!-- endinject -->
  
  <!-- plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="inc/theme/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

<?}?>