
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile UI</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

       /* Parent Wrapper */
       .user-wrapper {
            position: relative;
            margin-top: 10px;
            width: 100%;
            display: inline-block;
        }

        /* Background Container */
        .user-container-bg {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #dcdfe4;
            border-radius: 10px;
            width: 100%;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 15px;
            gap: 20px;
            z-index: 1;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.15);
        }

        /* Icons */
        .user-container-bg i {
            font-size: 18px;
            color: #333;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .user-container-bg i:hover {
            transform: scale(1.2);
            color: #1877f2;
        }

        /* Main User Container */
        .user-container {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 10px;
            padding: 10px 15px;
            width: 100%;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
            transition: width 0.3s ease-in-out;
        }

        /* Profile Picture */
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #1877f2;
        }

        /* User Name */
        .user-name {
            margin-left: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            transition: opacity 0.3s ease-in-out;
        }

        /* Hover Effect - Shrink to 60% */
        .user-wrapper:hover .user-container {
            width: 60%;
        }

        /* Hide Name When Shrinking */
        .user-wrapper:hover .user-name {
            opacity: 0;
        }
        /* Back Arrow */
        #back-arrow {
            font-size: 15px;
            color: #555;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            margin-right: 10px; /* Space between arrow and input */
        }

        #back-arrow:hover {
            color: #1877f2;
        }

        /* Search Form */
        #search-form {
            flex: 1;
            display: flex;
            align-items: center;
        }

        /* Search Input */
        #search-input {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #0071ff;
            outline: none;
            font-size: 14px;
            border-radius: 20px;
            background-color: #e4e6eb;
        }

        /* Placeholder Styling */
        #search-input::placeholder {
            color: #888;
        }
        #button-Search {
            display: flex;
            gap: 10px;
        }

        /* Default Button Styling */
        #button-Search button {
            padding: 10px 20px;
            font-size: 14px;
            border:1px solid #0071ff;
            background-color: #e4e6eb;
            color: #0071ff;
            border-radius: 10px; /* Rounded square */
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        /* Hover Effect */
        #button-Search button:hover {
            background-color: #0071ff;
            color: white;
        }

        /* Active Button State */
        #button-Search button.active {
            background-color: #0071ff;
            color: white;
        }
        #straight {
            width: 100%;  /* Adjust width as needed */
            padding: 1px;
            background-color: #0071ff; /* Light background */
            border-radius: 8px; /* Slightly rounded corners */
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); /* Soft shadow */
            text-align: center;
            font-size: 1px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <nav>
        <div id="nav-left">
            <i id="back-arrow" class="fa-solid fa-arrow-left"></i>
            <form id="search-form" action="SearchedPage.php" method="get">
                <input type="text" id="search-input" name="query" placeholder="Search...">
            </form>
        </div>
        
        <div id="button-Search">
            <button>All</button>
            <button>User</button>
            <button>Post</button>
            <button>Video</button>
        </div>
        <div id="straight"></div>
    </nav>
    
    <div id="main">
        <div id="media">
            <!-- <div class="user-wrapper">
                <div class="user-container-bg">
                    <i class="fa-solid fa-user-plus" title="Add Friend"></i>
                    <i class="fa-solid fa-eye" title="View Profile"></i>
                </div>

                <div class="user-container">
                    <img src="https://via.placeholder.com/50" alt="Profile Picture" class="profile-pic">
                    <span class="user-name">John Doe</span>
                </div>
            </div>    -->
        </div>
    </div>
    <script>
        document.getElementById("back-arrow").addEventListener("click", function() {
            window.location.href = "Home.php"; // Redirects to Home.php when clicked
        });

        document.querySelectorAll("#button-Search button").forEach(button => {
            button.addEventListener("click", function() {
                // Remove 'active' class from all buttons
                document.querySelectorAll("#button-Search button").forEach(btn => btn.classList.remove("active"));
                
                // Add 'active' class to the clicked button
                this.classList.add("active");
            });
         });

    </script>
    <script src="../javascript/fetch_posts.js"></script>
</body>
</html>