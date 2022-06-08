import * as util from "../../utils.js";

(async () => {
    if (await util.authenticate()) {
        window.location = util.urlFrontend('/User');
    }

    let emailRegex = new RegExp("(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\\])")
    let passwordRegex = new RegExp("^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$")

    document.getElementById("email").addEventListener('input', (value) => {
        let emailInputElement = document.getElementById("error-email");
        let email = document.getElementById("email").value;

        if(!emailRegex.test(email)){
            emailInputElement.style.display = "block";
        } else {
            emailInputElement.style.display = "none"
        }
    })

    document.getElementById("password").addEventListener('input', (value) => {
        let passwordInputElement = document.getElementById("error-password");
        let password = document.getElementById("password").value;

        if(!passwordRegex.test(password)){
            passwordInputElement.style.display = "block";
        } else { 
            passwordInputElement.style.display = "none";
        }
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
