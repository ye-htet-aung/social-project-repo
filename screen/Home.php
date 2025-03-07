<?php
    include 'mainlayout.php';
?>
<link rel="stylesheet" href="/css/home.css">
<link rel="stylesheet" href="/css/addpost.css">
<link rel="stylesheet" href="../css/home.css">
<div id="main">
        <div id="media">
            <div id="addnewpost">
                <div id="profile"></div>
                <button id="addbutton">Add a Post</button>
                    <a href="">
                        <i class="fa-solid fa-image fa-xl" style="color: #005eff;"></i>
                    </a>
            </div>
        <div id="addpost">
            <div id="nav">
                <h2><i class="fa-solid fa-xmark" id="cancelbutton"></i> Create post</h2>
                <button>POST</button>
            </div>
            <form action="" id="postform">
                <input type="text" id="postcontenttext" placeholder="What's on your mind?">
            </form>
                <div id="background">
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                        <div class="bg"></div>
                </div>
                <div id="addpostnode">
                    <button>Photo/video</button>
                </div>
        </div>
            <div id="stories">
                <div id="stories-video-div">
                    <div id="video">
                        <!-- <iframe src="https://assets.pinterest.com/ext/embed.html?id=80150068365267657" frameborder="0" scrolling="no"></iframe> -->
                        <div id="profile">
                        </div>
                        <p id="profilename">
                            Add to Story
                        </p>
                    </div>
                </div>
            </div>

            <div id="post">
                <div id="uploader">
                    <div id="profile"></div>
                    <div id="profile-info">
                    <a id="profilename" href="">Tun Aung Lin</a>
                    <p>6 mins</p>
                    </div>
                    <a href="">
                    <i class="fa-solid fa-ellipsis" style="color: #005eff;"></i>
                    </a>
                </div>
                <div id="posttext">
                    <p>Not having fun at all</p>
                </div>
                <div id="postimg">
                    <img src="../src/image/2150844459.jpg" alt="">
                </div>
                <div id="postreact">
                    <div id="reacts">
                        <p>kyaw and others</p>
                        <p>7 comments</p>
                    </div>
                    <div id="reactbuttons">
                        <div id="button">
                        <i class="fa-regular fa-heart" style="color: #005eff;"></i>
                        <p>Like</p>
                        </div>
                        <div id="button">
                        <i class="fa-regular fa-comment" style="color: #005eff;"></i>
                        <p>Comment</p>
                        </div>
                        <div id="button">
                        <i class="fa-regular fa-message" style="color: #005eff;"></i>
                        <p>Send</p>
                        </div>
                        <div id="button">
                        <i class="fa-regular fa-share" style="color: #005eff;"></i>
                        <p>Share</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="friendsection">
                <div id="sub-headings"><i class="fa-solid fa-user-group"></i> <h3>Tun,friend for you</h3></div>
                <div id="friendsuggestion">
                    <div id="friend">
                        <div id="imgdiv">
                            <img src="../src/image/2150844459.jpg" alt="">
                        </div>
                        <div id="friend-info">
                            <h2>Tun Aung Lin</h2>
                            <p>2 mutual friends</p>
                            <div id="button">
                                <button>Add Friend</button>
                                <button>Join</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script src="../javascript/addpost.js"></script>