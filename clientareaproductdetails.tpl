// ADD PLEASE THIS CODE WHERE SHOW BELOW

<ul class="list-info list-info-50 list-info-bordered">
<div class="traffic-graph-period">
    <form method="get" action="">
        <li><span class="list-info-title">Select Traffic Period Information:</span></li>
        <select name="period" id="period" onchange="this.form.submit()">
            <option value="daily" {if $selectedPeriod == 'daily'}selected{/if}>Daily</option>
            <option value="weekly" {if $selectedPeriod == 'weekly'}selected{/if}>Weekly</option>
            <option value="monthly" {if $selectedPeriod == 'monthly'}selected{/if}>Monthly</option>
        </select>
        <!-- Mevcut URL parametrelerini korumak için -->
        {foreach from=$smarty.get key=key item=value}
            {if $key neq "period"}
                <input type="hidden" name="{$key}" value="{$value}">
            {/if}
        {/foreach}
    </form>
</div>

<div class="traffic-graph">
   <li> {$trafficGraphHtml}</li>
</div>
</ul>
