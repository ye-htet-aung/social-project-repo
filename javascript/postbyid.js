async function fetchPosts() {
    try {
        const response = await fetch('http://localhost:3000/fetch_post_byid.php');
        const posts = await response.json();
        
        const postsContainer = document.getElementById("media");

        posts.forEach(post => {
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
            reactsDiv.innerHTML = `<p>${post.like_count} Likes</p> <p>${post.comments.length} Comments</p>`;

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
            commentBox.classList.add("commentDIV");
            commentBox.style.display = "block";

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
        const formData = new FormData();
        formData.append("action", "like");
        formData.append("post_id", postId);

        const response = await fetch('http://localhost:3000/upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            // Toggle like button icon
            const icon = likeButton.querySelector("i");
            if (icon.classList.contains("fa-regular")) {
                icon.classList.remove("fa-regular", "fa-heart");
                icon.classList.add("fa-solid", "fa-heart");
            } else {
                icon.classList.remove("fa-solid", "fa-heart");
                icon.classList.add("fa-regular", "fa-heart");
            }

            // Update like count
            reactsDiv.innerHTML = `<p>${result.like_count} Likes</p> <p>${result.comment_count} Comments</p>`;
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error("Error liking post:", error);
    }
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

// Fetch posts and story when the page loads
document.addEventListener("DOMContentLoaded", () => {
    fetchPosts();
});
