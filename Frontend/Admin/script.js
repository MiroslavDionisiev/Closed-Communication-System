import { authenticate, GLOBALS } from '../utils.js';

window.onload = async () => {
    let user = await authenticate();

    if (user.userRole !== GLOBALS.ADMIN_ROLE) {
        window.location.replace("/Frontend/User");
    }
};
