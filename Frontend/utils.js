export const GLOBALS = {
    "ADMIN_ROLE": "ADMIN",
    "USER_ROLE": "USER"
};

export async function authenticate() {
    return fetch("/index.php/account/is-authenticated").then((resp) => {
        if (resp.status == 401) {
            window.location.replace("/Frontend/Login");
        }
        return resp.json();
    });
}
