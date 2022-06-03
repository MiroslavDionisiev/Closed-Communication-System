export const GLOBALS = {
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
    return fetch("/index.php/account/is-authenticated").then((resp) => {
        if (resp.status == 401) {
            window.location.replace("/Frontend/Login");
        }
        return resp.json();
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
