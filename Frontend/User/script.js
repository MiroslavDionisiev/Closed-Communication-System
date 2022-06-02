(() => {
    fetch('../../index.php/user', {
        method: 'GET'
    })
    .then(res => {
        return res.json();
    })
    .then(data => {
        let username = document.getElementsByClassName('userName')[0];
        username.innerText = data['name'];
    });

    fetch('../../index.php/user/chat-rooms', {
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
            if (element['unreadMessages'] > 0) {
                div.setAttribute('class', 'unreadChatRoom');
            }
            else {
                div.setAttribute('class', 'chatRoom');
            }

            let ulRoomData = document.createElement('ul');
            ulRoomData.setAttribute('class', 'noStyle chatRoomData');
            let liName = document.createElement('li');
            liName.innerText = element['userChatRoom']['chatRoom']['name'];
            let liRole = document.createElement('li');
            if (element['userChatRoom']['chatRoom']['isAnonymouse'] === true) {
                liRole.innerText = "Роля в стаята: анонимен";
            }
            else {
                liRole.innerText = "Роля в стаята: неанонимен"; 
            }
            let liActive = document.createElement('li');
            liActive.innerText = 'Активна до: '.concat(element['userChatRoom']['chatRoom']['availabilityDate']);
            ulRoomData.appendChild(liName);
            ulRoomData.appendChild(liRole);
            ulRoomData.appendChild(liActive);

            if (element['unreadMessages'] > 0) {
                let divUnread = document.createElement('div');
                divUnread.setAttribute('class', 'unreadMessage');

                div.appendChild(divUnread);
            }
        
            div.appendChild(ulRoomData);
            a.appendChild(div);
            li.appendChild(a);
            ul.appendChild(li);  
        });
    });
})();