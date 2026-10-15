<?php include('includes/header.php'); ?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Riphah International University (Peshawar Campus)</span>
    <h1>Student, Parent &amp; Faculty Portal</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Portal Login</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-12">
        <div class="page-content">

          <div class="adm-intro fu">
            <!-- <span class="sec-eyebrow">MEIS</span> -->
            <h2 class="sec-title" style="font-size:1.75rem;">Management &amp; Education Information System</h2>
            <p class="about-lead">
              MEIS is the central platform for students and parents at Riphah International University – Peshawar Campus.
              Use the links below to access your academic records, attendance, results, and fee information.
            </p>
          </div>

          <!-- Portal Cards -->
          <div class="adm-block fu fu-delay-1" id="portals">
            <div class="adm-block-head">
              <h3>Choose Your Portal</h3>
              <p>Select the portal that applies to you to proceed to login.</p>
            </div>

            <div class="portal-grid">

              <div class="portal-card">
                <div class="portal-card-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h4>Student Portal</h4>
                <!-- <p>Access your academic profile, attendance, results, and course information.</p> -->
                <p>Click the below Button to access the Student Portal</p>
                <a href="https://prime.edu.pk/sis/login" target="_blank" rel="noopener" class="btn-pmc btn-pmc-primary">
                  <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
              </div>

              <div class="portal-card">
                <div class="portal-card-icon"><i class="bi bi-people-fill"></i></div>
                <h4>Parent Portal</h4>
                <p>Click the below Button to access the Parent Portal</p>
                <!-- <p>Monitor your ward's attendance, academic performance, and fee status.</p> -->
                <a href="https://prime.edu.pk/sis/login/parent" target="_blank" rel="noopener" class="btn-pmc btn-pmc-primary">
                  <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
              </div>

              <div class="portal-card">
                <div class="portal-card-icon"><i class="bi bi-info-circle-fill"></i></div>
                <h4>Faculty Portal</h4>
                <p>Click the below Button to access the Faculty Portal</p>
                <!-- <p>Learn more about the Management &amp; Education Information System.</p> -->
                <a href="https://www.prime.edu.pk/meis" target="_blank" rel="noopener" class="btn-pmc btn-pmc-outline">
                  <i class="bi bi-arrow-up-right"></i> Login
                </a>
              </div>

            </div>
          </div>

          

        </div>
      </div>

      <!-- Sidebar -->
      <!-- <div class="col-lg-4 fu fu-delay-2">
        

        <div class="sidebar-widget">
          <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
          <div class="sw-body">
            <a class="sw-link" href="https://prime.edu.pk/sis/login" target="_blank" rel="noopener"><i class="bi bi-mortarboard"></i>Student Login</a>
            <a class="sw-link" href="https://prime.edu.pk/sis/login/parent" target="_blank" rel="noopener"><i class="bi bi-people"></i>Parent Login</a>
            <a class="sw-link" href="https://www.prime.edu.pk/meis" target="_blank" rel="noopener"><i class="bi bi-info-circle"></i>Faculty Login</a>
          </div>
        </div>
      </div> -->

    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- Portal card styles -->
<style>
.portal-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}
.portal-card {
  background: #fff;
  border: 1px solid rgba(0,0,0,.06);
  border-radius: 16px;
  padding: 28px 22px;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0,0,0,.04);
  transition: transform .2s ease, box-shadow .2s ease;
}
.portal-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 24px rgba(0,0,0,.08);
}
.portal-card-icon {
  width: 58px;
  height: 58px;
  margin: 0 auto 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--teal, #009688);
  color: #fff;
  font-size: 1.5rem;
}
.portal-card h4 {
  font-size: 1.05rem;
  font-weight: 700;
  margin-bottom: 8px;
}
.portal-card p {
  font-size: .88rem;
  color: var(--gray-mid, #6c757d);
  line-height: 1.6;
  margin-bottom: 18px;
}
.portal-card .btn-pmc {
  width: 100%;
  justify-content: center;
  font-size: .82rem;
}
</style>