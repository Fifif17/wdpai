const form = document.querySelector("form");
const emailInput = form.querySelector('input[name="email"]');
const messagesContainer = document.querySelector('.messages');


function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}


function markValidation(element, condition) {
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
}


function validateEmail() {
    setTimeout(function () {
            markValidation(emailInput, isEmail(emailInput.value));
        },
        100
    );
}


function displayMessage(message) {
    messagesContainer.innerHTML = `<p>${message}</p>`;
}


function handleSubmit(event) {
    event.preventDefault();

    if (isEmail(emailInput.value)) {
        displayMessage('Account registered successfully. Redirecting...');

        setTimeout(() => {
            form.submit();
        }, 1000);
    } else {
        displayMessage('Invalid email. Please enter a valid email address.');
    }
}


emailInput.addEventListener('keyup', validateEmail);
form.addEventListener('submit', handleSubmit);