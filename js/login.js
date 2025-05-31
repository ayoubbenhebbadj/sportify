document.getElementById("registrationForm").addEventListener("submit", function(event) {
    let age = document.getElementById("ageInput").value;
    let ageWarning = document.getElementById("ageWarning");

    if (age < 12) {
        ageWarning.style.display = "block";
        event.preventDefault();
    } else {
        ageWarning.style.display = "none";
        alert("Registration successful!");
    }
});
