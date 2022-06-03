import * as util from "../../utils.js";

(async () => {
    let user = await util.authenticate();

    // if (user.userRole !== GLOBALS.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

    util.setHeader(user);

    function openUserInfo(userId) {
        fetch(`/index.php/admin/users/${userId}`)
            .then((resp) => {
                if (resp.status != 200) {
                    alert("User doesn't exist");
                }
                return resp.json();
            })
            .then((user) => {
                let info = document.createElement("div");
                info.classList.add("curtain");

                info.innerHTML = `
                    <section class="user-info-wrapper">
                        <button type="button" class="user-info-close">
                            <span>&times;</span>
                        </button>
                        <div class="img">
                            <img src="img/img-user.png" alt="User profile picture">
                        </div>
                        <div class="user-details">
                            <ul class="user-info">
                            </ul>
                        </div>
                    </section>
                `;


                let closeInfo = () => {
                    info.parentNode.removeChild(info);
                }
                
                info.addEventListener("click", (event) => {
                    if (event.target.classList.contains("curtain")) {
                        closeInfo();
                    }
                });
                info.querySelector(".user-info-close").addEventListener("click", () => {
                    closeInfo(info);
                });
                document.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        closeInfo();
                    }
                });

                for (let key of Object.keys(user)) {
                    let li = document.createElement("li");
                    li.innerHTML = `
                        <p class="user-info-key">${key}:</p>
                        <p class="user-info-value">${user[key]}</p>
                    `;

                    info.querySelector(".user-info").appendChild(li);
                }

                document.documentElement.appendChild(info);
                info.focus();
            });
    }

    let getUserBanner = (user) => {
        let banner = document.createElement("div");

        banner.innerHTML = `
            <a class="user-banner">
                <figure>
                    <img src="img/img-user.png" alt="User profile picture">
                    <figcaption class="banner-user-name"></figcaption>
                </figure>
            </a>
        `;

        banner.querySelector(".banner-user-name").innerHTML = user.userName;
        banner
            .querySelector("a")
            .addEventListener("click", (event) =>
                openUserInfo(event.currentTarget.getAttribute("user-id"))
            );
        banner.querySelector("a").setAttribute("user-id", user.userId);

        return banner.querySelector("a");
    };

    fetch("/index.php/admin/users")
        .then(async (resp) => {
            if (resp.status != 200) {
                throw await resp.json();
            }
            return resp.json();
        })
        .then((users) => {
            let list = document.getElementById("list-users");
            for (let user of users) {
                list.appendChild(getUserBanner(user));
            }
        })
        .catch((err) => {
            console.log(err);
        });
})();
