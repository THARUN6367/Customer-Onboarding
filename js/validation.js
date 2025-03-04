document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('onboardingForm').addEventListener('submit', function (e) {
        let sections = document.querySelectorAll('.form-section');
        for (let section of sections) {
            if (!validateSection(section)) {
                e.preventDefault();
                alert('Please complete all required fields.');
                return false;
            }
        }
    });
});

function nextSection(nextId) {
    let currentSection = document.querySelector('.form-section:not([style*="display: none"])');
    let nextSection = document.getElementById(nextId);
    if (validateSection(currentSection)) {
        currentSection.style.display = 'none';
        nextSection.style.display = 'block';
    }
}

function previousSection(previousId) {
    let currentSection = document.querySelector('.form-section:not([style*="display: none"])');
    let previousSection = document.getElementById(previousId);
    currentSection.style.display = 'none';
    previousSection.style.display = 'block';
}

function validateSection(section) {
    let inputs = section.querySelectorAll('input[required]');
    for (let input of inputs) {
        if (!input.value.trim()) {
            input.classList.add('invalid');
            alert(`Please fill out the ${input.previousElementSibling.textContent} field.`);
            return false;
        } else {
            input.classList.remove('invalid');
        }

        if (input.name === 'email' || input.name === 'shop_email') {
            if (!validateEmail(input.value)) {
                input.classList.add('invalid');
                alert(`Please enter a valid email address.`);
                return false;
            } else {
                input.classList.remove('invalid');
            }
        }

        if (input.name === 'phone_number' || input.name === 'shop_phone_number') {
            if (!validatePhoneNumber(input.value)) {
                input.classList.add('invalid');
                alert(`Please enter a valid phone number.`);
                return false;
            } else {
                input.classList.remove('invalid');
            }
        }

        if (input.name === 'password') {
            if (!validatePassword(input.value)) {
                input.classList.add('invalid');
                alert(`Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.`);
                return false;
            } else {
                input.classList.remove('invalid');
            }
        }

        if (input.name === 'ifsc_code') {
            if (!validateIFSC(input.value)) {
                input.classList.add('invalid');
                alert(`Please enter a valid IFSC code.`);
                return false;
            } else {
                input.classList.remove('invalid');
            }
        }
    }
    return true;
}

function validateEmail(email) {
    const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(email);
}

function validatePhoneNumber(phoneNumber) {
    const re = /^\d{10}$/;
    return re.test(phoneNumber);
}

function validatePassword(password) {
    const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return re.test(password);
}

function validateIFSC(ifsc) {
    const re = /^[A-Za-z]{4}\d{7}$/;
    return re.test(ifsc);
}
