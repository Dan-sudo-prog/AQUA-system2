<!-- User Message Form -->
<h2>Send Message</h2>
<form method="post" action="send_message.php">
    <input type="text" name="sender_name" placeholder="Your name" required>
    <textarea name="message_text" placeholder="Type your message" required></textarea><br>
    <button type="submit">Send</button>
</form>

<!-- Received Messages -->
<h2>Received Messages</h2>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Message</th>
        <th>Reply</th>
    </tr>
   <?php
   include '../components/connect.php';
    // Fetch and display messages for admins
    $selectQuery = "SELECT name, message_text
                    FROM messages;";
    $result = $conn->query($selectQuery);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)):
    ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['message_text']; ?></td>
            <td>
                <form method="post" action="reply_message.php">
                    <textarea name="admin_reply" placeholder="Type your reply" required></textarea><br>
                    <button type="submit">Reply</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
