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

export async function authenticate() {
    return fetch("/index.php/account/is-authenticated")
        .then(async (resp) => {
            if (resp.status == 401) {
                window.location.replace("/Frontend/Login");
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

    alert.style.opacity = 0.9;

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
    <a href="/Frontend/User" class="app-name">Closed Communication System</a>
    <section class="right-side">
        <a href="/Frontend/Login" class="header-btn">Вход</a>
        <a href="/Frontend/Register" class="header-btn">Регистрация</a>
    </section>
    `;

    document.body.insertBefore(header, document.body.firstChild);

    if (user !== null) {
        let rightSide = header.querySelector(".right-side");
        for (let btn of rightSide.querySelectorAll("button"))
            rightSide.removeChild(btn);
        rightSide.innerHTML = `
            <p class="user-name">${user.userName}</p>
            <button class="header-btn">Изход</button>
        `;
        rightSide.querySelector("button").addEventListener("click", () => {
            fetch("/index.php/account/logout")
                .then(async (resp) => {
                    if (resp.ok) {
                        window.location.replace("/Frontend/Login");
                    } else {
                        throw await resp.json();
                    }
                })
                .catch((err) => {
                    popAlert(err.error);
                });
        });
    }
}
