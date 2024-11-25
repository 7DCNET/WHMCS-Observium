

### Remarks:

**Dropdown Menu:** Daily, weekly and monthly options are offered to the user. When these options are changed, the form is automatically resubmitted.

**Graphic HTML:** The dynamically created chart according to the selected period is displayed on the page.

**{$trafficGraphHtml}**: Graph data created by Hook and transferred to the tpl file.

### Summary

**Hook Function:** By obtaining the port information connected to the customer's service, it creates the daily, weekly or monthly chart URL according to the user's selection.

**ClientArea:** The chart is displayed in the client panel according to the selected period. You can switch between graphics using the dropdown selection area.
With this method, you can enable users to view the traffic graph according to certain periods in the WHMCS customer panel.

To display graphs for specific periods (daily, weekly, monthly), you can display the desired graph by selecting it in the customer area in WHMCS. In this example, we will provide the user with daily, weekly, and monthly chart options and create a different chart URL for each option.

**1. Generating Chart URLs Dynamically**
We can dynamically generate different URLs for daily, weekly and monthly charts. These URLs will change according to the period selected by the user.

**2. Creating a Selection Area in the Customer Panel**
We can add a dropdown field for the customer to choose a daily, weekly or monthly traffic graph.

**Step 1: Hook Function**
First, let's add the hook function to the customer panel. This function will generate the chart URL based on the client selection.

**Step 2: Showing Period Selection and Graph in tpl File**

In the clientareaproductdetails.tpl file, we can add a period selection field to the user and show the selected chart.

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

Please add a custom field named PORT to the server or hosting service as seen in the first picture.

## Screenshot

![Screenshot](https://i.ibb.co/GkGx2vx/Screenshot-4.jpg)

![Screenshot](https://i.ibb.co/TWKtDgk/Screenshot-3.jpg)

![Screenshot](https://i.ibb.co/5FW9rTj/Screenshot-1.jpg)
