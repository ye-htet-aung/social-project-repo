var addbutton = document.getElementById("addbutton");
var postbutton = document.getElementById("postbutton");
var cancelbutton = document.getElementById("cancelbutton");
var postcontenttext = document.getElementById("postcontenttext");
var addPhotobutton = document.getElementById("addPhoto");
var postform = document.getElementById("postform");

var addposttab = document.getElementById("addpost");

let imgcount = 0;
let imgFiles = []; // Store actual File objects

addbutton.addEventListener('click', () => {
    addposttab.style.display = "flex";
});

cancelbutton.addEventListener('click', () => {
    addposttab.style.display = "none";
});

// Handle Post Submission
postbutton.addEventListener('click', async () => {
    const formData = new FormData();
    formData.append("post_text", postcontenttext.value);

    // Append actual image files
    imgFiles.forEach((file, index) => {
        formData.append("photos[]", file, `image${index}.png`);
    });

    try {
        const response = await fetch('http://localhost:3000/upload.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("Post and images uploaded successfully!");
    } catch (error) {
        console.error("Error uploading post:", error.message);
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
        
        const postsContainer = document.getElementById("media");
        // postsContainer.innerHTML = ""; // Clear previous posts

        posts.forEach(post => {
            const postElement = document.createElement("div");
            postElement.id = "post";

            // Uploader section
            const uploader = document.createElement("div");
            uploader.id = "uploader";

            const profileDiv = document.createElement("div");
            profileDiv.id = "profile";

            const profileInfo = document.createElement("div");
            profileInfo.id = "profile-info";

            const profileName = document.createElement("a");
            profileName.id = "profilename";
            profileName.href = "#";
            profileName.textContent = "Tun Aung Lin"; // Replace with dynamic user name if available

            const postTime = document.createElement("p");
            postTime.textContent = "Just now"; // Replace with `post.created_at`

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
                    img.src = "http://localhost:3000/"+ imageUrl;
                    img.alt =   imageUrl;
                    img.style.maxWidth = "100%";
                    if(post.images.length>1){
                    img.style.maxWidth = "50%";
                    postImgDiv.style.display="flex";
                    }
                    postImgDiv.appendChild(img);
                });
            }

            // Post react section
            const postReactDiv = document.createElement("div");
            postReactDiv.id = "postreact";

            const reactsDiv = document.createElement("div");
            reactsDiv.id = "reacts";
            reactsDiv.innerHTML = `<p>kyaw and others</p><p>7 comments</p>`;

            const reactButtonsDiv = document.createElement("div");
            reactButtonsDiv.id = "reactbuttons";

            const buttons = [
                { icon: "fa-heart", text: "Like" },
                { icon: "fa-comment", text: "Comment" },
                { icon: "fa-message", text: "Send" },
                { icon: "fa-share", text: "Share" }
            ];

            buttons.forEach(btn => {
                const buttonDiv = document.createElement("div");
                buttonDiv.id = "button";

                const icon = document.createElement("i");
                icon.className = `fa-regular ${btn.icon}`;
                icon.style.color = "#005eff";

                const text = document.createElement("p");
                text.textContent = btn.text;

                buttonDiv.appendChild(icon);
                buttonDiv.appendChild(text);
                reactButtonsDiv.appendChild(buttonDiv);
            });

            postReactDiv.appendChild(reactsDiv);
            postReactDiv.appendChild(reactButtonsDiv);

            // Append everything to the main post element
            postElement.appendChild(uploader);
            postElement.appendChild(postTextDiv);
            postElement.appendChild(postImgDiv);
            postElement.appendChild(postReactDiv);

            postsContainer.appendChild(postElement);
        });
    } catch (error) {
        console.error("Error fetching posts:", error);
    }
}

// Fetch posts when the page loads
document.addEventListener("DOMContentLoaded", fetchPosts);