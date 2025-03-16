<?php 
    include 'mainlayout.php';
    include __DIR__ . '/../database/config.php';
    
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "You must log in first.";
        exit;
    }
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT u.id, u.name, p.profile_picture
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.id != ?
            AND u.id NOT IN (
                SELECT fr.user_id
                FROM friends fr
                WHERE fr.friend_id = ? AND fr.status = 'accepted'
            )
            ORDER BY RAND()
            LIMIT 20";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<link rel="stylesheet" href="/css/friend.css">
<div class="container">
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Friends</h2>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" class="search-input" placeholder="Search friends">
                </div>
            </div>

            <div class="card-content">
                <div class="tabs">
                    <div class="tab active" data-tab="requests">
                        Requests <span class="notification-badge">3</span>
                    </div>
                    <div class="tab" data-tab="suggestions">Suggestions</div>
                </div>
                      <!-- Friend Requests Tab (Dynamic) -->
              <div class="tab-content active" id="requests-tab">
                <!-- Friend requests will be appended here dynamically -->
                    <div>Loading friend requests...</div>
              </div>

                <div class="tab-content" id="suggestions-tab">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="friend-item">
                                    <div class="avatar">
                                        <img src="http://localhost:3000/' . htmlspecialchars($row["profile_picture"]) . '" alt="">
                                    </div>
                                    <div class="friend-info">
                                        <div class="friend-name">' . htmlspecialchars($row['name']) . '</div>
                                        <div class="friend-meta">7 mutual friends</div>
                                    </div>
                                    <button class="btn btn-outline btn-icon" id="addbtn" onclick="addFriend(' .$row['id']. ')">
                                        <i class="fas fa-user-plus"></i> Add Friend
                                    </button>
                                    <button class="btn btn-outline btn-icon">
                                        <i class="fas fa-user-minus"></i> Remove
                                    </button>
                                  </div>';
                        }
                    } else {
                        echo '<div>No suggestions available.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('.tab');
  
  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      const tabName = this.getAttribute('data-tab');
      document.getElementById(tabName + '-tab').classList.add('active');
    });
  });
  
  // Fetch and display friend requests for the Requests tab
  fetch('/fetch_friend_request.php')
    .then(response => response.json())
    .then(data => {
      const requestsTab = document.getElementById('requests-tab');
      requestsTab.innerHTML = ''; // Clear loading message
      const badge = document.querySelector('.tab[data-tab="requests"] .notification-badge');
      if (data.error) {
        requestsTab.innerHTML = '<div>' + data.error + '</div>';
        badge.textContent = '0';
        return;
      }
      
      // Update the notification badge count
      badge.textContent = data.length;
      
      if (data.length > 0) {
        data.forEach(request => {
          const friendItem = document.createElement('div');
          friendItem.className = 'friend-item';
          friendItem.innerHTML = `
            <div class="avatar">
              <img src="http://localhost:3000/${request.profile_picture}" alt="${request.name}">
            </div>
            <div class="friend-info">
              <div class="friend-name">${request.name}</div>
              <div class="friend-meta">Pending friend request</div>
            </div>
            <button class="btn btn-primary" onclick="confirmFriend(${request.r_id})">Confirm</button>
            <button class="btn btn-secondary" onclick="removeFriendRequest(${request.r_id})">Remove</button>
          `;
          requestsTab.appendChild(friendItem);
        });
      } else {
        requestsTab.innerHTML = '<div>No friend requests.</div>';
      }
    })
    .catch(error => {
      console.error('Error fetching friend requests:', error);
      document.getElementById('requests-tab').innerHTML = '<div>Error loading friend requests.</div>';
    });
});

async function addFriend(friendId) {
  console.log(friendId);
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('friend_id', friendId);
  
  try {
        const response = await fetch('http://localhost:3000/add_friend.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("friend request sent successfully!");
    } catch (error) {
        alert.log(error.message)
        console.error("Error add friend request:", error.message);
    }
}

async function confirmFriend(requestId) {
  alert(requestId);
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('request_id', requestId);
  
  try {
        const response = await fetch('http://localhost:3000/confirm_friend_request.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("you are friend now!");

    } catch (error) {
        console.error("Error conforming friend request:", error.message);
    }
}

async function removeFriendRequest(requestId) {
  alert(requestId);
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('request_id', requestId);
  
  try {
        const response = await fetch('http://localhost:3000/remove_friend_request.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("you are removing a friend!");

    } catch (error) {
        console.error("Error conforming friend request:", error.message);
    }
}
</script>