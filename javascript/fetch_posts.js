// result.posts.forEach(post => {
//     const postElement = document.createElement("div");
//     postElement.id = "post";
//     postElement.dataset.postId = post.id; // Store post ID

//     // Uploader section (same as before)
//     const uploader = document.createElement("div");
//     uploader.id = "uploader";

//     const profileDiv = document.createElement("div");
//     profileDiv.id = "profile";

//     const profileImg = document.createElement("img");
//     profileImg.src = `http://localhost:3000/${post.profile_image}`;
//     profileImg.alt = post.user_name;

//     profileDiv.appendChild(profileImg);

//     const profileInfo = document.createElement("div");
//     profileInfo.id = "profile-info";

//     const profileName = document.createElement("a");
//     profileName.id = "profilename";
//     profileName.href = "#";
//     profileName.textContent = post.user_name;

//     const postTime = document.createElement("p");
//     postTime.textContent = getRelativeTime(post.created_at);

//     profileInfo.appendChild(profileName);
//     profileInfo.appendChild(postTime);

//     const optionsLink = document.createElement("a");
//     optionsLink.href = "#";
//     optionsLink.innerHTML = `<i class="fa-solid fa-ellipsis" style="color: #005eff;"></i>`;

//     uploader.appendChild(profileDiv);
//     uploader.appendChild(profileInfo);
//     uploader.appendChild(optionsLink);

//     // Post text section (same as before)
//     const postTextDiv = document.createElement("div");
//     postTextDiv.id = "posttext";

//     const postText = document.createElement("p");
//     postText.textContent = post.post_text;

//     postTextDiv.appendChild(postText);

//     // Post image section
//     const postImgDiv = document.createElement("div");
//     postImgDiv.id = "postimg";

//     // Ensure images is an array
//     const images = Array.isArray(post.images) ? post.images : [];
//     images.forEach(imageUrl => {
//         const img = document.createElement("img");
//         img.src = `http://localhost:3000/uploads/${imageUrl}`;
//         img.alt = imageUrl;

//         img.style.width = images.length === 1 ? "100%" : (images.length === 2 ? "50%" : "33.33%");
//         img.style.objectFit = "cover";
//         img.style.cursor = "pointer";

//         img.addEventListener("click", () => {
//             img.style.width = img.style.width === "100%" ? (images.length === 1 ? "100%" : "50%") : "100%";
//         });

//         postImgDiv.appendChild(img);
//     });

//     // Post video section
//     const videos = Array.isArray(post.videos) ? post.videos : [];
//     videos.forEach(videoUrl => {
//         const video = document.createElement('video');
//         video.src = `http://localhost:3000/uploads/${videoUrl}`;
//         video.controls = true;
//         video.style.width = "100%";  // Adjust the size as needed
//         postImgDiv.appendChild(video);
//     });

//     // Post react section (same as before)
//     const postReactDiv = document.createElement("div");
//     postReactDiv.id = "postreact";

//     const reactsDiv = document.createElement("div");
//     reactsDiv.id = "reacts";
//     reactsDiv.innerHTML = `<p>${post.like_count} Likes</p><p>${post.comments.length} Comments</p>`;

//     const reactButtonsDiv = document.createElement("div");
//     reactButtonsDiv.id = "reactbuttons";

//     // Like Button
//     reactButtonsDiv.appendChild(createButton("fa-heart", "Like", () => likePost(post.id, reactsDiv)));

//     // Comment Button
//     reactButtonsDiv.appendChild(createButton("fa-comment", "Comment", () => showCommentBox(post.id)));

//     // Message Button
//     reactButtonsDiv.appendChild(createButton("fa-message", "Message", () => sendMessage(post.id)));

//     // Share Button
//     reactButtonsDiv.appendChild(createButton("fa-share", "Share", () => sharePost(post.id)));

//     postReactDiv.appendChild(reactsDiv);
//     postReactDiv.appendChild(reactButtonsDiv);

