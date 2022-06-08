import * as util from "../../utils.js";

(async () => {
    if (await util.authenticate()) {
        window.location = util.urlFrontend('/User');
    }
    
    document.getElementById("login").addEventListener('click', (event) => {
        let options = {
            method: "Post",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                userEmail: document.getElementById("email").value,
                userPassword: document.getElementById("password").value,
            }),
        };

        fetch(util.urlBackend('/account/login'), options).then(
            (resp) => {
                if (resp.ok) {
                    document.getElementById("error").style.display = "none";
                    window.location = util.urlFrontend('/User');
                } else {
                    document.getElementById("error").style.display = "block";
                }
            }
        );
    });

    document.getElementById("register").addEventListener('click', (event) => {
        window.location = util.urlFrontend('/Account/Register');
    });
})();
