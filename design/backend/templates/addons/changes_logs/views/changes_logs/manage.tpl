{capture name="mainbox"}

<div id="order_logs">
    {if $logs}
        {assign var="order_statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses:true} 
        <table width="100%" class="table table-middle table--relative">
        <thead>
        <tr>
            <th width="5%" class="center">{__("order_id")}</th>
            <th width="50%" class="left">{__("old_status")}</th>
            <th width="50%" class="left">{__("new")}</th>
            <th width="15%">{__("user")}</th>
            <th width="15%" class="center">{__("date")}</th>
        </tr>
        </thead>
        {foreach from=$logs item=log}
        {math equation="x+1" x=$log_id|default:0 assign="log_id"}
            <tr>
                <td class="center">#{$log.order_id}</td>                
                <td class="left">{$log.old}</td>
                 <td class="left">{$log.new}</td>
                 <td>{if $log.user_id}
                    <a href="{"profiles.update&user_id=`$log.user_id`"|fn_url}" class="strong">{$log.firstname}&nbsp;{$log.lastname}</a>
                {else}
                    {if $log.action == "changes_logs_order_created"}
                        {__('guest')}
                    {else}
                        {__('system')}
                    {/if}
                {/if}</td>
                <td class="center">
                    {$log.timestamp|date_format:"`$settings.Appearance.date_format`"},&nbsp;{$log.timestamp|date_format:"`$settings.Appearance.time_format`"}
                </td>
            </tr>
        {/foreach}
        </table>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}

    </table>
<!--order_logs--></div>



{/capture}

{hook name="changes_logs:manage_mainbox_params"}
    {$page_title = __("all_orders")}
    {$select_languages = true}
{/hook}

{include file="common/mainbox.tpl" title=$page_title content=$smarty.capture.mainbox adv_buttons=$smarty.capture.adv_buttons select_languages=$select_languages sidebar=$smarty.capture.sidebar}
