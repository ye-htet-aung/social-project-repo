async function fetchFriend() {
    try {
        const response = await fetch(`http://localhost:3000/fetch_friend.php`);
        const friends = await response.json();

        const friendContainer = document.getElementById("friends_show");
        friendContainer.innerHTML="";

        friends.forEach(friend => {
            const friendDiv = document.createElement('div');
            friendDiv.id = 'friend_div';
        
            // Create the div for friend's image
            const friendImgDiv = document.createElement('div');
            friendImgDiv.id = 'friend_img_div';
        
            const friendImg = document.createElement('img');
            friendImg.src = "http://localhost:3000/"+friend.profile_picture; // Set profile picture
            friendImg.alt = friend.name; // Set alt text to the friend's name
            friendImgDiv.appendChild(friendImg); // Append the image to the image div
        
            // Create the div for friend's data
            const friendData = document.createElement('div');
            friendData.id = 'friend_data';
        
            const friendName = document.createElement('p');
            friendName.id = 'friend_name';
            friendName.textContent = friend.name; // Set friend's name
        
            friendData.appendChild(friendName); // Append the name to the data div
        
            // Append the image div and data div to the friend div
            friendDiv.appendChild(friendImgDiv);
            friendDiv.appendChild(friendData);
            friendContainer.appendChild(friendDiv);
        });
    } catch (error) {
        console.error("Error liking post:", error);
    }
}

// Fetch posts and story when the page loads
document.addEventListener("DOMContentLoaded", () => {
    fetchFriend();
});
