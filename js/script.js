// script.js
window.onload = function() {
    // Selecting the profile dropdown
    let profile = document.querySelector('.profile');

    // Handling the click event on the user button
    document.querySelector('#user-btn').onclick = () => {
        // Toggle the 'active' class on the profile dropdown
        profile.classList.toggle('active');
    }

    // Handling the scroll event on the window
    window.onscroll = () => {
        // Remove the 'active' class from the profile dropdown
        profile.classList.remove('active');
    }

    // Limiting the length of input for number inputs
    document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
        inputNumber.oninput = () => {
            // If the input length exceeds the maxLength attribute, trim it
            if (inputNumber.value.length > inputNumber.maxLength) {
                inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
            }
        }
    });


}