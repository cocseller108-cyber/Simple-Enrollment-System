<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Northbridge Senior High Enrollment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="index.php" aria-label="Northbridge enrollment home">
            <span class="brand-mark">N</span>
            <span>
                <strong>King Senior High School</strong>
                <small>Enrollment Office</small>
            </span>
        </a>

        <nav class="site-nav" aria-label="Main navigation">
            <a href="#programs">Programs</a>
            <a href="#process">Process</a>
            <a href="student_login.php">Student Portal</a>
            <a href="admin_login.php">Admin</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-copy">
                <p class="eyebrow">Academic Year 2026-2027</p>
                <h1>Senior High enrollment made clear, fast, and trackable.</h1>
                <p class="lead">
                    Submit your application, verify your contact number, and monitor your enrollment status through one professional school portal.
                </p>

                <div class="hero-actions">
                    <a class="button primary" href="enrollment.php">Start Enrollment</a>
                    <a class="button secondary" href="student_login.php">Check Status</a>
                </div>
            </div>

            <div class="hero-panel" aria-label="Enrollment snapshot">
                <div class="panel-topline">
                    <span>Enrollment Status</span>
                    <strong>Open</strong>
                </div>
                <div class="stat-row">
                    <article>
                        <span>Applications</span>
                        <strong>1</strong>
                    </article>
                    <article>
                        <span>Verified</span>
                        <strong>1</strong>
                    </article>
                </div>
                <div class="timeline">
                    <div>
                        <span></span>
                        <p>Application submitted</p>
                    </div>
                    <div>
                        <span></span>
                        <p>OTP verification</p>
                    </div>
                    <div>
                        <span></span>
                        <p>Registrar review</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="programs">
            <div class="section-heading">
                <p class="eyebrow">Available Tracks</p>
                <h2>Choose the strand that fits your goals.</h2>
            </div>

            <div class="program-grid">
                <article class="program-card">
                    <span>STEM</span>
                    <h3>Science, Technology, Engineering, and Mathematics</h3>
                    <p>For learners preparing for engineering, health sciences, IT, architecture, and research courses.</p>
                </article>
                <article class="program-card">
                    <span>ABM</span>
                    <h3>Accountancy, Business, and Management</h3>
                    <p>For future entrepreneurs, accountants, managers, marketers, and business professionals.</p>
                </article>
                <article class="program-card">
                    <span>HUMSS</span>
                    <h3>Humanities and Social Sciences</h3>
                    <p>For students pursuing education, communication, social work, law, public service, and liberal arts.</p>
                </article>
                <article class="program-card">
                    <span>TVL</span>
                    <h3>Technical-Vocational-Livelihood</h3>
                    <p>For hands-on learners building workplace-ready skills in programming, cookery, and technical fields.</p>
                </article>
            </div>
        </section>

        <section class="section process-section" id="process">
            <div class="section-heading">
                <p class="eyebrow">How It Works</p>
                <h2>A simple admission flow from application to verification.</h2>
            </div>

            <div class="process-grid">
                <article>
                    <span>01</span>
                    <h3>Complete the form</h3>
                    <p>Enter personal, guardian, and academic information using the online enrollment form.</p>
                </article>
                <article>
                    <span>02</span>
                    <h3>Verify with OTP</h3>
                    <p>The system sends an OTP to confirm the registered mobile number before account activation.</p>
                </article>
                <article>
                    <span>03</span>
                    <h3>Track status</h3>
                    <p>Students can sign in using their phone number to review strand details and enrollment status.</p>
                </article>
            </div>
        </section>
    </main>
</body>
</html>
