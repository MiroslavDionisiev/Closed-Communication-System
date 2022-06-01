import { authenticate } from '../utils.js';

export function authorize(authority) {
    fetch(`/index.php/auth/authorize?authority=${authority}`).then((resp) => {
        if (resp.status == 401) {
            window.location.replace("../Frontend/Login");
        }
    });
}

window.onload = () => {
    authenticate();
    authorize('ADMIN');
};
