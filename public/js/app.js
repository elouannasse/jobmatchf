// Main app.js
document.addEventListener('DOMContentLoaded', function() {
  // Toggle sidebar
  const sidebarToggle = document.getElementById('sidebarToggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector('.sidebar').classList.toggle('active');
      document.querySelector('.main-content').classList.toggle('active');
    });
  }

  // Form validation
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });

  // Initialize Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize Bootstrap popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Date picker default configuration (if needed)
  const dateInputs = document.querySelectorAll('input[type="date"]');
  if (dateInputs) {
    dateInputs.forEach(input => {
      if (!input.value) {
        // Set default placeholder (optional)
      }
    });
  }

  // Custom file input labels
  const fileInputs = document.querySelectorAll('.custom-file-input');
  if (fileInputs) {
    fileInputs.forEach(input => {
      input.addEventListener('change', function(e) {
        const fileName = this.files[0].name;
        const nextSibling = e.target.nextElementSibling;
        if (nextSibling) {
          nextSibling.innerText = fileName;
        }
      });
    });
  }
});
