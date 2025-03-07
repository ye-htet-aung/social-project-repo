const unReadMessages = document.querySelectorAll('.unread');
const unReadMessagesCount = document.getElementById('num-of-notifi');

unReadMessagesCount.innerText = unReadMessages.length;

unReadMessages.forEach((message) =>{
    message.addEventListener('click',()=>{
        message.classlist.remove('unread');
        const newUnreadMessages = document.querySelectorAll('unread');
        unReadMessagesCount,innerText = newUnreadMessages.length;
    });
});