//     // Display Comments for the Post
//     const commentsDiv = document.createElement("div");
//     commentsDiv.id = `comments-${post.id}`;

//     if (post.comments && post.comments.length > 0) {
//         post.comments.forEach(comment => {
//             const commentDiv = document.createElement("div");
//             commentDiv.className = "comment";

//             const commentUser = document.createElement("strong");
//             commentUser.textContent = comment.comment_user_name;

//             const commentText = document.createElement("p");
//             commentText.textContent = comment.comment_text;

//             const commentTime = document.createElement("span");
//             commentTime.textContent = getRelativeTime(comment.comment_time);

//             commentDiv.appendChild(commentUser);
//             commentDiv.appendChild(commentText);
//             commentDiv.appendChild(commentTime);

//             commentsDiv.appendChild(commentDiv);
//         });
//     } else {
//         commentsDiv.textContent = "No comments yet.";
//     }

//     // Comment Box (Initially Hidden)
//     const commentBox = document.createElement("div");
//     commentBox.id = `comment-box-${post.id}`;
//     commentBox.style.display = "none";

//     const commentInput = document.createElement("input");
//     commentInput.type = "text";
//     commentInput.placeholder = "Write a comment...";
//     commentInput.id = `comment-input-${post.id}`;

//     const commentSubmit = document.createElement("button");
//     commentSubmit.textContent = "Post";            
//     commentSubmit.onclick = () => postComment(post.id, commentsDiv);
//     commentBox.appendChild(commentsDiv);
//     commentBox.appendChild(commentInput);
//     commentBox.appendChild(commentSubmit);

//     postElement.appendChild(uploader);
//     postElement.appendChild(postTextDiv);
//     postElement.appendChild(postImgDiv);
//     postElement.appendChild(postReactDiv);
//     postElement.appendChild(commentBox);

//     postsContainer.appendChild(postElement);
// });


// // Function to create buttons like 'Like', 'Comment', 'Message', 'Share'
// function createButton(iconClass, text, onClick) {
//     const buttonDiv = document.createElement("div");
//     buttonDiv.id = "button";

//     const icon = document.createElement("i");
//     icon.className = `fa-regular ${iconClass}`;
//     icon.style.color = "#005eff";

//     const buttonText = document.createElement("p");
//     buttonText.textContent = text;

//     buttonDiv.appendChild(icon);
//     buttonDiv.appendChild(buttonText);
//     buttonDiv.addEventListener("click", onClick);

//     return buttonDiv;
// }
// function getRelativeTime(timestamp) {
//     const now = new Date();
//     const postTime = new Date(timestamp);
//     const diffInSeconds = Math.floor((now - postTime) / 1000);

//     const minutes = Math.floor(diffInSeconds / 60);
//     const hours = Math.floor(diffInSeconds / 3600);
//     const days = Math.floor(diffInSeconds / 86400);

//     if (minutes < 1) return "Just now";
//     if (minutes < 60) return `${minutes} minutes ago`;
//     if (hours < 24) return `${hours} hours ago`;
//     return `${days} days ago`;
// }

