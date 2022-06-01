(() => {
    fetch(window.location.host + '/index.php/user/chat-rooms', {
        method: 'GET'
    })
    .then(res => {
        return res.json();
    })
    .then(data => {
        let ul = document.getElementById('chatRooms');
        data.forEach(element => {
            let li = document.createElement('li');

            let a = document.createElement('a');
            a.setAttribute('class', 'noStyle');
            a.setAttribute('href', "");

            let div = document.createElement('div');
            div.setAttribute('class', 'chatRoom');

            let ulRoomData = document.createElement('ul');
            ulRoomData.setAttribute('class', 'noStyle chatRoomData');
            let liName = createElement('li');
            liName.innerText = element['chatRoom']['name'];
            let liRole = createElement('li');
            if (element['chatRoom']['name'] === true) {
                liRole.innerText = "Роля в стаята: анонимен";
            }
            else {
                liRole.innerText = "Роля в стаята: неанонимен"; 
            }
            let liActive = createElement('li');
            liActive.innerText = 'Активна до: '.concat(element['chatRoom']['availabilityDate']);
            ulRoomData.appendChild('liName');
            ulRoomData.appendChild('liRole');
            ulRoomData.appendChild('liActive');

            let divUnread = document.createElement('div');
            div.setAttribute('class', 'unread');

            div.setAttribute(ulRoomData);
            div.appendChild(divUnread);
            a.appendChild(div);
            li.appendChild(a);
            ul.appendChild(li);  
        });
    });
})();