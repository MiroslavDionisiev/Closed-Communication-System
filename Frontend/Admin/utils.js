import * as util from "../utils.js";

export function openUserInfo(userId) {
    fetch(util.urlBackend(`/admin/users/${userId}`))
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
                            <img src="${util.urlFrontend(
                                "/Admin/Users/img/img-user.png"
                            )}" alt="User profile picture">
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
    let banner = document.createElement("li");
    banner.classList.add("user-banner", "hover-invert");

    banner.innerHTML = `
            <a>
                <figure>
                    <img src="${util.urlFrontend(
                        "/Admin/Users/img/img-user.png"
                    )}" alt="User profile picture">
                    <figcaption class="banner-user-name">
                        <div>${user.userName}</div>
                        <div>${user.userIdentity}</div>
                    </figcaption>
                </figure>
            </a>
        `;

    let link = banner.querySelector("a");
    link.addEventListener("click", (event) => {
        if (!event.target.classList.contains("select-user-checkbox")) {
            openUserInfo(event.currentTarget.getAttribute("user-id"));
        }
    });
    link.setAttribute("user-id", user.userId);

    return banner;
}

export async function getAllUsers() {
    return fetch(util.urlBackend("/admin/users"))
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
    if (user.userRole !== util.USER_ROLES.ADMIN_ROLE) {
        util.reditectTo("/User");
    }
}

export function createUsersSection(users) {
    let section = document.createElement("section");
    section.classList.add("users-section");

    section.innerHTML = `
        <div class="users-filter">
            <label for="filter-value">Търси:</label>
            <input id="filter-value" type="text">
            <select id="filter-type" title="filterType">
                <option>Име</option>
                <option>Факултетен номер</option>
            </select>
        </div>
        <ul id="list-users" class="wrapper-list"></ul>
    `;

    let list = section.querySelector("#list-users");
    for (let user of users) {
        let banner = getUserBanner(user);
        list.appendChild(banner);
        user["banner"] = banner;
    }

    let search = section.querySelector("#filter-value");
    let filterType = section.querySelector("#filter-type");

    search.addEventListener("input", (event) => {
        let filter = (() => {
            switch (filterType.value) {
                case "Име":
                    return "userName";
                default:
                    return "studentFacultyNumber";
            }
        })();
        let input = event.currentTarget.value;

        for (let user of users) {
            if (!input || (user[filter] !== undefined && user[filter].match(new RegExp(input, "gi")))) {
                if (!list.contains(user.banner)) {
                    list.appendChild(user.banner);
                }
            } else if (list.contains(user.banner)) {
                list.removeChild(user.banner);
            }
        }
    });

    return section;
}