// // Trigger fetch when the user types in the search input field
// const searchInput = document.getElementById('search-input');
// searchInput.addEventListener('input', fetchPosts);
// Function to fetch users
async function fetchPosts() {
    try {
        const searchInput = document.getElementById('search-input');
        const query = searchInput ? searchInput.value.trim() : ""; // Get the search query

        // If there's a query, fetch posts based on search, otherwise fetch all posts
        let url = 'http://localhost:3000/fetch_posts.php'; // Default URL for fetching all posts
        if (query !== "") {
            url = `http://localhost:3000/fetchBySearch.php?query=${encodeURIComponent(query)}`; // URL for search-based fetching
        }

        const response = await fetch(url);
        const result = await response.json();
        
        const postsContainer = document.getElementById("media");
        postsContainer.innerHTML = ""; // Clear existing posts

        if (!result.posts || result.posts.length === 0 && !result.users || result.users.length === 0) {
            // If no posts are found
            postsContainer.innerHTML = `<p>No results found for '${query}'</p>`;
            return;
        }
        result.users.forEach(user => {
            // <div class="user-wrapper">
            //     <div class="user-container-bg">
            //         <i class="fa-solid fa-user-plus" title="Add Friend"></i>
            //         <i class="fa-solid fa-eye" title="View Profile"></i>
            //     </div>

            //     <div class="user-container">
            //         <img src="https://via.placeholder.com/50" alt="Profile Picture" class="profile-pic">
            //         <span class="user-name">John Doe</span>
            //     </div>
            // </div>  
            const userElement = document.createElement("div");
            userElement.classList.add("user-wrapper")
            userElement.dataset.userId = user.user_id; // Store user ID

            const profileDiv = document.createElement("div");
            profileDiv.classList.add("user-container-bg")
            profileDiv.innerHTML = `
                <i class="fa-solid fa-user-plus add-friend" data-user-id="${user.user_id}" title="Add Friend"></i>
                <i class="fa-solid fa-eye view-profile" title="View Profile"></i>
            `;

            // Add event listener for "Add Friend"
            profileDiv.querySelector(".add-friend").addEventListener("click", function () {
                addFriend(this.getAttribute("data-user-id"));
            });

            // Add event listener for "View Profile"
            profileDiv.querySelector(".view-profile").addEventListener("click", function () {
                window.location.href =  `http://localhost:3000/screen/profiledetail.php?user_id=`+user.user_id;// Assuming you have a viewProfile function
            });
            
            const profileDiv1 = document.createElement("div");
            profileDiv1.classList.add("user-container");

            const profileImg = document.createElement("img");
            profileImg.src = "http://localhost:3000/"+user.user_profile;
            profileImg.classList.add("profile-pic");
            profileImg.alt = user.user_name;

            profileDiv1.appendChild(profileImg);

            const profileInfo = document.createElement("span");
            profileInfo.classList.add("user-name");
            profileInfo.textContent=user.user_name;

            profileDiv1.appendChild(profileInfo);

            userElement.appendChild(profileDiv);
            userElement.appendChild(profileDiv1);

            postsContainer.appendChild(userElement);
        });

        result.posts.forEach(post => {
            const postElement = document.createElement("div");
            postElement.id = "post";
            postElement.dataset.postId = post.id; // Store post ID

            // Uploader section
            const uploader = document.createElement("div");
            uploader.id = "uploader";

            const profileDiv = document.createElement("div");
            profileDiv.id = "profile";

            const profileImg = document.createElement("img");
            profileImg.src = "http://localhost:3000/"+post.profile_image;
            profileImg.alt = post.user_name;

            profileDiv.appendChild(profileImg);

            const profileInfo = document.createElement("div");
            profileInfo.id = "profile-info";

            const profileName = document.createElement("a");
            profileName.id = "profilename";
            profileName.href = "http://localhost:3000/screen/profiledetail.php?user_id="+post.user_id;
            profileName.textContent = post.user_name;

            const postTime = document.createElement("p");
            postTime.textContent = getRelativeTime(post.created_at);

            profileInfo.appendChild(profileName);
            profileInfo.appendChild(postTime);

            const optionsLink = document.createElement("a");
            optionsLink.href = "#";
            optionsLink.innerHTML = `<i class="fa-solid fa-ellipsis" style="color: #005eff;"></i>`;

            uploader.appendChild(profileDiv);
            uploader.appendChild(profileInfo);
            uploader.appendChild(optionsLink);

            // Post text section
            const postTextDiv = document.createElement("div");
            postTextDiv.id = "posttext";

            const postText = document.createElement("p");
            postText.textContent = post.post_text;

            postTextDiv.appendChild(postText);

            // Post image section
            const postImgDiv = document.createElement("div");
            postImgDiv.id = "postimg";

            if (post.images.length > 0) {
                post.images.forEach(imageUrl => {
                    const img = document.createElement("img");
                    img.src = "http://localhost:3000/uploads/" + imageUrl;
                    img.alt = imageUrl;

                    img.style.width = post.images.length === 1 ? "100%" : (post.images.length === 2 ? "50%" : "33.33%");
                    img.style.objectFit = "cover";
                    img.style.cursor = "pointer";

                    img.addEventListener("click", () => {
                        img.style.width = img.style.width === "100%" ? (post.images.length === 1 ? "100%" : "50%") : "100%";
                    });

                    postImgDiv.appendChild(img);
                });
            }
            if (post.videos.length > 0) {
                post.videos.forEach(videoUrl => {
                    const video = document.createElement('video');
                    video.src = "http://localhost:3000/uploads/" + videoUrl;
                    video.controls = true;
                    video.style.width = "100%";  // Adjust the size as needed
                    postImgDiv.appendChild(video);
                });
            }
            // Post react section
            const postReactDiv = document.createElement("div");
            postReactDiv.id = "postreact";

            const reactsDiv = document.createElement("div");
            reactsDiv.id = "reacts";
            reactsDiv.innerHTML = `<p class='like-count'>${post.like_count} Likes</p> <p class='comment-count'>${post.comments.length} Comments</p>`;

            const reactButtonsDiv = document.createElement("div");
            reactButtonsDiv.id = "reactbuttons";

            const likeButton = createButton(
                post.is_liked ? "fa-solid fa-heart" : "fa-regular fa-heart",
                "Like",
                () => likePost(post.id, likeButton, reactsDiv)
            );
            reactButtonsDiv.appendChild(likeButton);

            // Other Buttons
            reactButtonsDiv.appendChild(createButton("fa-regular fa-comment", "Comment", () => showCommentBox(post.id)));
            reactButtonsDiv.appendChild(createButton("fa-regular fa-message", "Message", () => sendMessage(post.id)));
            reactButtonsDiv.appendChild(createButton("fa-regular fa-share", "Share", () => sharePost(post.id)));


            postReactDiv.appendChild(reactsDiv);
            postReactDiv.appendChild(reactButtonsDiv);
                        // Display Comments for the Post
                        const commentsDiv = document.createElement("div");
                        commentsDiv.id = `comments-${post.id}`;
            
                        if (post.comments.length > 0) {
                            post.comments.forEach(comment => {
                                const commentDiv = document.createElement("div");
                                commentDiv.className = "comment";
            
                                const commentUser = document.createElement("strong");
                                commentUser.textContent = comment.comment_user_name;
            
                                const commentText = document.createElement("p");
                                commentText.textContent = comment.comment_text;
            
                                const commentTime = document.createElement("span");
                                commentTime.textContent = getRelativeTime(comment.comment_time);
            
                                commentDiv.appendChild(commentUser);
                                commentDiv.appendChild(commentText);
                                commentDiv.appendChild(commentTime);
            
                                commentsDiv.appendChild(commentDiv);
                            });
                        } else {
                            commentsDiv.textContent = "No comments yet.";
                        }
            

            // Comment Box (Initially Hidden)
            const commentBox = document.createElement("div");
            commentBox.id = `comment-box-${post.id}`;
            commentBox.style.display = "none";

            const commentInput = document.createElement("input");
            commentInput.type = "text";
            commentInput.placeholder = "Write a comment...";
            commentInput.id = `comment-input-${post.id}`;

            const commentSubmit = document.createElement("button");
            commentSubmit.textContent = "Post";            
            commentSubmit.onclick = () => postComment(post.id, commentsDiv);
            commentBox.appendChild(commentsDiv);
            commentBox.appendChild(commentInput);
            commentBox.appendChild(commentSubmit);
            

            postElement.appendChild(uploader);
            postElement.appendChild(postTextDiv);
            postElement.appendChild(postImgDiv);
            postElement.appendChild(postReactDiv);
            postElement.appendChild(commentBox);

            postsContainer.appendChild(postElement);
        });
    } catch (error) {
        console.error("Error fetching posts:", error);
    }
}

