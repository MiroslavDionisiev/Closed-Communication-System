export function openUserInfo(userId) {
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
                            <img src="/Frontend/Admin/Users/img/img-user.png" alt="User profile picture">
                        </div>
                        <div class="user-details">
                            <ul class="user-info">
                            </ul>
                        </div>
                    </section>
                `;

            let closeInfo = () => {
                try {
                    info.parentNode.removeChild(info);
                } catch (e) {}
            };

            info.addEventListener("click", (event) => {
                if (event.target.classList.contains("curtain")) {
                    closeInfo();
                }
            });
            info.querySelector(".user-info-close").addEventListener(
                "click",
                () => {
                    closeInfo();
                }
            );
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
        });
}

export function getUserBanner(user) {
    let banner = document.createElement("div");

    banner.innerHTML = `
            <a class="user-banner hover-invert">
                <figure>
                    <img src="/Frontend/Admin/Users/img/img-user.png" alt="User profile picture">
                    <figcaption class="banner-user-name">
                        <div>${user.userName}</div>
                        <div>${user.userIdentity}</div>
                    </figcaption>
                </figure>
            </a>
        `;

    banner.querySelector("a").addEventListener("click", (event) => {
        if (!event.target.classList.contains("select-user-checkbox")) {
            openUserInfo(event.currentTarget.getAttribute("user-id"));
        }
    });
    banner.querySelector("a").setAttribute("user-id", user.userId);

    return banner.querySelector("a");
}

export async function getAllUsers() {
    return fetch("/index.php/admin/users")
        .then(async (resp) => {
            if (resp.status != 200) {
                throw await resp.json();
            }
            return resp.json();
        })
        .catch((err) => {
            console.log(err);
        });
}

export function authorize(user) {
    if (user.userRole !== USER_ROLES.ADMIN_ROLE) {
        window.location.replace("/Frontend/User");
    }
}
