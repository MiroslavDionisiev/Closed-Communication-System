import { authenticate, GLOBALS } from "../../utils.js";

window.onload = async () => {
    // let user = await authenticate();

    // if (user.userRole !== GLOBALS.ADMIN_ROLE) {
    //     window.location.replace("/Frontend/User");
    // }

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
                info.addEventListener("click", (event) => {
                    if (event.target.classList.contains("curtain")) {
                        event.currentTarget.parentNode.removeChild(
                            event.currentTarget
                        );
                    }
                });
                document.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        let curtain = document.querySelector(".curtain");
                        if (curtain) {
                            curtain.parentNode.removeChild(curtain);
                        }
                    }
                });

                info.innerHTML = `
                    <section class="user-info-wrapper">
                        <img src="img/img-user.png" alt="User profile picture">
                        <div class="user-details">
                            <ul class="user-info-keys">
                            </ul>
                            <ul class="user-info-values">
                            </ul>
                        </div>
                    </section>
                `;

                for (let key of Object.keys(user)) {
                    let li1 = document.createElement("li");
                    li1.innerHTML = key;
                    info.querySelector(".user-info-keys").appendChild(li1);

                    let li2 = document.createElement("li");
                    li2.innerHTML = user[key];
                    info.querySelector(".user-info-values").appendChild(li2);
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
                    <figcaption class="user-name"></figcaption>
                </figure>
            </a>
        `;

        banner.querySelector(".user-name").innerHTML = user.userName;
        banner
            .querySelector("a")
            .addEventListener("click", (event) =>
                openUserInfo(event.currentTarget.getAttribute("user-id"))
            );
        banner.querySelector("a").setAttribute("user-id", user.userId);

        return banner.querySelector("a");
    };

    fetch("/index.php/admin/users")
        .then((resp) => resp.json())
        .then((users) => {
            let list = document.getElementById("list-users");
            for (let user of users) {
                list.appendChild(getUserBanner(user));
            }
        });
};
