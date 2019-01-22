{capture name="mainbox"}
<h4 class="subheader hand">
    {__("replain.manage_chat")}
</h4>
<div class="table-responsive-wrapper">
    <form class="replain-form" action="{""|fn_url}" method="post" name="manage_replain_form">
    {if $bot_url}
        <div>
            <p>{__("replain.insert_script", ["[bot_url]" => $bot_url]) nofilter}</p>
        </div>
        <p><textarea rows="6" cols="99" id="replain_script" name="replain_settings[general][script]" required></textarea><p>
        {include file="addons/replain/buttons/new_chat.tpl" but_name="dispatch[replain.create]" but_meta ="" but_role="submit" but_text="replain.create_chat"|__ but_target_form="manage_replain_form" active=false}
    {else}
        <input style="display: none;" type="text" name="replain_settings[script]"  value="{empty($addons.replain.script)}" />
        <table>
            <tr>
                {if $active}
                    {assign var="new_chat_enabled" value=true}
                {/if}
                <td>
                    {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.{if $active}disable{else}enable{/if}]" but_text="replain.{if $active}disable{else}enable{/if}"|__ but_role="{if $active}disable{else}enable{/if}" but_target_form="manage_replain_form"}
                </td>
                <td>
                    {include file="addons/replain/buttons/replain.tpl" but_name="dispatch[replain.delete]" but_text="replain.delete"|__ but_role="delete" but_target_form="manage_replain_form"}
                </td>
            </tr>
        </table>
    {/if}
    </form>
</div>
{/capture}

{include file="common/mainbox.tpl" title=__("replain") content=$smarty.capture.mainbox}