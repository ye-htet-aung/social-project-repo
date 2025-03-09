
    document.addEventListener("DOMContentLoaded", function () {
        const searchForm = document.getElementById("search-form");
        const userWrapper = document.querySelector("media");

        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const query = document.getElementById("search-input").value;

            // Make AJAX request to fetch search results
            fetch("searchedPage.php?query=" + query)
                .then(response => response.json())
                .then(data => {
                    userWrapper.innerHTML = ''; // Clear previous results

                    // Check if there is a "message" key (i.e., no results found)
                    if (data.message) {
                        userWrapper.innerHTML = `<p>${data.message}</p>`; // Display the message
                        return;
                    }

                    // Display users
                    data.users.forEach(user => {
                        const userDiv = document.createElement("div");
                        userDiv.classList.add("user-wrapper");

                        userDiv.innerHTML = `
                            <div class="user-container-bg">
                                <i class="fa-solid fa-user-plus" title="Add Friend"></i>
                                <i class="fa-solid fa-eye" title="View Profile"></i>
                            </div>
                            <div class="user-container">
                                <img src="${user.profile_picture || 'https://via.placeholder.com/50'}" alt="Profile Picture" class="profile-pic">
                                <span class="user-name">${user.user_name}</span>
                            </div>
                        `;

                        userWrapper.appendChild(userDiv);
                    });

                    // Display posts
                    data.posts.forEach(post => {
                        const postDiv = document.createElement("div");
                        postDiv.classList.add("post-wrapper");

                        let postImages = post.images.map(img => `<img src="${img}" alt="Image" class="post-image">`).join('');
                        let postVideos = post.videos.map(video => `<video controls><source src="${video}" type="video/mp4"></video>`).join('');

                        postDiv.innerHTML = `
                            <div class="post-container">
                                <div class="user-info">
                                    <img src="${post.profile_picture || 'https://via.placeholder.com/50'}" alt="Profile Picture" class="profile-pic">
                                    <span class="user-name">${post.user_name}</span>
                                </div>
                                <p>${post.post_text}</p>
                                ${postImages}
                                ${postVideos}
                                <p>Likes: ${post.like_count}</p>
                            </div>
                        `;

                        userWrapper.appendChild(postDiv);
                    });
                })
                .catch(err => console.error("Error fetching search results:", err));
        });
    });

