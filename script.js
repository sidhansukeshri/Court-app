document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch court names from the server
    async function fetchCourtNames(query) {
        const response = await fetch(`search_court.php?q=${query}`);
        const data = await response.json();
        return data.map(item => item.court_name);
    }

    // Function to fetch staff names from the server
    async function fetchStaffNames(query) {
        const response = await fetch(`search_staff.php?q=${query}`);
        const data = await response.json();
        return data.map(item => item.display_name);
    }

    // Function to fetch role names from the server
    async function fetchRoleNames(query) {
        const response = await fetch(`search_role.php?q=${query}`);
        const data = await response.json();
        console.log("Role data received:", data);
        return data.map(item => item.role_name);
    }

    // Initialize Select2 for Court Name input
    $('#court_name').select2({
        data: [],
        placeholder: 'Select Court Name',
        minimumInputLength: 0, // Allow searching within the loaded options
    });

    const courtNameInput = document.getElementById("court_name");

    // Fetch and display court names initially
    fetchCourtNames('').then(data => {
        data.forEach(option => {
            const newOption = new Option(option, option, false, false);
            courtNameInput.appendChild(newOption);
        });
    });

    // Initialize Select2 for Staff Name input
    $('#staff_name').select2({
        data: [],
        placeholder: 'Select Staff Name',
        minimumInputLength: 0, // Allow searching within the loaded options
    });

    const staffNameInput = document.getElementById("staff_name");

    // Fetch and display staff names initially
    fetchStaffNames('').then(data => {
        data.forEach(option => {
            const newOption = new Option(option, option, false, false);
            staffNameInput.appendChild(newOption);
        });
    });

    // Initialize Select2 for Role input
    $('#role').select2({
        data: [],
        placeholder: 'Select Role',
        minimumInputLength: 0, // Allow searching within the loaded options
    });

    const roleInput = document.getElementById("role");

    // Fetch and display role names initially
    fetchRoleNames('').then(data => {
        data.forEach(option => {
            const newOption = new Option(option, option, false, false);
            roleInput.appendChild(newOption);
        });
    });

    // Your existing code for input labels, form submission, and more...
    const inputLabels = document.querySelectorAll(".input-label");
    const inputs = document.querySelectorAll("input, select");
    const passwordError = document.getElementById("password-error");
    const usernameError = document.getElementById("username-error");
    const formMessages = document.getElementById("form-messages");

    inputLabels.forEach((input, index) => {
        if (input instanceof Element) { // Check if input is a DOM element
            input.addEventListener("focus", () => {
                inputLabels[index].classList.add("active");
            });
    
            input.addEventListener("blur", () => {
                if (input.value === "") {
                    inputLabels[index].classList.remove("active");
                }
            });
    
            if (input.value !== "") {
                inputLabels[index].classList.add("active");
            }
        }
    });
        

    document.getElementById("registration-form").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("register.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(responseText => {
                try {
                    const data = JSON.parse(responseText);
                    if (data.success) {
                        window.location.href = "login.html";
                    } else {
                        usernameError.textContent = data.message.username || "";
                        passwordError.textContent = data.message.password || "";
                        formMessages.textContent = data.message.other || "";
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
            });
    });
});
