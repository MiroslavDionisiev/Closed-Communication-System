export const USER_ROLES = {
    ADMIN_ROLE: "ADMIN",
    USER_ROLE: "USER",
};

export const ALERT_TYPE = {
    SUCCESS: "alert-success",
    DANGER: "alert-danger",
    WARNING: "alert-warning",
    INFO: "alert-info",
};

export function urlFrontend(location) {
    let parts = window.location.pathname.split(/Frontend/i, 2);
    parts[1] = location;
    return parts.join('Frontend');
}

export function urlBackend(location) {
    let parts = window.location.pathname.split(/Frontend/i, 2);
    parts[1] = location;
    return parts.join('index.php');
}

export async function authenticate() {
    return fetch(urlBackend("/account/is-authenticated"))
        .then(async (resp) => {
            if (resp.status == 401) {
                let urlPathName = window.location.pathname.toLowerCase();
                if(urlPathName != urlFrontend("/Account/Login/").toLowerCase() && urlPathName != urlFrontend("/Account/Register/").toLowerCase()) { 
                    window.location = urlFrontend("/Account/Login/");
                }
                return null;
            } else if (resp.status == 200) {
                return resp.json();
            } else {
                throw await resp.json();
            }
        })
        .catch((err) => {
            console.log(err.error);
            return null;
        });
}

export function popAlert(msg, alertType = ALERT_TYPE.INFO) {
    let alertBox = document.createElement("div");
    alertBox.classList.add("alert-box");
    alertBox.id = "alert-box";
    alertBox.innerHTML = `
        <div class="alert ${alertType}">
            <p class="alert-msg">${msg}</p>
            <button type="button" class="alert-close">
                <span>&times;</span>
            </button>
        </div>
    `;

    let alert = alertBox.querySelector(".alert");

    let removeAlert = (target) => {
        target.style.opacity = 0;
        target.addEventListener("transitionend", () => {
            target.parentNode.removeChild(target);
        });
    };

    alert.addEventListener("click", (event) =>
        removeAlert(event.currentTarget)
    );
    setTimeout(removeAlert, 3000, alert);

    let box = document.getElementById("alert-box");
    if (box === null) {
        document.documentElement.appendChild(alertBox);
    } else {
        document.getElementById("alert-box").appendChild(alert);
    }
}

export async function setHeader(user) {
    let header = document.createElement("header");
    header.classList.add("site-header");
    header.innerHTML = `
    <a href="${urlFrontend('/User')}" class="app-name">Closed Communication System</a>
    <section class="right-side">
        <a href="${urlFrontend('/Account/Login')}" class="header-btn">Вход</a>
        <a href="${urlFrontend('/Account/Register')}" class="header-btn">Регистрация</a>
    </section>
    `;

    document.body.insertBefore(header, document.body.firstChild);

    if (user !== null) {
        let rightSide = header.querySelector(".right-side");
        for (let btn of rightSide.querySelectorAll("button"))
            rightSide.removeChild(btn);
        rightSide.innerHTML = `
            <p class="user-name">${user.userName}</p>
            ${user.userRole === USER_ROLES.ADMIN_ROLE ? `<a href="${urlFrontend('/Admin')}" class="header-btn">Админ панел</a>` : ''}
            <button class="header-btn" type="button">Изход</button>
        `;
        rightSide.querySelector("button").addEventListener("click", () => {
            fetch(urlBackend("/account/logout"))
                .then(async (resp) => {
                    if(resp.status >= 400) {
                        throw await resp.json();
                    }
                    window.location = urlFrontend("/Account/Login");
                })
                .catch((err) => {
                    popAlert(err.error);
                });
        });
    }
}
