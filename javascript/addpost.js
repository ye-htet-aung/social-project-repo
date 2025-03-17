var addbutton = document.getElementById("addbutton");
var addstory = document.getElementById("addstory");

var postbutton = document.getElementById("postbutton");
var poststorybutton = document.getElementById("poststorybutton");

var cancelbutton = document.getElementById("cancelbutton");

var postcontenttext = document.getElementById("postcontenttext");

var addPhotobutton = document.getElementById("addPhoto");
var addVideobutton = document.getElementById("addVideo");

var postform = document.getElementById("postform");

var addposttab = document.getElementById("addpost");

let videoFiles = []; // Store actual video files

// Handle Video Selection
addVideobutton.addEventListener('click', (e) => {
    e.preventDefault();
    let videoInput = document.getElementById("dynamicVideoInput");
    if (!videoInput) {
        videoInput = document.createElement('input');
        videoInput.type = 'file';
        videoInput.name = 'video';
        videoInput.id = "dynamicVideoInput";
        videoInput.accept = "video/*";
        videoInput.style.display = "none";
        postform.appendChild(videoInput);
        videoInput.addEventListener('change', (event) => {
            handleVideoSelect(event, postform);
        });
        
    }

    videoInput.click();
});

// Process Selected Video
function handleVideoSelect(event,divtoappend) {
    const file = event.target.files[0];
    if (file) {
        videoFiles.push(file);

        const reader = new FileReader();
        reader.onload = function (e) {
            const video = document.createElement('video');
            video.src = e.target.result;
            // video.style.maxWidth = "100px";
            video.style.margin = "5px";
            video.style.borderRadius = "5px";
            video.controls = true;
            divtoappend.appendChild(video);
        };
        reader.readAsDataURL(file);
    }
}

let imgcount = 0;
let imgFiles = []; // Store actual File objects

addbutton.addEventListener('click', () => {
    addposttab.style.display = "flex";
});

cancelbutton.addEventListener('click', () => {
    addposttab.style.display = "none";
});

addstory.addEventListener('click', () => {
    document.getElementById("poststory").addEventListener('change', (event) => {
        handleVideoSelect(event, document.getElementById("addstory"));
    });
    document.getElementById("poststorybutton").style.display="block";
    document.getElementById("proimg").style.display="none";
    document.getElementById("storyde").style.display="none";
    document.getElementById("poststory").click();
});



// Handle Post Submission
postbutton.addEventListener('click', async () => {
    const formData = new FormData();
    formData.append("action", "create_post");
    formData.append("post_text", postcontenttext.value);

    // Append actual image files
    imgFiles.forEach((file, index) => {
        formData.append("photos[]", file, `image${index}.png`);
    });
    // Append video files
    videoFiles.forEach((file, index) => {
        formData.append("videos[]", file, `video${index}.mp4`);
    });

    try {
        const response = await fetch('http://localhost:3000/upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        // alert("Post and images uploaded successfully!");
        fetchPosts();
    } catch (error) {
        console.error("Error uploading post:", error.message);
    }
});
// Handle Story Submission
poststorybutton.addEventListener('click', async () => {
    const formData = new FormData();
    formData.append("action", "create_post");

    // Append video files
    videoFiles.forEach((file, index) => {
        formData.append("videos[]", file, `video${index}.mp4`);
    });
    // Append actual image files
    imgFiles.forEach((file, index) => {
        formData.append("photos[]", file, `image${index}.png`);
    });

    try {
        // Send the form data to the server
        const response = await fetch('http://localhost:3000/uploadstory.php', {
            method: 'POST',
            body: formData,
        });

        // Read the response as text first
        const textResponse = await response.text();

        try {
            // Attempt to parse the response as JSON
            const result = JSON.parse(textResponse);

            // Check the result from the server
            if (result.success) {
                console.log(result);
                fetchStories();
                alert("Story uploaded successfully!");

            } else {
                console.error("Server returned an error:", result.error);
                alert("Error: " + result.error);
            }
        } catch (jsonError) {
            // If JSON parsing fails, log and show the raw response
            console.error("Error: Response is not valid JSON", textResponse);
            alert("Error: Something went wrong, please try again later.");
        }

    } catch (error) {
        // Catch any network or fetch errors
        console.error("Error uploading story:", error.message);
        alert("Error uploading story: " + error.message);
    }
});
// Handle Image Selection
addPhotobutton.addEventListener('click', (e) => {
    e.preventDefault();

    if (imgcount >= 3) {
        alert("You can only upload up to 3 images.");
        return;
    }

    let photoInput = document.getElementById("dynamicPhotoInput");
    
    if (!photoInput) {
        photoInput = document.createElement('input');
        photoInput.type = 'file';
        photoInput.name = 'photo';
        photoInput.id = "dynamicPhotoInput";
        photoInput.accept = "image/*";
        photoInput.style.display = "none";
        postform.appendChild(photoInput);
        photoInput.addEventListener('change', handleFileSelect);
    }
    
    photoInput.click();
});

// Process Selected File
function handleFileSelect(event) {
    if (imgcount >= 3) {
        alert("You can only upload up to 3 images.");
        return;
    }

    const file = event.target.files[0];

    if (file) {
        imgFiles.push(file);
        imgcount++;

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = "100px";
            img.style.margin = "5px";
            img.style.borderRadius = "5px";
            postform.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
}
async function fetchPosts() {
    try {
        const response = await fetch('http://localhost:3000/fetch_posts.php');
        const posts = await response.json();
        
        const postsContainer = document.getElementById("post-container");
        postsContainer.innerHTML="";
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
            reactButtonsDiv.appendChild(createButton("fa-regular fa-message", "Message", () => sendMessage(post.user_id,post.user_name)));
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
async function fetchStories() {
    try {
        const response = await fetch('http://localhost:3000/fetch_stories.php');
        const text = await response.text(); // Read response as text first

        try {
            const stories = JSON.parse(text); // Attempt to parse JSON
            console.log("API Response:", stories);

            if (!Array.isArray(stories)) {
                console.error("Expected an array but got:", stories);
                return;
            }

            const storiesContainer = document.getElementById("stories-video-div");
            // storiesContainer.innerHTML = ""; 

            stories.forEach(story => {
                const storyElement = document.createElement("div");
                storyElement.classList.add("story");

                if (story.video_url) {
                    const video = document.createElement("video");
                    video.src = "http://localhost:3000/" + story.video_url;
                    video.style.width = "100%";
                    video.controls = false;
                    video.autoplay = true;
                    video.loop = true;
                    video.muted = true;
                    storyElement.appendChild(video);
                } else if (story.image_url) {
                    const img = document.createElement("img");
                    img.src = "http://localhost:3000/" + story.image_url;
                    img.alt = "Story Image";
                    storyElement.appendChild(img);
                }

                const storyprofile = document.createElement("div");
                const proimg=document.createElement("img");
                proimg.src = "http://localhost:3000/"+ story.profile_pic;
                storyprofile.id="story-profile";
                storyprofile.appendChild(proimg);
                storyElement.appendChild(storyprofile);

                const userNameOverlay = document.createElement("p");
                userNameOverlay.id="storyusername";
                userNameOverlay.textContent = story.user_name;
                storyElement.appendChild(userNameOverlay);


                storiesContainer.appendChild(storyElement);
            });

        } catch (jsonError) {
            console.error("Response is not valid JSON:", text);
        }

    } catch (error) {
        console.error("Error fetching stories:", error);
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
function sendMessage(userid,username) {
    window.location.href = `http://localhost:3000/messenger/chatUI.php?receiver_id=${userid}&receiver_name=${username}`;

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
    fetchStories();
});
