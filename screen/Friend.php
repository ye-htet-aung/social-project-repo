<?php 
        include 'mainlayout.php';
        include __DIR__ . '/../database/config.php';
        
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo "You must log in first.";
            exit;
        }
        $user_id=$_SESSION["user_id"];
        $sql = "SELECT u.name,p.profile_picture 
                FROM users u 
                LEFT JOIN user_profiles p ON u.id = p.user_id 
                WHERE user_id != '$user_id'
                ORDER BY RAND() 
                LIMIT 20";
        $result = $con->query($sql);
        if (!$result) {
          die("Query failed: " . $con->error);
        }

?>


<link rel="stylesheet" href="/css/friend.css">
<div class="container">
        <!-- Header -->
        <div class="header">
          <a href="#" class="logo">facebook</a>
          <div class="header-right">
            <button class="notification-btn">
              <i class="fas fa-bell"></i>
            </button>
            <div class="avatar">
              <img src="dog3.jpg" alt="User">
            </div>
          </div>
        </div>
    
        <!-- Main Content -->
        <div class="main-content">
          <!-- Friends Card -->
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">Friends</h2>
              <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Search friends">
              </div>
            </div>
            <div class="card-content">

              <!-- Tabs -->
              <div class="tabs">
                <div class="tab active" data-tab="all">All Friends</div>

                <div class="tab" data-tab="requests">
                  Requests
                  <span class="notification-badge">3</span>
                </div>

                <div class="tab" data-tab="suggestions">Suggestions</div>
              </div>
    
              <!-- All Friends Tab -->
              <div class="tab-content active" id="all-tab">
                <div class="friend-grid">

                  <!-- Friend 1 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog.jpg" alt="Tun Aung Lin">
                      <span class="online-indicator"></span>
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Tun Aung Lin</div>
                      <div class="friend-meta">Active now</div>
                    </div>
                    <button class="btn btn-secondary">Message</button>
                  </div>
    
                  <!-- Friend 2 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog1.webp" alt="Kyaw Zaww Hein">
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Kyaw Zaww Hein</div>
                      <div class="friend-meta">Offline</div>
                    </div>
                    <button class="btn btn-secondary">Message</button>
                  </div>
    
                  <!-- Friend 3 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog.jpg" alt="Ye Htet Aung">
                      <span class="online-indicator"></span>
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Ye Htet Aung</div>
                      <div class="friend-meta">Active now</div>
                    </div>
                    <button class="btn btn-secondary">Message</button>
                  </div>
    
                  <!-- Friend 4 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog1.webp" alt="Htet Wai Yan Lin">
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Htet Wai Yan Lin</div>
                      <div class="friend-meta">Offline</div>
                    </div>
                    <button class="btn btn-secondary">Message</button>
                  </div>
    
                  <!-- Friend 5 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog.jpg" alt="Nyi Nyi Phyoe">
                      <span class="online-indicator"></span>
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Nyi Nyi Phyoe</div>
                      <div class="friend-meta">Active now</div>
                    </div><button class="btn btn-secondary">Message</button>
                  </div>
    
                  <!-- Friend 6 -->
                  <div class="friend-item">
                    <div class="avatar">
                      <img src="dog1.webp" alt="Kaung Myat Oo">
                    </div>
                    <div class="friend-info">
                      <div class="friend-name">Kaung Myat Oo</div>
                      <div class="friend-meta">Offline</div>
                    </div>
                    <button class="btn btn-secondary">Message</button>
                  </div>
                </div>
              </div>
    
              <!-- Friend Requests Tab -->
              <div class="tab-content" id="requests-tab">
                <!-- Request 1 -->
                <div class="friend-item">
                  <div class="avatar">
                    <img src="dog4.jpg" alt="Naing Lin Aung">
                  </div>
                  <div class="friend-info">
                    <div class="friend-name">Naing Lin Aung</div>
                    <div class="friend-meta">8 mutual friends • 3d</div>
                  </div>
                  <div class="action-buttons">
                    <button class="btn btn-primary">Confirm</button>
                    <button class="btn btn-secondary">Remove</button>
                  </div>
                </div>
    
                <!-- Request 2 -->
                <div class="friend-item">
                  <div class="avatar">
                    <img src="dog5.jpg" alt="Yarzar Soe Hter Kyaw">
                  </div>
                  <div class="friend-info">
                    <div class="friend-name">Yarzar Soe Hter Kyaw</div>
                    <div class="friend-meta">15 mutual friends • 1w</div>
                  </div>
                  <div class="action-buttons">
                    <button class="btn btn-primary">Confirm</button>
                    <button class="btn btn-secondary">Remove</button>
                  </div>
                </div>
    
                <!-- Request 3 -->
                <div class="friend-item">
                  <div class="avatar">
                    <img src="dog6.jpg" alt="Kaung Myat Phyoe">
                  </div>
                  <div class="friend-info">
                    <div class="friend-name">Kaung Myat Phyoe</div>
                    <div class="friend-meta">3 mutual friends • 2w</div>
                  </div>
                  <div class="action-buttons">
                    <button class="btn btn-primary">Confirm</button>
                    <button class="btn btn-secondary">Remove</button>
                  </div>
                </div>
              </div>
    
              <!-- Suggestions Tab -->
              <div class="tab-content" id="suggestions-tab">
    <?php
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {


            echo '<div class="friend-item">
            <div class="avatar">
              <img src="'.$row['profile_picture'].'" alt="Flash">
            </div>
            <div class="friend-info">
              <div class="friend-name">'.$row['name'].'</div>
              <div class="friend-meta">7 mutual friends</div>
            </div>
            <button class="btn btn-outline btn-icon">
              <i class="fas fa-user-plus"></i>
              Add Friend
            </button>

            <button class="btn btn-outline btn-icon">
              <i class="fas fa-user-plus"></i>
              Remove
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
    
          
          <div class="card">
            <div class="card-header">
              <h2 class="card-title">
                <i class="fas fa-users"></i>
                Friend Lists
              </h2>
              <div class="card-description">Your Friend lists is here!</div>
            </div>
            <div class="card-content">
              <!-- Close Friends -->
              <div class="friend-list-item">
                <div class="list-icon blue-bg">
                  <i class="fas fa-users blue-text"></i>
                </div>
                <div class="friend-info">
                  <div class="friend-name">Close Friends</div>
                  <div class="friend-meta">12 friends</div>
                </div>
              </div>
    
              <!-- Family -->
              <div class="friend-list-item">
                <div class="list-icon green-bg">
                  <i class="fas fa-users green-text"></i>
                </div>
                <div class="friend-info">
                  <div class="friend-name">Family</div>
                  <div class="friend-meta">8 friends</div>
                </div>
              </div>
    
              <!-- Work -->
              <div class="friend-list-item">
                <div class="list-icon purple-bg">
                  <i class="fas fa-users purple-text"></i>
                </div>
                <div class="friend-info">
                  <div class="friend-name">Work</div>
                  <div class="friend-meta">15 friends</div>
                </div>
              </div>
    
              <!-- Create New List -->
              <div class="friend-list-item">
                <div class="list-icon gray-bg">
                  <i class="fas fa-user-plus gray-text"></i>
                </div>
                <div class="friend-info">
                  <div class="friend-name">Create New List</div>
                </div>
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
              
              // Add active class to clicked tab
              this.classList.add('active');
              
              // Hide all tab contents
              document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
              });
              
              // Show the corresponding tab content
              const tabName = this.getAttribute('data-tab');
              document.getElementById(tabName + '-tab').classList.add('active');
            });
          });
        });
      </script>