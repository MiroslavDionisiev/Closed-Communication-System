import * as util from "../../utils.js";

const INVALID_EMAIL_ERROR = "Invalid email address";
const EMAIL_EXISTS_ERROR = "Email address already exists";

async function emailExists(email) {
    let response = await fetch(util.urlBackend(`/account/userExistsByEmail/${email}`),
    {
        method: "GET",
    }
    )

    if(response.ok){
        return (await response.text()) === 'true';
    } else {
        throw response.json();
    }
}

async function facultyNumberExists(facultyNumber) {
    let response = await fetch(util.urlBackend(`/account/userExistsByFacultyNumber/${facultyNumber}`),
        {
            method: "GET",
        }
    )

    if(response.ok){
        return (await response.text()) === 'true';
    } else {
        throw response.json();
    }
}

function getRegisterButtonElement() {
    return document.getElementById("register");
}

let isEmailAddressValid = false;
let isPasswordValid = false;
let isFacultyNumberInvalid = true;

function validateRegisterForm() {
    let buttonElement = getRegisterButtonElement();
    let isFormValid = isEmailAddressValid && isPasswordValid && !isFacultyNumberInvalid;
    if(isFormValid){
        buttonElement.disabled = false;
    } else {
        buttonElement.disabled = true;
    }
}

(async () => {
    if (await util.authenticate()) {
        window.location = util.urlFrontend('/User');
    }

    let emailRegex = new RegExp("(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\\])")
    let passwordRegex = new RegExp("^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$")

    document.getElementById("email").addEventListener('input', async (value) => {
        let emailInvalidInputElement = document.getElementById("error-email");
        const emailSpan = emailInvalidInputElement.children[0];
        let emailAddress = document.getElementById("email").value;
        let emailValid = true;

        if (!emailRegex.test(emailAddress)) {
            emailSpan.textContent = INVALID_EMAIL_ERROR;
            emailValid = false;
        } else {
            let exists = await emailExists(emailAddress);
            if (exists) {
                emailInvalidInputElement.style.display = "block";
                emailSpan.textContent = EMAIL_EXISTS_ERROR;
                emailValid = false;
            }
        }

        if (emailValid) {
            emailInvalidInputElement.style.display = "none";
        } else {
            emailInvalidInputElement.style.display = "block";
        }

        isEmailAddressValid = emailValid;
        validateRegisterForm();
    })

    document.getElementById("facultyNumber").addEventListener('input', async (value) => {
        let facultyErrorElement = document.getElementById("error-faculty-number");
        let facultyNumber = document.getElementById("facultyNumber").value;

        isFacultyNumberInvalid = await facultyNumberExists(facultyNumber);
            if (isFacultyNumberInvalid) {
                facultyErrorElement.style.display = "block";
            } else {
                facultyErrorElement.style.display = "none";
            }

            validateRegisterForm();
        }
    )

    document.getElementById("password").addEventListener('input', (value) => {
        let passwordInputElement = document.getElementById("error-password");
        let password = document.getElementById("password").value;
        isPasswordValid = passwordRegex.test(password);

        if(!isPasswordValid){
            passwordInputElement.style.display = "block";
        } else { 
            passwordInputElement.style.display = "none";
        }

        validateRegisterForm();
    });

    document.getElementById("register").addEventListener('click', (event) => {
        let options = {
            method: "Post",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                userEmail: document.getElementById("email").value,
                userPassword: document.getElementById("password").value,
                userName: document.getElementById("fullName").value,
                studentFacultyNumber: document.getElementById("facultyNumber").value,
                studentYear: document.getElementById("course").value,
                studentSpeciality: document.getElementById("speciality").value,
                studentFaculty: document.getElementById("faculty").value
            }),
        };

        fetch(util.urlBackend('/account/register'), options).then(
            (resp) => {
                if(resp.ok) {
                     window.location = util.urlFrontend('/Account/Login');
                }
            }
        );
    })
})();
