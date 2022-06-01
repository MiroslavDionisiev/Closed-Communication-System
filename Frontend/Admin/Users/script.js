import { authenticate } from "../../utils.js";
import { authorize } from "../script.js";

window.onload = () => {
    authenticate();
    authorize();

    let getUserBanner = (user) => {
        let banner = document.createElement("div");
        banner.classList.add("user-banner");

        banner.innerHTML = `
            <img src="img/img-user.png" alt="User profile picture">
            <lu class="user-info">
                <li>${user.name}</li>
                <li>${user.email}</li>
                <li>${user.facultyNumber}</li>
                <li>${user.speciality}</li>
                <li>${user.faculty}</li>
                <li>${user.role}</li>
            </lu>
        `;

        return banner;
    };

    let fetchUsers = () => {
        fetch("/index.php/admin/users")
            .then((resp) => resp.json())
            .then((users) => {
                let list = document.getElementById("list-users");
                for (let user of users) {
                    list.appendChild(getUserBanner(user));
                }
            });
    };

    fetchUsers();
};