// Create a Button (Like, Comment, Message, Share)
function createButton(iconClass, text, onClick) {
    const buttonDiv = document.createElement("div");
    buttonDiv.id = "button";

    const icon = document.createElement("i");
    icon.className = `fa-regular ${iconClass}`;
    icon.style.color = "#005eff";

    const buttonText = document.createElement("p");
    buttonText.textContent = text;

    buttonDiv.appendChild(icon);
    buttonDiv.appendChild(buttonText);
    buttonDiv.addEventListener("click", onClick);

    return buttonDiv;
}

// Function to Like a Post
async function likePost(postId, likeButton, reactsDiv) {
    try {
        // Optimistically update UI
        const icon = likeButton.querySelector("i");
        let likeCount = parseInt(reactsDiv.querySelector(".like-count").textContent) || 0;
        let commentCount = parseInt(reactsDiv.querySelector(".comment-count").textContent) || 0;
        let isLiked = icon.classList.contains("fa-solid");

        if (isLiked) {
            icon.classList.remove("fa-solid", "fa-heart");
            icon.classList.add("fa-regular", "fa-heart");
            likeCount--;
        } else {
            icon.classList.remove("fa-regular", "fa-heart");
            icon.classList.add("fa-solid", "fa-heart");
            likeCount++;
        }

        reactsDiv.innerHTML = `<p class='like-count'>${likeCount} Likes</p> <p class='comment-count'>${commentCount} Comments</p>`;

        // Send request to the server
        const formData = new FormData();
        formData.append("action", "like");
        formData.append("post_id", postId);

        const response = await fetch('http://localhost:3000/upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (!result.success) {
            console.error(result.error);
        }

    } catch (error) {
        console.error("Error liking post:", error);
    }
}


