<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/layout.css">
    <style>
        /* Center settings container */
        #settings-container {
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }

        /* Language Selector */
        #language-select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Dark mode for select */
        body.dark-mode #language-select {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
        }
    </style>
</head>
<body>

<!-- Settings Container -->
<div id="settings-container">
    <!-- Language Selector -->
    <label for="language-select">Change Language:</label>
    <select id="language-select">
        <option value="en">English</option>
        <option value="my">မြန်မာ</option>
        <option value="fr">Français</option>
        <option value="ja">日本語</option>
    </select>
</div>

<script src="../javascript/languages.js"></script>

</body>
</html>