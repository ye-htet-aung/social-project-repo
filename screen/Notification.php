<?php
    include 'mainlayout.php';
?>
<link rel="stylesheet" href="/css/notification.css">
<div id="main">
    <div id="notificationContainer">
        <div id="notificationHeader">
            <h1>Notifications <span id="num-of-noti">3</span></h1>
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <main id="notification-list">
            <!-- Notifications will be inserted dynamically here -->
        </main>
    </div>
</div>

<script src="/notification.js"></script>
<script>
    // Function to fetch and display notifications dynamically
    function fetchNotifications() {
        fetch('/getnotification.php')
    .then(response => {
        // Check if the response is not JSON, if it's HTML (error), log it
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const notificationContainer = document.getElementById('notification-list');
        const numOfNoti = document.getElementById('num-of-noti');
        notificationContainer.innerHTML = ''; // Clear existing notifications

        if (data.notifications.length === 0) {
            const noNotificationsMessage = document.createElement('p');
            noNotificationsMessage.textContent = 'You have no notifications.';
            notificationContainer.appendChild(noNotificationsMessage);
        } else {
            data.notifications.forEach(notification => {
                const notificationDiv = document.createElement('div');
                notificationDiv.classList.add('notation');
                notificationDiv.classList.add(notification.is_read ? 'read' : 'unread');
                
                const profileImageUrl = notification.profile_image_url || '/default_profile_image.jpg'; // Default image if profile_image_url is empty
                
                const notificationContent = `
                    <img src="${profileImageUrl}" alt="photo">
                    <div class="description">
                        <p>${notification.username} ${notification.notification_text}</p>
                        <p id="notif-time">${formatTime(notification.created_at)}</p>
                    </div>
                `;
                notificationDiv.innerHTML = notificationContent;

                // Add click listener to mark as read when clicked
                notificationDiv.addEventListener('click', function() {
                    markAsRead(notification.id, notificationDiv);
                });

                notificationContainer.appendChild(notificationDiv);
            });
        }

        numOfNoti.textContent = data.notifications.length; // Update notification count
    })
    .catch(error => console.error('Error fetching notifications:', error));

    }

    // Function to mark notification as read
    function markAsRead(notificationId, notificationDiv) {
        fetch(`/markNotificationAsRead.php?id=${notificationId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationDiv.classList.remove('unread');
                    notificationDiv.classList.add('read');
                }
            })
            .catch(error => console.log('Error marking notification as read:', error));
    }

    // Format timestamp to a human-readable time (e.g., "1 minute ago")
    function formatTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diffInSeconds = Math.floor((now - time) / 1000);
        
        if (diffInSeconds < 60) return `${diffInSeconds} second${diffInSeconds === 1 ? '' : 's'} ago`;
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `${diffInMinutes} minute${diffInMinutes === 1 ? '' : 's'} ago`;
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours} hour${diffInHours === 1 ? '' : 's'} ago`;
        const diffInDays = Math.floor(diffInHours / 24);
        if (diffInDays < 30) return `${diffInDays} day${diffInDays === 1 ? '' : 's'} ago`;
        const diffInMonths = Math.floor(diffInDays / 30);
        if (diffInMonths < 12) return `${diffInMonths} month${diffInMonths === 1 ? '' : 's'} ago`;
        const diffInYears = Math.floor(diffInMonths / 12);
        return `${diffInYears} year${diffInYears === 1 ? '' : 's'} ago`;
    }

    // Fetch notifications when the page is loaded
    document.addEventListener('DOMContentLoaded', fetchNotifications);
</script>