// Show Comment Box
function showCommentBox(postId) {
    const commentBox = document.getElementById(`comment-box-${postId}`);
    commentBox.classList.add("commentDIV");
    commentBox.style.display = commentBox.style.display === "none" ? "block" : "none";
}

// Function to Post a Comment
async function postComment(postId, reactsDiv) {
    const commentInput = document.getElementById(`comment-input-${postId}`);
    const commentText = commentInput.value.trim();

    if (commentText === "") {
        alert("Comment cannot be empty!");
        return;
    }

    try {
        const formData = new FormData();
        formData.append("action", "comment");
        formData.append("post_id", postId);
        formData.append("comment_text", commentText);

        const response = await fetch('http://localhost:3000/upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            fetchPosts();
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error("Error posting comment:", error);
    }
}

// Send Message (Placeholder Function)
function sendMessage(postId) {
    alert(`Message feature coming soon!`);
}

// Share Post
function sharePost(postId) {
    const shareUrl = `http://localhost:3000/post.php?id=${postId}`;
    navigator.clipboard.writeText(shareUrl).then(() => {
        alert("Post link copied! Share it with others.");
    }).catch(err => {
        console.error("Failed to copy link:", err);
    });
}
// Convert Timestamp to "X minutes/hours ago"
function getRelativeTime(dateString) {
    const postDate = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - postDate) / 1000);

    if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds <86400)return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds <2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    return `${dateString}`;
}
// // Trigger fetch when the user types in the search input field
const searchInput = document.getElementById('search-input');
searchInput.addEventListener('input', fetchPosts);


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
        location.reload();
    } catch (error) {
        alert.log(error.message)
        console.error("Error add friend request:", error.message);
    }
}

async function confirmFriend(requestId) {
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
        location.reload();
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
        location.reload();
    } catch (error) {
        console.error("Error conforming friend request:", error.message);
    }
}
function sendMessage(userid,username) {
    window.location.href = `http://localhost:3000/messenger/chatUI.php?receiver_id=${userid}&receiver_name=${username}`;
}