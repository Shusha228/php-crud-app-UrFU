<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Стена сообщений</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
            width: 100%;
            max-width: 600px;
            margin: 20px 0;
        }

        div {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 100%;
            max-width: 600px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        div p strong {
            color: #333;
        }

        div p small {
            color: #888;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .modal {
            display: flex;
            position: fixed;
            z-index: 1000;
            left: 28.85%;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            align-items: center;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 1s ease;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            font-size: 16px;
            color: #333;
        }

        textarea {
            width: 96%;
            height: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
            font-size: 14px;
            color: #333;
            overflow: hidden;
        }

        textarea:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .modal button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .modal button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Стена сообщений</h1>
<p>Привет, <?php echo htmlspecialchars($userName); ?>! <a href="/logout">Выйти</a></p>
<p><?php echo htmlspecialchars($extraInfo); ?></p>
<button onclick="openModal('messageModal')">Написать сообщение</button>
<hr>

<?php foreach($messages as $message) : ?>
    <div>
        <p><strong><?php echo htmlspecialchars($message->author); ?></strong>:</p>
        <p><?php echo nl2br(htmlspecialchars($message->content)); ?></p>
        <p><small>Опубликовано: <?php echo $message->created_at; ?></small></p>
		<?php if(($message->user_id === $userId) and ((time() - strtotime($message->created_at)) < 5)) : ?>
            <a href="javascript:void(0);" onclick="openEditModal(<?php echo $message->id; ?>, '<?php echo htmlspecialchars($message->content, ENT_QUOTES); ?>')">Редактировать</a> |
            <a href="/message/delete?id=<?php echo $message->id; ?>" onclick="return confirm('Удалить это сообщение?');">Удалить</a>
		<?php endif; ?>
        <hr>
    </div>
<?php endforeach; ?>

<div id="messageModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="closeModal('messageModal')">&times;</span>
        <h2>Написать сообщение</h2>
        <form action="/message/create" method="POST">
            <label for="content">Сообщение:</label>
            <textarea id="content" name="content" oninput="autoResize(this)" required></textarea>
            <button type="submit">Опубликовать</button>
        </form>
    </div>
</div>

<div id="editMessageModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editMessageModal')">&times;</span>
        <h2>Редактировать сообщение</h2>
        <form id="editForm" action="/message/edit" method="POST">
            <input type="hidden" id="editMessageId" name="id">
            <label for="editContent">Сообщение:</label>
            <textarea id="editContent" name="content" oninput="autoResize(this)" required></textarea>
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = "flex";

        const textarea = modal.querySelector("textarea");
        textarea.style.height = 'auto';
        textarea.value = "";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    function openEditModal(messageId, currentContent) {
        const modal = document.getElementById("editMessageModal");
        document.getElementById("editMessageId").value = messageId;
        document.getElementById("editContent").value = currentContent;

        modal.style.display = "flex";
        const textarea = modal.querySelector("textarea");
        textarea.style.height = 'auto';
        textarea.value = currentContent;
        autoResize(textarea);
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    /*window.onload = function() {
        closeModal('messageModal');
        closeModal('editMessageModal');
    };*/


    window.onclick = function (event) {
        let modals = document.getElementsByClassName("modal");
        for (let i = 0; i < modals.length; i++) {
            if (event.target === modals[i]) {
                closeModal(modals[i].id);
            }
        }
    }
</script>
</body>
</html>
