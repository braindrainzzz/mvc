<h2>User Update</h2>

<form method="Post" action="">
    <label>Login</label><input type="text" name="login" value="{$users['login']}"/><br/>
    <label>Password</label><input type="text" name="password"/><br/>
    <label>Role</label>
    <select name="role">
        <option value="default"{if $users['role'] = 'default'}selected{/if} >Default</option>
        <option value="admin"{if $users['role'] = 'admin'}selected{/if}>Admin</option>
        <option value="owner"{if $users['role'] = 'owner'}selected{/if} >Owner</option>
    </select>
    <br/><input type="submit"/>
</form>