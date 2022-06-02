function setUserName() {
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
}

function displayNewMessages() {
    setTimeout(displayNewMessages, 1000);
}

function displayAllMessages() {
    fetch('../../index.php/user/chat-rooms/messages', {
        method: 'POST',
        body: '5831793b-e1e8-11ec-89fe-b05adad6ff7b'
    })
    .then(res => {
        return res.json();
    })
    .then(data => {
        console.log(data);
    });
}

(() => {
    setUserName();
    displayAllMessages();
    displayNewMessages();
})();