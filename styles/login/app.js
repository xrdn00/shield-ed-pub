const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

const openModalBtn = document.getElementById('openModalBtn');
const modalOverlay = document.getElementById('modalOverlay');
const closeBtn = document.getElementById('closeBtn');

closeBtn.addEventListener('click', () => {
  modalOverlay.style.display = 'none';
});

modalOverlay.addEventListener('click', (event) => {
  if (event.target === modalOverlay) {
    modalOverlay.style.display = 'none';
  }
});

$("#signupForm").submit(function(event) {
  event.preventDefault();

  // Perform the AJAX post
  $.post("process.php", $(this).serialize(), function(data) {
      // Parse the JSON response from the server
      var responseData = JSON.parse(data);

      // If the response indicates a successful sign up
      if (responseData.success) {
          // Show the modal
          $("#modalOverlay").css('display', 'flex');
      } else {
          // Handle sign up failure (you can customize this part)
          alert("Sign up failed: " + responseData.error);
      }
  });
});

