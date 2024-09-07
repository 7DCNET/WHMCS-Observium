<?php
 
use WHMCS\Database\Capsule;

add_hook('ClientAreaPageProductDetails', 1, function ($vars) {
    // Get Service ID

     $serviceId = $vars['serviceid'];
     $selectedPeriod = isset($_GET['period']) ? $_GET['period'] : 'daily'; // Varsayılan günlük
    
    // Get server port information
    $port = getServerPortDetails($serviceId);

    if ($port) {

        // Generate chart URL
        $graphUrl = generateGraphUrl($port, $selectedPeriod);
        
        // Generate graphical HTML output
        $graphHtml = displayTrafficGraph($graphUrl);
        
        // export data to tpl file
        return [
            'trafficGraphHtml' => $graphHtml,
            'selectedPeriod' => $selectedPeriod // Period selected by the user
        ];
    }

    return [
        'trafficGraphHtml' => "Port information not found."
    ];
  
});

// Function to get server port information
function getServerPortDetails($serviceId) {

    $service = Capsule::table('tblhosting')
        ->where('id', $serviceId)
        ->first();

    // If there is service information, let's find the custom field with packageid
    if ($service) {
        // Get ID of field 'PORT' from tblcustomfields table, matching packageid
        $customField = Capsule::table('tblcustomfields')
            ->where('fieldname', 'PORT')
            ->where('relid', $service->packageid) // Matching the service's packageid
            ->first();

        // If 'PORT' custom field is found, get the value from tblcustomfieldsvalues ​​table
        if ($customField) {
            $port = Capsule::table('tblcustomfieldsvalues')
                ->where('relid', $serviceId) // Customer service ID (relid)
                ->where('fieldid', $customField->id) // Custom field ID'si ile
                ->first();
            
            return $port ? $port->value : null; // Returning port value
        }
    }
    return null;
}

// Function to create chart URL
function generateGraphUrl($port, $period) {
    $baseUrl = 'https://OBSERVIUMIP-OR-DOMAIN/graph.php?type=port_bits&height=300&width=800&id=' . urlencode($port);

    switch ($period) {
        case 'weekly':
            return $baseUrl . '&from=-1w&to=now';
        case 'monthly':
            return $baseUrl . '&from=-1m&to=now';
        default:
            return $baseUrl . '&from=-1d&to=now'; // Daily default
    }
}

// Function to create traffic graph
function displayTrafficGraph($graphUrl) {
    // Getting chart URL with cURL
    $username = 'USERNAME';
    $password = 'PASSWORD';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $graphUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response = curl_exec($ch);
    curl_close($ch);

    // If the cURL response is returned, we return it with a base64 data URL
    if ($response) {
        $base64 = base64_encode($response);
        $dataUrl = 'data:image/png;base64,' . $base64;
        return '
        <div style="margin: 20px 0;">
            <h4>Traffic Graph</h4>
            <img src="' . htmlspecialchars($dataUrl) . '" alt="Traffic Graph" style="max-width: 100%; height: auto;" />
        </div>';
    } else {
        return '<div style="margin: 20px 0;">Graphics could not be retrieved.</div>';
    }
}
