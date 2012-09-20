<center>
    <form name="myForm" action="" method="POST">
        <label>Ваше cообщение</label>
        <input type="text" name='text'><br/>
        <button type="submit">Создать запись</button>
    </form> </center>
{foreach item=entry from=$news}
    {if ($c % 2)}
    #{$col = "bgcolor='#f9f9f9'"}
        {else}
    #{$col = "bgcolor='#f0f0f0'"}
    {/if}
<table border="0" cellspacing="5" cellpadding="0" width="200%" {$col} style="margin: 20px 0px;">
    <tr>
        <td width="195" style="color: #999999;">
            <center>{$entry.id} :
        </td>
        <td>{$entry.text}</td>  </center>
    </tr>
    {$c++}
</table>
{/foreach}

{if ($c == 0)}
<p>Новостей нет!!<br></p>
{/if}
