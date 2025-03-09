async function fetchPosts() {
    try {
        const searchInput = document.getElementById('search-input');
        const query = searchInput.value.trim(); // Get the search query

        if (query === "") {
            alert("Please enter a search term");
            return;
        }

        const response = await fetch(`http://localhost:3000/fetchBySearch.php?query=${encodeURIComponent(query)}`);
        const result = await response.json();
        
        // Debugging: Check what the response contains
        console.log(result); // You can remove this later after debugging

        const postsContainer = document.getElementById("media");
        postsContainer.innerHTML = ""; // Clear previous results

        if (result.message) {
            // Display message if no results
            postsContainer.innerHTML = `<p>${result.message}</p>`;
            return;
        }

        // Process posts if available
        result.posts.forEach(post => {
            const postElement = document.createElement("div");
            postElement.className = "posts";
            postElement.innerHTML = `
                <div class="post-header">
                    <img src="http://localhost:3000/${post.profile_picture}" alt="${post.user_name}" class="profile-img">
                    <div class="post-user">
                        <strong>${post.user_name}</strong>
                        <span>${getRelativeTime(post.created_at)}</span>
                    </div>
                </div>
                <div class="post-content">${post.post_text}</div>
                <div class="post-images">
                    ${post.image_urls.map(img => `<img src="http://localhost:3000/uploads/${img}" alt="Post Image">`).join('')}
                </div>
                <div class="post-reactions">
                    <p>${post.like_count} Likes</p>
                </div>
            `;
            postsContainer.appendChild(postElement);
        });
    } catch (error) {
        console.error("Error fetching posts:", error);
    }
}

// Convert Timestamp to "X minutes/hours ago"
function getRelativeTime(dateString) {
    const postDate = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - postDate) / 1000);

    if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    return `${Math.floor(diffInSeconds / 3600)} hours ago`;
}

// Fetch posts when the page loads
document.addEventListener("DOMContentLoaded", fetchPosts);

// Trigger fetch on form submit
const searchForm = document.getElementById('search-form');
searchForm.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent the default form submit
    fetchPosts(); // Trigger the fetch function to get posts
});

// Fetch posts when user types in the search input field
const searchInput = document.getElementById('search-input');
searchInput.addEventListener('input', fetchPosts);
