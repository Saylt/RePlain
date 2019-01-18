{capture name="mainbox"}
<h4 class="subheader hand">
    Chat properties
</h4>
<div class="table-responsive-wrapper">
    <form class="replain-form" action="{""|fn_url}" method="post" name="manage_replain_form">
    {if $bot_url}
        <div>
            <p>{__("replain.improper_url", ["[bot_url]" => $bot_url]) nofilter}</p>
        </div>
        <p><textarea rows="6" cols="99" id="replain_raw_key" name="replain_settings[general][raw_key]"></textarea><p>
        {include file="addons/replain/buttons/new_chat.tpl" but_name="dispatch[replain.create]" but_meta ="" but_role="submit" but_text="replain.create_chat"|__ but_target_form="manage_replain_form" active=true}
    {else}
        <label class="replain-label" for="startMessage">{__("replain.langid")}:</label>
        <select name="replain_settings[general][langId]" class="replain-input" id="langId" value="" {if !$active}disabled{/if}>
            {foreach from=$available_languages item="s" key="k"}
                <option value="{$s}">{$k}</option>
            {/foreach}
        </select><br>
        <input style="display: none;" type="text" name="replain_settings[id]"  value="{$addons.replain.id}" />
        <table>
            <tr>
                <td>
                    {if $active}
                    {assign var="new_chat_enabled" value=true}
                    {/if}
                    {include file="addons/replain/buttons/new_chat.tpl" but_name="dispatch[replain.create]" but_meta="cm-new-window" but_role="submit" but_text="replain.new_chat"|__ but_target_form="manage_replain_form" active=$new_chat_enabled}
                </td>
                {if $addons.replain.id}
                    <td>
                        {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.{if $active}disable{else}enable{/if}]" but_text="replain.{if $active}disable{else}enable{/if}"|__ but_role="{if $active}disable{else}enable{/if}" but_target_form="manage_replain_form"}
                    </td>
                    <td>
                        {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.delete]" but_text="replain.delete"|__ but_role="delete" but_target_form="manage_replain_form"}
                    </td>
                {/if}
            </tr>
        </table>
    {/if}
    </form>
</div>
{/capture}

{include file="common/mainbox.tpl" title=__("replain") content=$smarty.capture.mainbox}