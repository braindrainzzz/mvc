<h1>User</h1>

<form method="Post" action="">
    <label>Login</label><input type="text" name="login" /><br />
    <label>Password</label><input type="text" name="password" /><br />
    <label>Role</label>

    <select name="role">
        <option value="default">Default</option>
        <option value="admin">Admin</option>
    </select>
    <br />
    <input type="submit" />
</form>

<table>
    {foreach item=user from=$users}
        <tr><td>{$user['id']}</td>
            <td>{$user['login']}</td>
            <td>{$user['role']}</td></tr>
    {/foreach}
</table>