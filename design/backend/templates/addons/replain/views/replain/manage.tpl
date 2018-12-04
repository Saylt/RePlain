{capture name="mainbox"}
<h4 class="subheader hand">
    {"replain.chat_properties"|__:$bot_url}
</h4>
<div class="table-responsive-wrapper">
    <form class="replain-form" action="{""|fn_url}" method="post" name="manage_products_form">
    {if $bot_url}
        <div>
            <p>{"replain.improper_url"|__:$bot_url nofilter}</p>
        </div>
        <p><textarea rows="6" cols="99" id="replain_raw_key" name="replain_settings[general][raw_key]"></textarea><p>
        {include file="addons/replain/buttons/new_chat.tpl" but_name="dispatch[replain.create]" but_role="submit" but_text="replain.create_chat"|__ but_target_form="manage_products_form" disabled=$new_chat_disabled}
    {else}
        <label class="replain-label" for="startMessage">{__("replain.langid")}:</label>
        <select name="replain_settings[general][langId]" class="replain-input" id="langId" value="" {if $disabled}disabled{/if}>
            {foreach from=$available_languages item="s" key="k"}
                <option value="{$s}" {if (isset($addons.replain.langId) && $addons.replain.langId== $s)} selected="selected" {/if}>{$k}</option>
            {/foreach}
        </select><br>
        <input style="display: none;" type="text" name="replain_settings[id]"  value="{$addons.replain.id}" />
        <table>
            <tr>
                <td>
                    {if $disabled}
                        {assign var="new_chat_disabled" value=true}
                    {/if}
                    {include file="addons/replain/buttons/new_chat.tpl" but_name="dispatch[replain.create]" but_role="submit" but_text="replain.new_chat"|__ but_target_form="manage_products_form" disabled=$new_chat_disabled}
                </td>
                {if $addons.replain.id}
                    <td>
                        {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.{if $disabled}enable{else}disable{/if}]" but_text="replain.{if $disabled}enable{else}disable{/if}"|__ but_role="{if $disabled}enable{else}disable{/if}"}
                    </td>
                    <td>
                        {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.delete]" but_text="replain.delete"|__ but_role="delete"}
                    </td>
                {/if}
                {if $invite_link}
                    <td>
                        <div> <a class="replain-invite-link" id="hide" onclick="replain_call();" href="{$invite_link}">{"replain.click_to_activate"|__}</a></div>
                    </td>
                {/if}
            </tr>
        </table>
    {/if}
    </form>
</div>
{/capture}

<script type="text/javascript">
$(document).ready(function(){
    $("#hide").click(function(){
        $(".replain-invite-link").hide();
    });
});

var Replainkey = '{$addons.replain.secret_key}';
</script>

{script src="js/addons/replain/request.js"}

{include file="common/mainbox.tpl" title=__("replain") content=$smarty.capture.mainbox}