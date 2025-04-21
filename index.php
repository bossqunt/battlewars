<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Battlewarz Authentication</title>
  <!-- Include your CSS and JavaScript files as needed -->
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
</head>

<body>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
          <div class="text-center">
  <img src="images/logo.png" alt="Logo" class="img-fluid mb-4" style="width: 400px;" />
</div>
          <!-- Login Form -->
            <form id="formLogin" class="auth-form" action="/bw2/api/login.php" method="POST">
              <div class="mb-3">
                <label for="loginName" class="form-label">Username</label>
                <input type="text" class="form-control" id="loginName" name="name" placeholder="Enter your username"
                  required>
              </div>
              <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="password"
                  placeholder="Enter your password" required>
              </div>
              <button type="submit" class="btn btn-primary d-block w-100">Sign in</button>
            </form>
            <!-- End Login Form -->

            <!-- Registration Form -->
            <form id="formRegister" class="auth-form d-none" action="/bw2/api/registerPlayer.php" method="POST">
              <div class="mb-3">
                <label for="registerName" class="form-label">Username</label>
                <input type="text" class="form-control" id="registerName" name="name" placeholder="Enter your username"
                  required>
              </div>
              <div class="mb-3">
                <label for="registerPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="registerPassword" name="password"
                  placeholder="Enter your password" required>
              </div>
              <!-- <div class="mb-3">
                <label for="registerClass" class="form-label">Class</label>
                <select class="form-select" id="registerClass" name="class" required>
                  <option value="">Select class</option>
                  <option value="0">Knight</option>
                  <option value="1">Paladin</option>
                  <option value="2">Mage</option>
                  <option value="3">Sorcerer</option>
                </select>
              </div> -->
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="registerTerms" name="terms" required>
                  <label class="form-check-label" for="registerTerms">
                    I agree to <a href="#">privacy policy & terms</a>
                  </label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary d-block w-100">Sign up</button>
            </form>
            <!-- End Registration Form -->

            <!-- Toggle between Login and Register forms -->
            <div class="text-center mt-3">
              <p id="toggleMessage">Don't have an account? <a href="#" id="toggleFormLink">Register here</a></p>
            </div>

            <!-- Notification Box -->
            <div id="notification" class="alert d-none" role="alert"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- Custom Script for Form Toggle and AJAX -->
 <!-- Custom Script for Form Toggle and AJAX -->
<script>
  $(document).ready(function () {
    // Toggle between login and register forms
    $('#toggleFormLink').click(function (e) {
      e.preventDefault();
      $('#formLogin, #formRegister').toggleClass('d-none');
      $('#toggleMessage').toggleText(
        "Already have an account? <a href='#' id='toggleFormLink'>Sign in instead</a>",
        "Don't have an account? <a href='#' id='toggleFormLink'>Register here</a>"
      );
    });

    // Function to toggle text in an element
    $.fn.toggleText = function (t1, t2) {
      this.html(this.html() === t1 ? t2 : t1);
      return this;
    };

    // Function to show notification message
    function showNotification(message, type) {
      $('#notification')
        .removeClass('d-none alert-success alert-danger')
        .addClass('alert-' + type)
        .text(message);
    }

    // Handle login form submission
    $('#formLogin').submit(function (event) {
      event.preventDefault(); // Prevent form submission

      $.ajax({
        type: 'POST',
        url: '/bw2/api/loginPlayer.php',
        data: $(this).serialize(),
        success: function (response) {
          console.log(response); // Debug response

          // Ensure response is parsed (in case it's a JSON string)
          if (typeof response === 'string') {
            response = JSON.parse(response);
          }

          if (response.status === 'success' && response.token) {
            localStorage.setItem('authToken', response.token);
            document.cookie = `token=Bearer ${response.token}; path=/`;


            showNotification('Login successful! Redirecting to dashboard...', 'success');
            setTimeout(function () {
              window.location.href = 'dashboard.php';
            }, 3000);
          } else {
            showNotification('Error: ' + response.message, 'danger');
          }
        },
        error: function (xhr, status, error) {
          console.error('Login Error:', error);
          showNotification('Error occurred while logging in.', 'danger');
        }
      });
    });

    // Handle registration form submission
    $('#formRegister').submit(function (event) {
      event.preventDefault(); // Prevent form submission

      $.ajax({
        type: 'POST',
        url: '/bw2/api/registerPlayer.php',
        data: $(this).serialize(),
        success: function (response) {
          console.log(response); // Debug response

          if (typeof response === 'string') {
            response = JSON.parse(response);
          }

          if (response.status === 'success') {
            showNotification('Registration successful!', 'success');
            setTimeout(function () {
              window.location.href = 'index.php';
            }, 1000);
          } else {
            showNotification('Error: ' + response.message, 'danger');
          }
        },
        error: function (xhr, status, error) {
          console.error('Registration Error:', error);
          showNotification('Error occurred while registering.', 'danger');
        }
      });
    });

    // Close notification handler
    $('#closeNotification').click(function () {
      $('#notification')
        .addClass('d-none')
        .removeClass('alert-success alert-danger')
        .text('');
    });
  });
</script>

</body>

</html>