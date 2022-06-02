export function authenticate() {
    fetch("/index.php/auth/authorize").then((resp) => {
        if (resp.status == 401) {
            window.location.replace("../Frontend/Login");
        }
    });
}
